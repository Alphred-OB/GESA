@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $user = auth()->user();
    $firstName = $user ? Str::of($user->fullname ?? $user->username ?? $user->email)->trim()->explode(' ')->first() : 'Admin';
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

<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/75">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-3">
            <button type="button" x-data="{}" x-on:click="$dispatch('admin-sidebar:open')" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 bg-white p-2 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/40 hover:text-[#16136a] lg:hidden" aria-label="Open navigation" aria-controls="admin-mobile-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M4 6h16" />
                    <path d="M4 12h16" />
                    <path d="M4 18h16" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3" data-dropdown>
            <button type="button" data-dropdown-toggle="admin-profile-menu" class="flex items-center gap-2.5 rounded-full border border-slate-200/80 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/40 hover:text-[#16136a]">
                <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-[#16136a]/10 text-sm font-semibold text-[#16136a]">
                    @if ($profileUrl)
                        <img src="{{ $profileUrl }}" alt="{{ $firstName }} avatar" class="h-full w-full object-cover" loading="lazy">
                    @else
                        {{ $avatarInitials }}
                    @endif
                </span>
                <span class="uppercase tracking-[0.2em] text-[11px] text-[#16136a]">{{ Str::upper($firstName) }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="admin-profile-menu" data-dropdown-menu class="invisible absolute right-0 top-full mt-3 w-56 translate-y-1 rounded-2xl border border-slate-200/80 bg-white py-2 text-sm text-slate-600 opacity-0 shadow-xl transition duration-200 ease-out">
                <div class="flex items-center gap-3 px-4 pb-3">
                    <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#16136a]/10 text-base font-semibold text-[#16136a]">
                        @if ($profileUrl)
                            <img src="{{ $profileUrl }}" alt="{{ $firstName }} avatar" class="h-full w-full object-cover" loading="lazy">
                        @else
                            {{ $avatarInitials }}
                        @endif
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $user->fullname ?? $firstName }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email ?? 'gesaumat24@gmail.com' }}</p>
                    </div>
                </div>

                @if ($profileRoute)
                    <a href="{{ $profileRoute }}" class="flex items-center gap-2 px-4 py-2 transition hover:bg-slate-100 focus:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5" />
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        </svg>
                        View profile
                    </a>
                @endif

                <form method="POST" action="{{ route('auth.logout') }}" class="border-t border-slate-200/80 pt-2">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left transition hover:bg-slate-100 focus:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
