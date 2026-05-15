@php($title = 'Sign In')

<x-layouts.auth :title="$title">
    <div class="p-8 md:p-12 auth-card-hover">
        <div class="mb-10 text-center stagger-1">
            <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Sign in</h2>
            <p class="mt-2 text-base text-slate-500">Welcome back! Please enter your details.</p>
        </div>

        @if(session('status'))
            <div class="stagger-1 mb-6 rounded-2xl border border-blue-100 bg-blue-50/50 px-4 py-3 text-sm text-blue-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-6" data-auth-form>
            @csrf
            <div class="stagger-2 space-y-2">
                <label for="identifier" class="block text-sm font-semibold text-slate-700">Email or Username</label>
                <div class="group auth-input-group relative">
                    <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                        <i class="ri-user-smile-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus autocomplete="username" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Enter your email or username" />
                </div>
                @error('identifier')
                    <p class="mt-1 text-xs font-medium text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <div class="stagger-3 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 active:scale-95">Forgot password?</a>
                </div>
                <div class="group auth-input-group relative">
                    <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                        <i class="ri-lock-2-line text-lg" aria-hidden="true"></i>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="••••••••" />
                    <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-all duration-300 hover:text-[#16136a] hover:scale-110 active:scale-90" aria-label="Toggle password visibility">
                        <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                        <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs font-medium text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <div class="stagger-4 flex items-center justify-between">
                <label for="remember" class="group flex cursor-pointer items-center space-x-3 text-sm text-slate-600">
                    <div class="relative flex items-center transition-transform duration-300 group-hover:scale-110">
                        <input id="remember" name="remember" type="checkbox" value="1" class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border border-slate-300 bg-white/50 transition-all checked:border-[#16136a] checked:bg-[#16136a] focus:outline-none focus:ring-4 focus:ring-[#16136a]/10">
                        <i class="ri-check-line pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-xs font-semibold text-white opacity-0 transition-opacity peer-checked:opacity-100"></i>
                    </div>
                    <span class="font-medium transition-colors duration-300 group-hover:text-slate-900">Remember me</span>
                </label>
            </div>

            <button type="submit" class="stagger-5 auth-button-press group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-4 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30 disabled:pointer-events-none disabled:opacity-80">
                <!-- Default State -->
                <div class="flex items-center space-x-2 transition-all duration-300 group-[.is-loading]:translate-y-10 group-[.is-loading]:opacity-0" data-auth-submit-default>
                    <span>Sign in</span>
                    <i class="ri-arrow-right-line transition-transform duration-300 group-hover:translate-x-1"></i>
                </div>

                <!-- Loading State -->
                <div class="absolute inset-0 flex translate-y-10 items-center justify-center space-x-3 opacity-0 transition-all duration-300 group-[.is-loading]:translate-y-0 group-[.is-loading]:opacity-100" data-auth-submit-loading>
                    <div class="flex items-center space-x-1.5">
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-white [animation-delay:-0.3s]"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-white [animation-delay:-0.15s]"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-white"></span>
                    </div>
                    <span class="text-xs uppercase tracking-widest">Authenticating</span>
                </div>
            </button>
        </form>

        <div class="stagger-5 mt-10 border-t border-slate-200/50 pt-8 text-center text-sm text-slate-500">
            <p>
                Don’t have an account yet?
                <a href="{{ route('auth.register') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Create an account</a>
            </p>
        </div>
    </div>
</x-layouts.auth>
