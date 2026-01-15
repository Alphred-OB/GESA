@php($title = 'Sign In')

<x-layouts.auth :title="$title">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="GESA Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="text-2xl font-semibold text-white">Welcome back</h1>
            <p class="mt-2 text-sm text-white/80">Enter your credentials to continue</p>
        </div>
    </x-slot:hero>

    <div class="rounded-3xl bg-white/90 p-8 shadow-xl ring-1 ring-black/5 backdrop-blur">
        <div class="mb-8 text-center lg:text-left">
            <h2 class="text-2xl font-semibold text-slate-900">Login to your account</h2>
            <p class="mt-2 text-sm text-slate-600">Enter your credentials to continue</p>
        </div>

        @if(session('status'))
            <div class="mb-6 rounded-xl border border-[#16136a]/30 bg-[#16136a]/10 px-4 py-3 text-sm text-[#16136a]">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="space-y-2">
                <label for="identifier" class="block text-sm font-medium text-slate-700">Email, Username, or Reference Number</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ri-user-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus autocomplete="username" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-3 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Email, username, or reference number" />
                </div>
                @error('identifier')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#16136a] transition hover:underline">Forgot password?</a>
                </div>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="ri-lock-password-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Enter your password" />
                    <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                        <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                        <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center space-x-2 text-sm text-slate-600">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]">
                    <span>Remember me</span>
                </label>
                <span aria-hidden="true"></span>
            </div>

            <button type="submit" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-[#16136a] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/30 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#18188a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                <i class="ri-login-circle-line text-lg" aria-hidden="true"></i>
                <span>Login</span>
            </button>
        </form>

        <div class="mt-6 space-y-3 text-center text-sm text-slate-600">
            <p>
                Don’t have an account?
                <a href="{{ route('auth.register') }}" class="font-semibold text-[#16136a] hover:underline">Sign up</a>
            </p>
            <p>
                Need help?
                <a href="mailto:gesaumat24@gmail.com" class="font-semibold text-[#16136a] hover:underline">Contact support</a>
            </p>
        </div>
    </div>

    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#16136a]/20">
                <i class="ri-loader-4-line animate-spin text-2xl text-[#16136a]" aria-hidden="true"></i>
            </div>
            <p class="text-sm font-medium text-slate-700">Signing you in…</p>
        </div>
    </div>
</x-layouts.auth>
