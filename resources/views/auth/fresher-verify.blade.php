<x-layouts.auth title="Verify Email">
    <div class="auth-card-hover space-y-8 p-10">
        <div class="space-y-4 text-center">
            <div class="stagger-1 mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[#16136a]/5 text-[#16136a] shadow-inner backdrop-blur-sm">
                <i class="ri-mail-check-line text-4xl"></i>
            </div>
            <div class="space-y-2">
                <h1 class="stagger-2 text-3xl font-semibold tracking-tight text-slate-900">Verify Your Email</h1>
                <p class="stagger-3 text-base text-slate-500 leading-relaxed">
                    We have sent a verification code to your email. <br>
                    Please enter it below to complete your registration.
                </p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="stagger-1 rounded-xl border border-green-200 bg-green-50/50 p-4 text-center animate-fade-slide-up">
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="stagger-1 rounded-xl border border-red-200 bg-red-50/50 p-4 text-center animate-fade-slide-up">
                <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.fresher-register.verify.submit') }}" class="space-y-8" data-auth-form>
            @csrf
            
            <div class="stagger-4 space-y-4">
                <label for="code" class="block text-center text-sm font-semibold uppercase tracking-widest text-slate-500">Verification Code</label>
                <div class="group auth-input-group relative">
                    <input id="code" type="text" name="code" required autofocus autocomplete="one-time-code" placeholder="123456" 
                        class="auth-input w-full rounded-2xl border-slate-200 bg-slate-50/10 px-4 py-4 text-center text-3xl font-semibold tracking-[0.5em] text-[#16136a] placeholder:tracking-normal placeholder:text-slate-300 transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" />
                </div>
                @error('code')
                    <p class="text-center text-sm font-semibold text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="stagger-5 auth-button-press group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-8 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                <div class="flex items-center space-x-3 transition-transform duration-300 group-hover:scale-105">
                    <span>Verify Account</span>
                    <i class="ri-shield-check-line text-lg"></i>
                </div>
            </button>
        </form>

        <div class="stagger-5 space-y-6 text-center">
            <p class="text-sm text-slate-500 leading-relaxed px-4">
                Didn't receive the code? Check your spam folder.
            </p>
            <form method="POST" action="{{ route('auth.fresher-register.resend') }}">
                @csrf
                <button type="submit" class="group flex items-center gap-2 mx-auto rounded-2xl bg-[#16136a]/5 px-6 py-3 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:bg-[#16136a]/10 hover:scale-105 active:scale-95">
                    <i class="ri-refresh-line transition-transform duration-500 group-hover:rotate-180"></i>
                    <span>Resend Code</span>
                </button>
            </form>
        </div>

        <div class="stagger-5 border-t border-slate-200/50 pt-8 text-center">
            <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:scale-105">
                <i class="ri-arrow-left-line transition-transform duration-300 group-hover:-translate-x-1"></i>
                <span>Back to Login</span>
            </a>
        </div>
    </div>
</x-layouts.auth>

