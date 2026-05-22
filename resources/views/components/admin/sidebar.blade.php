@php
    $navGroups = [
        'Main' => [
            [
                'label' => 'Overview',
                'route_name' => 'admin.dashboard',
                'pattern' => 'admin.dashboard',
                'icon' => 'heroicon-o-squares-2x2',
            ],
            [
                'label' => 'Announcements',
                'route_name' => 'admin.announcements.index',
                'pattern' => 'admin.announcements.*',
                'icon' => 'heroicon-o-megaphone',
            ],
        ],
        'Management' => [
            [
                'label' => 'Student accounts',
                'route_name' => 'admin.students.index',
                'pattern' => 'admin.students.*',
                'icon' => 'heroicon-o-users',
            ],
            [
                'label' => 'Pending registrations',
                'route_name' => 'admin.pending-registrations.index',
                'pattern' => 'admin.pending-registrations.*',
                'icon' => 'heroicon-o-user-plus',
                'is_pending_registrations' => true,
            ],
            [
                'label' => 'Verifications',
                'route_name' => 'admin.dues.verifications',
                'pattern' => ['admin.dues.verifications', 'admin.dues.verify-payment'],
                'icon' => 'heroicon-o-check-circle',
                'is_verifications' => true,
            ],
            [
                'label' => 'Financial records',
                'route_name' => 'admin.dues.index',
                'pattern' => ['admin.dues.index', 'admin.dues.create', 'admin.dues.edit', 'admin.dues.statistics', 'admin.dues.export'],
                'icon' => 'heroicon-o-currency-dollar',
                'is_dues' => true,
            ],
            [
                'label' => 'Payment settings',
                'route_name' => 'admin.payment-settings.index',
                'pattern' => ['admin.payment-settings.index'],
                'icon' => 'heroicon-o-credit-card',
            ],
        ],
        'Academics' => [
            [
                'label' => 'Academic timeline',
                'route_name' => 'admin.timeline.index',
                'pattern' => 'admin.timeline.*',
                'icon' => 'heroicon-o-clock',
            ],
            [
                'label' => 'Resource hub',
                'route_name' => 'admin.resources.index',
                'pattern' => 'admin.resources.*',
                'icon' => 'heroicon-o-book-open',
            ],
            [
                'label' => 'Event schedule',
                'route_name' => 'admin.events.index',
                'pattern' => 'admin.events.*',
                'icon' => 'heroicon-o-calendar-days',
            ],
        ],
        'Feedback' => [
            [
                'label' => 'Suggestions',
                'route_name' => 'admin.suggestions.index',
                'pattern' => 'admin.suggestions.*',
                'icon' => 'heroicon-o-chat-bubble-left-ellipsis',
            ],
        ],
        'Personal' => [
            [
                'label' => 'My Dues',
                'route_name' => 'admin.personal-dues.index',
                'pattern' => 'admin.personal-dues.*',
                'icon' => 'heroicon-o-wallet',
            ],
            [
                'label' => 'Account settings',
                'route_name' => 'admin.profile',
                'pattern' => 'admin.profile*',
                'icon' => 'heroicon-o-cog-8-tooth',
            ],
        ],
    ];

    $processedGroups = [];
    foreach ($navGroups as $groupLabel => $items) {
        $processedGroups[$groupLabel] = collect($items)->map(function ($item) {
            $routeName = $item['route_name'] ?? null;
            $href = ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) ? route($routeName) : '#';

            return [
                'label' => $item['label'],
                'icon' => $item['icon'],
                'href' => $href,
                'active' => ! empty($item['pattern']) ? request()->routeIs($item['pattern']) : false,
                'is_verifications' => $item['is_verifications'] ?? false,
                'is_dues' => $item['is_dues'] ?? false,
                'is_pending_registrations' => $item['is_pending_registrations'] ?? false,
            ];
        })->toArray();
    }
@endphp

<aside
    class="hidden w-[280px] border-r border-slate-200/60 bg-white px-4 py-8 text-sm text-slate-600 lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:flex-col"
    aria-label="Admin navigation"
>
    <!-- Brand Header -->
    <div class="px-4 mb-10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('logo.png') }}" alt="GESA" class="h-10 w-10 object-contain" loading="lazy">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Administrator</p>
                <p class="text-lg font-semibold tracking-tight text-[#16136a]">GESA Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation Scroll Area -->
    <div class="flex-1 overflow-y-auto px-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <x-admin.sidebar-nav :nav-groups="$processedGroups" />
    </div>

    <!-- Sidebar Footer -->
    <div class="mt-auto px-2 pt-6">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 shrink-0 rounded-full bg-[#16136a] text-white flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->fullname }}</p>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="text-[11px] font-semibold text-red-600 hover:underline">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<div class="lg:hidden">
    <div x-show="adminSidebarOpen" style="display: none;" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-md" aria-hidden="true" @click="adminSidebarOpen = false"></div>

    <div id="admin-mobile-sidebar" x-show="adminSidebarOpen" style="display: none;" x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-72 border-r border-slate-200/40 bg-white/95 px-4 py-8 text-sm text-slate-600 shadow-2xl backdrop-blur-2xl flex flex-col">
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center gap-3">
                <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-xl border border-slate-200/50 bg-white p-1 shadow-sm">
                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-full w-full object-contain" loading="lazy">
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-[#16136a]/40">Admin Engine</p>
                    <p class="text-base font-semibold tracking-tight text-[#16136a]">GESA <span class="text-slate-400">Hub</span></p>
                </div>
            </div>
            <button type="button" class="rounded-full bg-slate-100 p-2 text-slate-400 transition hover:bg-slate-200 hover:text-[#16136a]" aria-label="Close sidebar" @click="adminSidebarOpen = false">
                <x-heroicon-o-x-mark class="size-5" />
            </button>
        </div>

        <div class="mt-8 flex-1 overflow-y-auto px-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <x-admin.sidebar-nav :nav-groups="$processedGroups" />
        </div>

        <!-- Mobile Sidebar Footer (Logout only for space) -->
        <div class="mt-auto px-2 pt-6">
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-red-50 py-3 text-sm font-semibold text-red-600 transition-all active:scale-95">
                    <x-heroicon-o-arrow-right-on-rectangle class="size-5" />
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>
