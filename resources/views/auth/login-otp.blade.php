@php
    $oldCode = old('code', '');
    $codeDigits = array_slice(array_pad(str_split($oldCode), 6, ''), 0, 6);
@endphp

<x-layouts.auth title="Enter Login Code" card-width="max-w-lg">
    <div class="auth-card-hover space-y-8 p-10">
        <div class="space-y-4 text-center">
            <div class="stagger-1 mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#16136a]/5 text-[#16136a] shadow-inner backdrop-blur-sm">
                <x-heroicon-o-paper-airplane class="text-4xl size-5" />
            </div>

            <div class="space-y-2">
                <h1 class="stagger-2 text-3xl font-semibold tracking-tight text-slate-900">Check your email</h1>
                <p class="stagger-3 text-base text-slate-500 leading-relaxed">
                    We sent a 6-digit code to <br>
                    <span class="font-semibold text-[#16136a]">{{ $pending['email'] ?? 'your email' }}</span>
                </p>
                @if (session('status'))
                    <div class="stagger-1 mx-auto mt-4 max-w-xs rounded-xl border border-green-100 bg-green-50/50 px-4 py-3 text-sm font-medium text-green-700 animate-fade-slide-up">
                        <div class="flex items-center justify-center gap-2">
                            <x-heroicon-s-check-circle class="size-5" />
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('auth.login.otp.submit') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="stagger-4 space-y-4" data-otp-container data-otp-target="#login-code">
                <label for="login-code" class="block text-center text-sm font-semibold uppercase tracking-widest text-slate-500">Verification Code</label>
                <input id="login-code" name="code" type="hidden" value="{{ $oldCode }}" required>
                <div class="flex justify-center gap-3">
                    @foreach ($codeDigits as $index => $digit)
                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                            data-otp-input
                            class="h-16 w-12 rounded-2xl border border-slate-200 bg-slate-50/10 text-center text-2xl font-semibold text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10"
                            aria-label="Login code digit {{ $index + 1 }}"
                            value="{{ $digit }}"
                        >
                    @endforeach
                </div>
                @error('code')
                    <p class="text-center text-sm font-semibold text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="stagger-5 auth-button-press group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-4 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                <div class="flex items-center space-x-2 transition-transform duration-300 group-hover:scale-105">
                    <span>Verify Account</span>
                    <x-heroicon-o-shield-check class="size-5" />
                </div>
            </button>
        </form>

        <div class="stagger-5 space-y-6 text-center">
            <p class="text-sm text-slate-500 leading-relaxed px-4">
                Didn’t receive the code? Check your spam folder or promotions tab.
            </p>
            <form method="POST" action="{{ route('auth.login.otp.resend') }}" class="flex items-center justify-center">
                @csrf
                <button type="submit" class="group flex items-center gap-2 rounded-2xl bg-[#16136a]/5 px-6 py-3 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:bg-[#16136a]/10 hover:scale-105 active:scale-95">
                    <x-heroicon-o-arrow-path class="transition-transform duration-500 group-hover:rotate-180 size-5" />
                    <span>Resend Code</span>
                </button>
            </form>
        </div>

        <div class="stagger-5 border-t border-slate-200/50 pt-8 text-center">
            <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:scale-105">
                <x-heroicon-o-arrow-left class="transition-transform duration-300 group-hover:-translate-x-1 size-5" />
                <span>Back to sign in</span>
            </a>
        </div>
    </div>
</x-layouts.auth>
