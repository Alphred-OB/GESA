@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $user = auth()->user();
    $fullName = $user->fullname ?? $user->username ?? 'Admin';
    $firstName = Str::of($fullName)->trim()->explode(' ')->first();
    $avatarInitials = Str::of($firstName)->substr(0, 1)->upper();
    $profileUrl = null;

    if ($user && $user->profile_picture) {
        $profileUrl = Str::startsWith($user->profile_picture, ['http://', 'https://'])
            ? $user->profile_picture
            : (Storage::disk('public')->exists($user->profile_picture)
                ? Storage::disk('public')->url($user->profile_picture)
                : null);
    }

    $profileRoute = Route::has('admin.profile') ? route('admin.profile') : null;
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200/50 bg-white/80 backdrop-blur-xl">
    <div class="flex h-16 items-center justify-between px-4 sm:px-8">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button type="button" x-data="{}" x-on:click="$dispatch('admin-sidebar:open')" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/60 bg-white p-2 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-[#16136a] lg:hidden" aria-label="Open navigation">
                <i class="ri-menu-2-line text-lg"></i>
            </button>

            <!-- Breadcrumb / Context Placeholder -->
            <div class="hidden lg:flex lg:items-center lg:gap-2">
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Dashboard</span>
                <i class="ri-arrow-right-s-line text-slate-300"></i>
                <span class="text-xs font-semibold uppercase tracking-widest text-[#16136a]">Overview</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Action Icons (Optional) -->
            <div class="hidden items-center gap-2 pr-4 border-r border-slate-200/50 md:flex">
                <button type="button" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <i class="ri-notification-3-line text-xl"></i>
                </button>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" data-dropdown>
                <button type="button" data-dropdown-toggle="admin-profile-menu" class="group flex items-center gap-3 rounded-full border border-slate-200/60 bg-white/50 py-1.5 pl-1.5 pr-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-white hover:shadow-md">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-[#16136a]/5">
                        @if ($profileUrl)
                            <img src="{{ $profileUrl }}" alt="{{ $firstName }} avatar" class="h-full w-full object-cover" loading="lazy">
                        @else
                            <span class="text-xs font-semibold text-[#16136a]">{{ $avatarInitials }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[13px] tracking-tight group-hover:text-[#16136a]">{{ $firstName }}</span>
                        <i class="ri-arrow-down-s-line text-slate-400 transition group-hover:text-[#16136a]"></i>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div id="admin-profile-menu" data-dropdown-menu class="invisible absolute right-0 top-full mt-2 w-64 translate-y-1 overflow-hidden rounded-2xl border border-slate-200/60 bg-white p-1.5 opacity-0 shadow-2xl transition duration-200 ease-out">
                    <div class="mb-1.5 px-3 py-2.5">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Account</p>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#16136a] text-white">
                                <span class="font-semibold">{{ $avatarInitials }}</span>
                            </div>
                            <div class="overflow-hidden">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $fullName }}</p>
                                <p class="truncate text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-0.5">
                        @if ($profileRoute)
                            <a href="{{ $profileRoute }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-[#16136a]/5 hover:text-[#16136a]">
                                <i class="ri-user-settings-line text-lg opacity-60"></i>
                                <span>Settings</span>
                            </a>
                        @endif
                        
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm font-medium text-red-500 transition hover:bg-red-50">
                                <i class="ri-logout-box-r-line text-lg opacity-80"></i>
                                <span>Log out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
