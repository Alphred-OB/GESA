@props(['navItems'])

{{-- 
    Live Polling Sidebar Navigation
    Badges for Verifications and Dues are updated in real-time via AJAX polling.
    The polling pauses when admin is actively interacting (modal open, form focused).
--}}

<nav 
    x-data="liveNavBadges()"
    x-init="startPolling()"
    {{ $attributes->class(['flex flex-col gap-1']) }}
>
    @foreach ($navItems as $index => $item)
        @php
            $isVerificationsLink = str_contains($item['href'], 'verifications');
            $isDuesLink = str_contains($item['href'], 'dues') && !$isVerificationsLink;
        @endphp
        <a 
            href="{{ $item['href'] }}" 
            @class([
                'flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition relative',
                'bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20' => $item['active'],
                'text-slate-600 hover:bg-[#16136a]/5 hover:text-[#16136a]' => ! $item['active'],
            ])
        >
            <span @class([
                'flex h-9 w-9 items-center justify-center rounded-xl border transition',
                'border-white/50 bg-white/10 text-white' => $item['active'],
                'border-[#16136a]/15 bg-white text-[#16136a]/70 hover:border-[#16136a]/40' => ! $item['active'],
            ])>
                <i class="{{ $item['icon'] }} text-lg" aria-hidden="true"></i>
            </span>
            <span class="flex-1">{{ $item['label'] }}</span>
            
            {{-- Dynamic badge for Verifications --}}
            @if ($isVerificationsLink)
                <template x-if="pendingCount > 0">
                    <span 
                        x-text="pendingCount > 99 ? '99+' : pendingCount"
                        :class="{
                            'bg-white/20 text-white': {{ $item['active'] ? 'true' : 'false' }},
                            'bg-red-500 text-white animate-pulse': {{ $item['active'] ? 'false' : 'true' }}
                        }"
                        class="flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-bold transition-all"
                    ></span>
                </template>
            {{-- Dynamic badge for Dues (same count) --}}
            @elseif ($isDuesLink)
                <template x-if="pendingCount > 0">
                    <span 
                        x-text="pendingCount > 99 ? '99+' : pendingCount"
                        :class="{
                            'bg-white/20 text-white': {{ $item['active'] ? 'true' : 'false' }},
                            'bg-amber-500 text-white': {{ $item['active'] ? 'false' : 'true' }}
                        }"
                        class="flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-bold transition-all"
                    ></span>
                </template>
            {{-- Static badges for other nav items --}}
            @elseif (!empty($item['badge']) && $item['badge'] > 0)
                <span @class([
                    'flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-bold',
                    'bg-white/20 text-white' => $item['active'],
                    'bg-red-500 text-white' => ! $item['active'],
                ])>
                    {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                </span>
            @endif
        </a>
    @endforeach
</nav>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('liveNavBadges', () => ({
        pendingCount: {{ \App\Models\Due::where('payment_status', 'pending_verification')->count() }},
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
                }
            }, this.POLL_INTERVAL_MS);
            
            // Also fetch when page becomes visible again
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden && !this.isPaused) {
                    this.fetchPendingCount();
                }
            });
            
            // Listen for verification-in-progress event (when admin is actively verifying)
            window.addEventListener('verification-in-progress', (e) => {
                if (e.detail?.paused) {
                    this.isPaused = true;
                } else {
                    this.isPaused = false;
                    this.fetchPendingCount();
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
