@php($title = 'Forgot password')

<x-layouts.auth :title="$title" card-width="max-w-lg">
    <x-slot:hero>
        <div class="mx-auto w_full max-w-lg text-center text-white">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/10 shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="GESA Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="text-2xl font-semibold">Reset access securely</h1>
            <p class="mt-2 text-sm text-white/80">We’ll send a one-time link to help you pick a new password in minutes.</p>
        </div>
    </x-slot:hero>

    <div class="space-y-8">
        <div class="space-y-3 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify_center rounded-full bg-[#16136a]/10 text-[#16136a]">
                <i class="ri-question-line text-3xl" aria-hidden="true"></i>
            </div>
            <h1 class="text-2xl font-semibold text-slate-900">Forgot your password?</h1>
            <p class="text-sm text-slate-600">
                Enter your email address and we will send you a secure link to reset your password.
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-[#16136a]/20 bg-[#16136a]/10 px-4 py-3 text-sm text-[#16136a]">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ri-mail-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-3 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30"
                        placeholder="you@example.com" />
                </div>
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="flex w-full items_center justify_center space-x-2 rounded-xl bg-[#16136a] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/25 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#18188a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                <i class="ri-mail-send-line text-lg" aria-hidden="true"></i>
                <span>Send reset link</span>
            </button>
        </form>

        <div class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-[#16136a] hover:underline">Back to sign in</a>
        </div>
    </div>

    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#16136a]/20">
                <i class="ri-loader-4-line animate-spin text-2xl text-[#16136a]" aria-hidden="true"></i>
            </div>
            <p class="text-sm font-medium text-slate-700">Sending reset link…</p>
        </div>
    </div>
</x-layouts.auth>
