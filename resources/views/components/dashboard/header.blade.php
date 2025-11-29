@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $user = auth()->user();
    $rawName = $user ? ($user->username ?: ($user->fullname ?: $user->email)) : 'student';
    $displayName = Str::of($rawName)->trim();
    $avatarInitials = Str::of($displayName)->substr(0, 1)->upper();
    $avatarUrl = null;

    $isAdminRoute = request()->routeIs('admin.*');

    if ($user && $user->profile_picture) {
        if (Str::startsWith($user->profile_picture, ['http://', 'https://'])) {
            $avatarUrl = $user->profile_picture;
        } elseif (Storage::disk('public')->exists($user->profile_picture)) {
            $avatarUrl = Storage::disk('public')->url($user->profile_picture);
        }
    }
@endphp

<header x-data="{ loadingShell: true }" x-init="setTimeout(() => { loadingShell = false }, 600)" class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95">
    <div x-show="loadingShell" x-transition.opacity.duration.200ms class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-slate-200/80 animate-pulse"></div>
            <div class="h-4 w-40 rounded-full bg-slate-200/80 animate-pulse"></div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden h-9 w-9 rounded-full bg-slate-200/80 animate-pulse md:block"></div>
            <div class="h-9 w-32 rounded-full bg-slate-200/80 animate-pulse"></div>
        </div>
    </div>

    <div x-show="!loadingShell" x-cloak class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            @if ($isAdminRoute)
                <button type="button" x-data="{}" x-on:click="$dispatch('admin-sidebar:open')" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 bg-white p-2 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/40 hover:text-[#16136a] md:hidden" aria-label="Open navigation" aria-controls="admin-mobile-sidebar">
                    <i class="ri-menu-line text-[20px]" aria-hidden="true"></i>
                </button>
            @endif
            <a href="{{ $isAdminRoute ? route('admin.dashboard') : route('student.dashboard') }}" class="inline-flex items-center gap-3 text-[#16136a]">
                <img src="{{ asset('logo.png') }}" alt="GESA Portal" class="h-10 w-10 rounded-xl border border-[#16136a]/20 object-contain" loading="lazy">
                <span class="text-lg font-semibold">{{ $isAdminRoute ? 'GESA Admin Console' : 'GESA Student Portal' }}</span>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden md:block" data-dropdown>
                <button type="button" data-dropdown-toggle="nav-menu" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 bg-white p-2 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/40 hover:text-[#16136a]" aria-label="Open menu">
                    <i class="ri-menu-line text-[20px]" aria-hidden="true"></i>
                </button>
                <div id="nav-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-52 translate-y-1 rounded-2xl border border-slate-200/80 bg-white py-3 text-sm text-slate-600 opacity-0 shadow-xl transition duration-200 ease-out">
                    <a href="{{ $isAdminRoute ? route('admin.dashboard') : route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-layout-grid-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('student.announcements.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-megaphone-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Announcements
                    </a>
                    <a href="{{ route('student.events.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-calendar-event-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Events
                    </a>
                    <a href="{{ route('student.dues.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-bill-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Dues
                    </a>
                    <a href="{{ route('student.resources.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-book-open-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Resources
                    </a>
                    <a href="{{ route('student.suggestions.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-lightbulb-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Suggestion box
                    </a>
                </div>
            </div>
            <div class="relative hidden max-w-[240px] md:block" data-dropdown>
                <button type="button" data-dropdown-toggle="profile-menu" class="flex items-center gap-3 rounded-full border border-slate-200/70 bg-white px-2 py-1.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-[#16136a]/10 text-base font-semibold text-[#16136a]">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $displayName }} avatar" class="h-full w-full object-cover" loading="lazy">
                        @else
                            {{ $avatarInitials }}
                        @endif
                    </span>
                    <span class="max-w-[140px] truncate">{{ $displayName }}</span>
                    <i class="ri-arrow-down-s-line text-base text-slate-400" aria-hidden="true"></i>
                </button>
                <div id="profile-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-48 translate-y-1 rounded-2xl border border-slate-200/80 bg-white py-2 text-sm text-slate-600 opacity-0 shadow-xl transition duration-200 ease-out">
                    <a href="{{ route('student.profile') }}" class="flex items-center gap-2 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-user-3-line text-base" aria-hidden="true"></i>
                        View profile
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left transition hover:bg-slate-100 focus:bg-slate-100">
                            <i class="ri-logout-box-r-line text-base" aria-hidden="true"></i>
                            Log out
                        </button>
                    </form>
                </div>
            </div>
            <div class="relative md:hidden" data-dropdown>
                <button type="button" data-dropdown-toggle="mobile-nav-menu" class="inline-flex items-center justify-center rounded-xl border border-slate-200/70 p-2 text-slate-600 transition hover:-translate-y-0.5 hover:text-[#16136a]" aria-label="Open menu">
                    <i class="ri-menu-line text-[20px]" aria-hidden="true"></i>
                </button>
                <div id="mobile-nav-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-64 translate-y-1 rounded-2xl border border-slate-200/80 bg-white py-3 text-sm text-slate-600 opacity-0 shadow-xl transition duration-200 ease-out">
                    <div class="flex items-center gap-3 px-4 pb-3">
                            <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#16136a]/10 text-base font-semibold text-[#16136a]">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $displayName }} avatar" class="h-full w-full object-cover" loading="lazy">
                                @else
                                    {{ $avatarInitials }}
                                @endif
                            </span>
                        <div class="flex min-w-0 flex-1 flex-col">
                            <span class="max-w-[140px] truncate text-sm font-semibold text-slate-900">{{ $displayName }}</span>
                            <span class="text-xs text-slate-500">{{ $user->email ?? 'student@acses.edu' }}</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200/80 pb-2">
                        <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-2 text-[#16136a] transition hover:bg-slate-100 focus:bg-slate-100">
                            <i class="ri-user-3-line text-base" aria-hidden="true"></i>
                            View profile
                        </a>
                    </div>
                    <a href="{{ $isAdminRoute ? route('admin.dashboard') : route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-layout-grid-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('student.announcements.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-megaphone-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Announcements
                    </a>
                    <a href="{{ route('student.events.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-calendar-event-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Events
                    </a>
                    <a href="{{ route('student.dues.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-bill-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Dues
                    </a>
                    <a href="{{ route('student.resources.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-book-open-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Resources
                    </a>
                    <a href="{{ route('student.suggestions.index') }}" class="flex items-center gap-3 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <i class="ri-lightbulb-line text-base text-[#16136a]" aria-hidden="true"></i>
                        Suggestion box
                    </a>
                    <div class="mt-2 border-t border-slate-200 pt-2">
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-left transition hover:bg-slate-100 focus:bg-slate-100">
                                <i class="ri-logout-box-r-line text-base" aria-hidden="true"></i>
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
