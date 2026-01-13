<x-layouts.auth title="Verify Email">
    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-[#16136a]">Verify Your Email</h1>
            <p class="mt-2 text-sm text-slate-600">
                We have sent a verification code to your email address. Please enter it below to complete your registration.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-center">
                <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-center">
                <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @error('verification')
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-center">
                <p class="text-sm font-semibold text-amber-700">{{ $message }}</p>
            </div>
        @enderror

        <form method="POST" action="{{ route('auth.fresher-register.verify.submit') }}" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="code" class="text-xs font-bold uppercase tracking-widest text-slate-500">Verification Code</label>
                <input id="code" type="text" name="code" required autofocus autocomplete="one-time-code" placeholder="123456" 
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] text-[#16136a] placeholder:tracking-normal placeholder:text-slate-300 focus:border-[#16136a] focus:ring-[#16136a]" />
                @error('code')
                    <p class="text-xs font-semibold text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center">
                <button type="submit" class="w-full max-w-sm rounded-2xl bg-[#16136a] px-8 py-2.5 text-sm font-bold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#16136a]/30 focus:outline-none focus:ring-2 focus:ring-[#16136a] focus:ring-offset-2">
                    Verify Email
                </button>
            </div>
        </form>

        <div class="text-center space-y-3">
            <p class="text-xs text-slate-500">Didn't receive the code?</p>
            <form method="POST" action="{{ route('auth.fresher-register.resend') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-[#16136a]/20 bg-[#16136a]/5 px-6 py-2 text-xs font-semibold uppercase tracking-widest text-[#16136a] hover:bg-[#16136a]/10 transition">
                    <i class="ri-mail-send-line"></i>
                    Resend Code
                </button>
            </form>

            <a href="{{ route('login') }}" class="block text-xs font-medium text-slate-500 hover:text-[#16136a] hover:underline">
                ← Back to Login
            </a>
        </div>
    </div>
</x-layouts.auth>

