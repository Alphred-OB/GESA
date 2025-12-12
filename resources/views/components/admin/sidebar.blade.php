@php
    $navConfig = [
        [
            'label' => 'Overview',
            'route_name' => 'admin.dashboard',
            'pattern' => 'admin.dashboard',
            'icon' => 'ri-dashboard-line',
        ],
        [
            'label' => 'Events',
            'route_name' => 'admin.events.index',
            'pattern' => 'admin.events.*',
            'icon' => 'ri-calendar-event-line',
        ],
        [
            'label' => 'Resources',
            'route_name' => 'admin.resources.index',
            'pattern' => 'admin.resources.*',
            'icon' => 'ri-book-3-line',
        ],
        [
            'label' => 'Student accounts',
            'route_name' => 'admin.students.index',
            'pattern' => 'admin.students.*',
            'icon' => 'ri-team-line',
        ],
        [
            'label' => 'Pending Registrations',
            'route_name' => 'admin.pending-registrations.index',
            'pattern' => 'admin.pending-registrations.*',
            'icon' => 'ri-user-add-line',
        ],
        [
            'label' => 'Dues',
            'route_name' => 'admin.dues.index',
            'pattern' => 'admin.dues.*',
            'icon' => 'ri-money-dollar-circle-line',
        ],
        [
            'label' => 'Announcements',
            'route_name' => 'admin.announcements.index',
            'pattern' => 'admin.announcements.*',
            'icon' => 'ri-megaphone-line',
        ],
        [
            'label' => 'Academic timeline',
            'route_name' => 'admin.timeline.index',
            'pattern' => 'admin.timeline.*',
            'icon' => 'ri-time-line',
        ],
        [
            'label' => 'Suggestions',
            'route_name' => 'admin.suggestions.index',
            'pattern' => 'admin.suggestions.*',
            'icon' => 'ri-customer-service-2-line',
        ],
        [
            'label' => 'Profile',
            'route_name' => 'admin.profile',
            'pattern' => 'admin.profile*',
            'icon' => 'ri-user-settings-line',
        ],
    ];

    $navItems = collect($navConfig)->map(function ($item) {
        $routeName = $item['route_name'] ?? null;
        $href = ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) ? route($routeName) : '#';

        return [
            'label' => $item['label'],
            'icon' => $item['icon'],
            'href' => $href,
            'active' => ! empty($item['pattern']) ? request()->routeIs($item['pattern']) : false,
        ];
    })->toArray();
@endphp

<aside
    x-data="{ loadingShell: true }"
    x-init="setTimeout(() => { loadingShell = false }, 600)"
    class="hidden w-72 shrink-0 border-r border-slate-200/80 bg-white/95 px-6 py-8 text-sm text-slate-600 lg:flex lg:flex-col lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto"
    aria-label="Admin navigation"
>
    <div x-show="loadingShell" x-transition.opacity.duration.200ms class="flex h-full flex-col">
        <div class="flex items-center gap-3 text-[#16136a]">
            <div class="h-10 w-10 rounded-xl bg-slate-200/80 animate-pulse"></div>
            <div class="space-y-2">
                <div class="h-3 w-32 rounded-full bg-slate-200/80 animate-pulse"></div>
                <div class="h-4 w-28 rounded-full bg-slate-200/70 animate-pulse"></div>
            </div>
        </div>

        <div class="mt-8 flex-1 space-y-2">
            @for ($i = 0; $i < 7; $i++)
                <div class="flex items-center gap-3 rounded-2xl px-4 py-3">
                    <div class="h-9 w-9 rounded-xl bg-slate-200/80 animate-pulse"></div>
                    <div class="h-3 w-32 rounded-full bg-slate-200/70 animate-pulse"></div>
                </div>
            @endfor
        </div>

        <div class="mt-auto"></div>
    </div>

    <div x-show="!loadingShell" x-cloak class="flex h-full flex-col">
        <div class="flex items-center gap-3 text-[#16136a]">
            <img src="{{ asset('logo.png') }}" alt="GESA" class="h-10 w-10 rounded-xl border border-[#16136a]/20 object-contain" loading="lazy">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#16136a]/70">GESA Admin</p>
                <p class="text-base font-semibold text-[#16136a]">Control Center</p>
            </div>
        </div>

        <x-admin.sidebar-nav :nav-items="$navItems" class="mt-8 flex-1" />

        <div class="mt-auto"></div>
    </div>
</aside>

<div class="lg:hidden">
    <div x-show="adminSidebarOpen" style="display: none;" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60" aria-hidden="true" @click="adminSidebarOpen = false"></div>

    <div id="admin-mobile-sidebar" x-show="adminSidebarOpen" style="display: none;" x-transition:enter="transition transform ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto border-r border-slate-200/80 bg-white px-6 py-8 text-sm text-slate-600 shadow-2xl">
        <div class="flex items-center justify-between text-[#16136a]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="GESA" class="h-9 w-9 rounded-xl border border-[#16136a]/20 object-contain" loading="lazy">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#16136a]/70">Admin</p>
                    <p class="text-base font-semibold text-[#16136a]">Control Center</p>
                </div>
            </div>
            <button type="button" class="rounded-full border border-slate-200/70 p-2 text-slate-500 transition hover:text-[#16136a]" aria-label="Close sidebar" @click="adminSidebarOpen = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="m6 6 12 12" />
                    <path d="m6 18 12-12" />
                </svg>
            </button>
        </div>

        <x-admin.sidebar-nav :nav-items="$navItems" class="mt-6 flex-1" />

        <div class="mt-6"></div>
    </div>
</div>
