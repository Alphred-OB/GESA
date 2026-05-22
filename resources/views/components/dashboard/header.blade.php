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

<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
    {{-- High-Signal Brand Strip --}}
    <div class="h-[3px] bg-[#16136a] w-full absolute top-0"></div>

    <div class="mx-auto flex w-full max-w-full items-center justify-between px-8 py-3.5">
        {{-- Left: Branding --}}
        <div class="flex items-center gap-3">
            @if ($isAdminRoute)
                <button type="button" x-data="{}" x-on:click="$dispatch('admin-sidebar:open')" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/60 bg-white text-slate-600 shadow-sm transition hover:text-[#16136a] md:hidden">
                    <x-heroicon-o-bars-3 class="size-5" />
                </button>
            @endif
            <a href="{{ $isAdminRoute ? route('admin.dashboard') : route('student.dashboard') }}" class="group flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white p-1 shadow-sm ring-1 ring-slate-200 transition-all group-hover:ring-[#16136a]/20">
                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-full w-full object-contain" loading="lazy">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-base font-semibold tracking-tighter text-slate-900">GESA</span>
                    <span class="text-[9px] font-semibold uppercase tracking-[0.25em] text-[#16136a]/60">{{ $isAdminRoute ? 'Admin Console' : 'Student Portal' }}</span>
                </div>
            </a>
        </div>

        {{-- Right: Navigation & User --}}
        <div class="flex items-center gap-4">
            {{-- Quick Nav Dropdown --}}
            <div class="relative hidden md:block" data-dropdown>
                <button type="button" data-dropdown-toggle="nav-menu" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-50 hover:text-[#16136a]">
                    <x-heroicon-o-bars-3 class="size-5" />
                </button>
                <div id="nav-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-56 translate-y-2 rounded-2xl border border-slate-200/80 bg-white py-2 text-[13px] font-semibold text-slate-600 opacity-0 shadow-2xl ring-1 ring-black/[0.03] transition-all duration-300">
                    <div class="px-4 py-2 text-[10px] uppercase tracking-widest text-slate-400">Navigation</div>
                    <a href="{{ $isAdminRoute ? route('admin.dashboard') : route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-squares-2x2 class="size-5 opacity-40" />
                        Dashboard
                    </a>
                    <a href="{{ route('student.announcements.index') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-bell class="size-5 opacity-40" />
                        Notices
                    </a>
                    <a href="{{ route('student.events.index') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-calendar-days class="size-5 opacity-40" />
                        Events
                    </a>
                    <a href="{{ route('student.dues.index') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-wallet class="size-5 opacity-40" />
                        Dues & Fees
                    </a>
                    <a href="{{ route('student.resources.index') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-folder-open class="size-5 opacity-40" />
                        Library
                    </a>
                </div>
            </div>

            {{-- Profile Pill --}}
            <div class="relative hidden md:block" data-dropdown>
                <button type="button" data-dropdown-toggle="profile-menu" class="group flex items-center gap-3 rounded-xl border border-slate-200/60 bg-white px-2.5 py-1.5 shadow-sm transition-all hover:border-[#16136a]/30 hover:shadow-md">
                    <span class="flex h-6 w-6 items-center justify-center overflow-hidden rounded bg-slate-100 text-[10px] font-semibold text-[#16136a] ring-1 ring-slate-200 transition-all group-hover:bg-[#16136a] group-hover:text-white">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="h-full w-full object-cover">
                        @else
                            {{ $avatarInitials }}
                        @endif
                    </span>
                    <span class="max-w-[120px] truncate text-xs font-semibold tracking-tight text-slate-700">{{ $displayName }}</span>
                    <x-heroicon-o-chevron-down class="text-slate-400 size-5" />
                </button>
                <div id="profile-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-52 translate-y-2 rounded-2xl border border-slate-200/80 bg-white py-2 text-[13px] font-semibold text-slate-600 opacity-0 shadow-2xl ring-1 ring-black/[0.03] transition-all duration-300">
                    <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-2.5 transition hover:bg-slate-50 hover:text-[#16136a]">
                        <x-heroicon-o-cog-6-tooth class="size-5 opacity-40" />
                        Profile Settings
                    </a>
                    <div class="my-1 border-t border-slate-100"></div>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-red-500 transition hover:bg-red-50">
                            <x-heroicon-o-arrow-right-on-rectangle class="size-5 opacity-40" />
                            Log Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div class="relative md:hidden" data-dropdown>
                <button type="button" data-dropdown-toggle="mobile-nav-menu" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-600 transition hover:text-[#16136a]">
                    <x-heroicon-o-bars-3-bottom-right class="size-5" />
                </button>
                <div id="mobile-nav-menu" data-dropdown-menu class="invisible absolute right-0 mt-3 w-64 translate-y-2 rounded-2xl border border-slate-200/80 bg-white py-2 text-[13px] font-semibold text-slate-600 opacity-0 shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-4 mb-2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-sm font-semibold text-[#16136a]">
                            {{ $avatarInitials }}
                        </span>
                        <div class="flex flex-col min-w-0">
                            <span class="truncate text-sm font-semibold text-slate-900">{{ $displayName }}</span>
                            <span class="truncate text-[10px] text-slate-400 font-semibold uppercase tracking-widest">{{ $user->email ?? 'Student Account' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50">
                        <x-heroicon-o-squares-2x2 class="opacity-40 size-5" /> Dashboard
                    </a>
                    <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50">
                        <x-heroicon-o-user class="opacity-40 size-5" /> View Profile
                    </a>
                    <div class="my-2 border-t border-slate-100"></div>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-red-500">
                            <x-heroicon-o-arrow-right-on-rectangle class="opacity-40 size-5" /> Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
