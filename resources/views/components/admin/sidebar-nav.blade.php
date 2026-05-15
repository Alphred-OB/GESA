@props(['navGroups'])

<nav 
    x-data="{ 
        ...liveNavBadges(), 
        openGroups: {
            @foreach($navGroups as $groupLabel => $items)
                '{{ $groupLabel }}': {{ collect($items)->contains('active', true) ? 'true' : 'false' }},
            @endforeach
        },
        toggleGroup(label) {
            this.openGroups[label] = !this.openGroups[label];
        }
    }"
    x-init="startPolling()"
    {{ $attributes->class(['flex flex-col space-y-4']) }}
>
    @foreach ($navGroups as $groupLabel => $items)
        <div class="space-y-1">
            <button 
                @click="toggleGroup('{{ $groupLabel }}')"
                class="flex w-full items-center justify-between px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.25em] text-slate-400 transition-colors hover:text-[#16136a]"
            >
                <div class="flex items-center gap-2">
                    <span>{{ $groupLabel }}</span>
                    <span class="h-px w-8 bg-slate-100"></span>
                </div>
                <i 
                    class="ri-arrow-down-s-line text-xs transition-transform duration-300"
                    :class="{ 'rotate-180': openGroups['{{ $groupLabel }}'] }"
                ></i>
            </button>
            
            <div 
                x-show="openGroups['{{ $groupLabel }}']"
                x-collapse
                class="space-y-1"
            >
                @foreach ($items as $item)
                    @php
                        $isActive = $item['active'];
                    @endphp
                    <a 
                        href="{{ $item['href'] }}" 
                        @class([
                            'group relative flex items-center gap-3 rounded-2xl px-4 py-2.5 text-[13px] font-semibold transition-all duration-200',
                            'bg-[#16136a] text-white shadow-md shadow-[#16136a]/10' => $isActive,
                            'text-slate-500 hover:bg-slate-50 hover:text-[#16136a]' => !$isActive,
                        ])
                    >
                        @if($isActive)
                            <div class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-white/40"></div>
                        @endif

                        <span @class([
                            'flex h-7 w-7 shrink-0 items-center justify-center rounded-lg transition-all duration-200',
                            'bg-white/10 text-white' => $isActive,
                            'bg-slate-50 text-slate-400 border border-slate-200/50 group-hover:bg-slate-200 group-hover:text-[#16136a]' => !$isActive,
                        ])>
                            <i class="{{ $item['icon'] }} text-sm"></i>
                        </span>
                        
                        <span class="flex-1 tracking-tight">{{ $item['label'] }}</span>
                        
                        {{-- Live Badges --}}
                        @if ($item['is_pending_registrations'])
                            <template x-if="pendingRegistrationsCount > 0">
                                <span 
                                    x-text="pendingRegistrationsCount > 99 ? '99+' : pendingRegistrationsCount"
                                    :class="{
                                        'bg-white/20 text-white': {{ $isActive ? 'true' : 'false' }},
                                        'bg-red-500 text-white shadow-sm': {{ $isActive ? 'false' : 'true' }}
                                    }"
                                    class="flex h-5 min-w-5 items-center justify-center rounded-lg px-1.5 text-[9px] font-semibold tabular-nums transition-all"
                                ></span>
                            </template>
                        @elseif ($item['is_verifications'])
                            <template x-if="pendingCount > 0">
                                <span 
                                    x-text="pendingCount > 99 ? '99+' : pendingCount"
                                    :class="{
                                        'bg-white/20 text-white': {{ $isActive ? 'true' : 'false' }},
                                        'bg-emerald-500 text-white shadow-sm': {{ $isActive ? 'false' : 'true' }}
                                    }"
                                    class="flex h-5 min-w-5 items-center justify-center rounded-lg px-1.5 text-[9px] font-semibold tabular-nums transition-all"
                                ></span>
                            </template>
                        @elseif ($item['is_dues'])
                            <template x-if="pendingCount > 0">
                                <span 
                                    x-text="pendingCount > 99 ? '99+' : pendingCount"
                                    :class="{
                                        'bg-white/20 text-white': {{ $isActive ? 'true' : 'false' }},
                                        'bg-amber-500 text-white shadow-sm': {{ $isActive ? 'false' : 'true' }}
                                    }"
                                    class="flex h-5 min-w-5 items-center justify-center rounded-lg px-1.5 text-[9px] font-semibold tabular-nums transition-all"
                                ></span>
                            </template>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('liveNavBadges', () => ({
        pendingCount: {{ \App\Models\Due::where('payment_status', 'pending_verification')->count() }},
        pendingRegistrationsCount: {{ \App\Models\PendingRegistration::where('status', 'pending')->count() }},
        isPolling: false,
        pollInterval: null,
        isPaused: false,
        lastFetch: null,
        
        // Poll every 5 seconds for quick updates
        POLL_INTERVAL_MS: 5000,
        
        startPolling() {
            // Only start polling if not already active
            if (this.isPolling) return;
            this.isPolling = true;
            
            // Set up pause detection (when admin is interacting with forms/modals)
            this.setupPauseDetection();
            
            // Start the polling loop
            this.pollInterval = setInterval(() => {
                if (!this.isPaused && !document.hidden) {
                    this.fetchPendingCount();
                    this.fetchPendingRegistrations();
                }
            }, this.POLL_INTERVAL_MS);
            
            // Also fetch when page becomes visible again
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden && !this.isPaused) {
                    this.fetchPendingCount();
                    this.fetchPendingRegistrations();
                }
            });
            
            // Listen for verification-in-progress event (when admin is actively verifying)
            window.addEventListener('verification-in-progress', (e) => {
                if (e.detail?.paused) {
                    this.isPaused = true;
                } else {
                    this.isPaused = false;
                    this.fetchPendingCount();
                    this.fetchPendingRegistrations();
                }
            });
        },
        
        setupPauseDetection() {
            // Pause polling when:
            // 1. Any form input is focused
            // 2. A modal is open (detected by common modal classes)
            // 3. Admin is typing in textarea
            
            const pauseTriggers = ['input', 'textarea', 'select'];
            
            pauseTriggers.forEach(tag => {
                document.addEventListener('focusin', (e) => {
                    if (e.target.matches(`${tag}, [contenteditable="true"]`)) {
                        this.isPaused = true;
                    }
                });
                
                document.addEventListener('focusout', (e) => {
                    if (e.target.matches(`${tag}, [contenteditable="true"]`)) {
                        // Small delay to handle focus switching between fields
                        setTimeout(() => {
                            if (!document.activeElement.matches('input, textarea, select, [contenteditable="true"]')) {
                                this.isPaused = false;
                                // Immediately fetch after resuming
                                this.fetchPendingCount();
                                this.fetchPendingRegistrations();
                            }
                        }, 300);
                    }
                });
            });
            
            // Also pause during any modal visibility (Alpine x-show modals)
            const observer = new MutationObserver(() => {
                const hasOpenModal = document.querySelector('[role="dialog"]:not([style*="display: none"]), .modal:not(.hidden), [x-show]:not([style*="display: none"])[aria-modal="true"]');
                if (hasOpenModal) {
                    this.isPaused = true;
                }
            });
            
            observer.observe(document.body, { childList: true, subtree: true, attributes: true });
        },
        
        async fetchPendingCount() {
            try {
                const response = await fetch('{{ route("admin.api.dues.pending-verifications") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const newCount = data.total_count || 0;
                    
                    // Only update and notify if count changed
                    if (newCount !== this.pendingCount) {
                        const wasHigher = newCount > this.pendingCount;
                        this.pendingCount = newCount;
                        this.lastFetch = new Date();
                        
                        // Dispatch event for other components (like the dues table) to refresh
                        if (wasHigher) {
                            window.dispatchEvent(new CustomEvent('new-verification-arrived', { 
                                detail: { count: newCount, data: data.verifications } 
                            }));
                        } else {
                            window.dispatchEvent(new CustomEvent('verification-count-updated', { 
                                detail: { count: newCount } 
                            }));
                        }
                    }
                }
            } catch (error) {
                // Silent fail - don't interrupt the admin
                console.debug('Live poll failed:', error);
            }
        },
        
        async fetchPendingRegistrations() {
            try {
                const response = await fetch('{{ route("admin.api.pending-registrations.count") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const newCount = data.total_count || 0;
                    
                    // Only update and notify if count changed
                    if (newCount !== this.pendingRegistrationsCount) {
                        const wasHigher = newCount > this.pendingRegistrationsCount;
                        this.pendingRegistrationsCount = newCount;
                        
                        // Dispatch event for other components to refresh
                        if (wasHigher) {
                            window.dispatchEvent(new CustomEvent('new-registration-arrived', { 
                                detail: { count: newCount, data: data.registrations } 
                            }));
                        } else {
                            window.dispatchEvent(new CustomEvent('registration-count-updated', { 
                                detail: { count: newCount } 
                            }));
                        }
                    }
                }
            } catch (error) {
                // Silent fail - don't interrupt the admin
                console.debug('Pending registrations poll failed:', error);
            }
        },
        
        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
                this.isPolling = false;
            }
        }
    }));
});
</script>
