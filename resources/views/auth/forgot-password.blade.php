@php($title = 'Forgot password')

<x-layouts.auth :title="$title" card-width="max-w-lg">
    <div class="auth-card-hover p-8 md:p-12">
        <div class="mb-10 text-center">
            <div class="stagger-1 mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#16136a]/5 text-[#16136a] shadow-inner mx-auto backdrop-blur-sm">
                <x-heroicon-o-key class="size-8" aria-hidden="true" />
            </div>
            <h1 class="stagger-2 text-3xl font-semibold tracking-tight text-slate-900">Reset password</h1>
            <p class="stagger-3 mt-2 text-base text-slate-500">
                Enter your email and we'll send you a link to reset your password.
            </p>
        </div>

        @if (session('status'))
            <div class="stagger-1 mb-6 rounded-2xl border border-green-100 bg-green-50/50 px-5 py-4 text-sm font-medium text-green-700 backdrop-blur-sm animate-fade-slide-up">
                <div class="flex items-center gap-3">
                    <x-heroicon-s-check-circle class="size-6" />
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="stagger-4 space-y-2">
                <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                <div class="group auth-input-group relative">
                    <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                        <x-heroicon-o-envelope class="size-5" aria-hidden="true" />
                    </span>
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                        class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10"
                        placeholder="you@example.com" />
                </div>
                @error('email')
                    <p class="mt-1 text-xs font-semibold text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="stagger-5 auth-button-press group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-4 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                <div class="flex items-center space-x-2 transition-transform duration-300 group-hover:scale-105">
                    <span>Send Reset Link</span>
                    <x-heroicon-s-paper-airplane class="transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-0.5 size-5" />
                </div>
            </button>
        </form>

        <div class="stagger-5 mt-10 border-t border-slate-200/50 pt-8 text-center">
            <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:scale-105">
                <x-heroicon-o-arrow-left class="transition-transform duration-300 group-hover:-translate-x-1 size-5" />
                <span>Back to sign in</span>
            </a>
        </div>
    </div>
</x-layouts.auth>
