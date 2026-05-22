@php($title = 'Reset Password')

<x-layouts.auth :title="$title" card-width="max-w-md">
    <div class="auth-card-hover p-8 md:p-12">
        <div class="mb-10 text-center">
            <div class="stagger-1 mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#16136a]/5 text-[#16136a] shadow-inner mx-auto backdrop-blur-sm">
                <x-heroicon-o-shield-check class="size-8" />
            </div>
            <h2 class="stagger-2 text-3xl font-semibold tracking-tight text-slate-900">Set new password</h2>
            <p class="stagger-3 mt-2 text-base text-slate-500">Choose a strong, secure password for your GESA account.</p>
        </div>

        @if (session('status'))
            <div class="stagger-1 mb-6 rounded-2xl border border-green-100 bg-green-50/50 px-5 py-4 text-sm font-medium text-green-700 backdrop-blur-sm animate-fade-slide-up">
                <div class="flex items-center gap-3">
                    <x-heroicon-s-check-circle class="size-6" />
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6" data-auth-form>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="stagger-4 space-y-2">
                <label for="password" class="block text-sm font-semibold text-slate-700">New Password</label>
                <div class="group auth-input-group relative">
                    <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                        <x-heroicon-o-lock-closed class="size-5" />
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="new-password" 
                        class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" 
                        placeholder="••••••••" />
                    <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-all duration-300 hover:text-[#16136a] hover:scale-110 active:scale-90" aria-label="Toggle password visibility">
                        <x-heroicon-o-eye data-eye class="size-5" />
                        <x-heroicon-o-eye-slash data-eye-off class="hidden size-5" />
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs font-semibold text-red-500 animate-fade-slide-up">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Strength System -->
            <div class="stagger-4 hidden space-y-4 rounded-2xl border border-slate-200/50 bg-slate-50/5 p-5 shadow-inner backdrop-blur-sm transition-all duration-300 hover:bg-white/10" data-password-strength data-password-input="#password">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Security Rating</span>
                    <span data-password-strength-label class="text-xs font-semibold text-slate-400 transition-colors duration-300">Weak</span>
                </div>
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200/50">
                    <div data-password-strength-bar class="h-full w-0 bg-red-500 transition-all duration-500"></div>
                </div>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="length" data-state="fail">
                        <x-heroicon-s-check-circle class="size-4" />
                        <span>Min. 8 Chars</span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="mixed" data-state="fail">
                        <x-heroicon-s-check-circle class="size-4" />
                        <span>Case-sensitive</span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="number" data-state="fail">
                        <x-heroicon-s-check-circle class="size-4" />
                        <span>Numbers</span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="symbol" data-state="fail">
                        <x-heroicon-s-check-circle class="size-4" />
                        <span>Symbols</span>
                    </div>
                </div>
            </div>

            <div class="stagger-4 space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                <div class="group auth-input-group relative">
                    <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                        <x-heroicon-o-lock-closed class="size-5" />
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" 
                        class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" 
                        placeholder="••••••••" />
                    <button type="button" data-password-toggle="#password_confirmation" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-all duration-300 hover:text-[#16136a] hover:scale-110 active:scale-90" aria-label="Toggle password visibility">
                        <x-heroicon-o-eye data-eye class="size-5" />
                        <x-heroicon-o-eye-slash data-eye-off class="hidden size-5" />
                    </button>
                </div>
            </div>

            <button type="submit" class="stagger-5 auth-button-press group relative flex w-full items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-4 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                <div class="flex items-center space-x-2 transition-transform duration-300 group-hover:scale-105">
                    <span>Update Password</span>
                    <x-heroicon-s-check-circle class="transition-transform duration-300 group-hover:scale-110 size-5" />
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
