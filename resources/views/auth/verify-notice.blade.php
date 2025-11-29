@php
    $oldCode = old('code', '');
    $codeDigits = array_slice(array_pad(str_split($oldCode), 6, ''), 0, 6);
@endphp

<x-layouts.auth title="Verify Email" card-width="max-w-lg">
    <div class="space-y-8">
        <div class="space-y-4 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 7h16" />
                    <path d="m4 7 8 6 8-6" />
                </svg>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-slate-900">Verify your email</h1>
                <p class="text-sm text-slate-600">
                    Enter the 6-digit code we sent to <span class="font-medium text-slate-900">{{ $pending['email'] ?? 'your email' }}</span> to activate your account.
                </p>
                @if (session('status'))
                    <p class="rounded-lg border border-[#16136a]/20 bg-[#16136a]/10 px-4 py-2 text-sm text-[#16136a]">
                        {{ session('status') }}
                    </p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('auth.verify.submit') }}" class="space-y-4">
            @csrf
            <div class="space-y-2" data-otp-container data-otp-target="#verification-code">
                <label for="verification-code" class="block text-sm font-medium text-slate-700">Verification code</label>
                <input id="verification-code" name="code" type="hidden" value="{{ $oldCode }}" required>
                <div class="flex justify-center gap-3">
                    @foreach ($codeDigits as $index => $digit)
                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                            data-otp-input
                            class="h-14 w-12 rounded-xl border border-slate-300 bg-white text-center text-lg font-semibold tracking-widest text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30"
                            aria-label="Verification digit {{ $index + 1 }}"
                            value="{{ $digit }}"
                        >
                    @endforeach
                </div>
                @error('code')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-xl bg-[#16136a] py-3 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/25 transition hover:-translate-y-0.5 hover:bg-[#18188a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                Confirm email
            </button>
        </form>

        <div class="space-y-2 text-center text-sm text-slate-600">
            <p>Didn’t get the code? It might take a moment. You can request a new one below.</p>
            <form method="POST" action="{{ route('auth.verify.resend') }}" class="flex items-center justify-center gap-2">
                @csrf
                <button type="submit" class="rounded-xl bg-[#16136a]/10 px-4 py-2 text-sm font-semibold text-[#16136a] transition hover:-translate-y-0.5 hover:bg-[#16136a]/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                    Resend verification code
                </button>
            </form>
        </div>

        <div class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-[#16136a] hover:underline">Back to sign in</a>
        </div>
    </div>
</x-layouts.auth>
