@php($title = 'Account Created Successfully')

<x-layouts.auth :title="$title" card-width="max-w-2xl">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg">
                <x-heroicon-o-check-circle class="text-4xl text-green-600 size-5" />
            </div>
            <h1 class="mt-8 text-3xl font-semibold tracking-tight text-white lg:text-4xl">Welcome to GESA!</h1>
            <p class="mt-4 max-w-md text-base text-white/80 mx-auto">
                Your account has been created successfully. You can now log in!
            </p>
        </div>
    </x-slot:hero>

    <div class="auth-card-hover mx-auto w-full max-w-3xl rounded-3xl bg-white/95 p-10 shadow-xl ring-1 ring-black/5 backdrop-blur">
        <div class="space-y-8">
            <div class="stagger-1 rounded-2xl border border-blue-200 bg-blue-50/50 p-8 backdrop-blur-sm transition-all duration-300 hover:bg-blue-50">
                <div class="flex gap-6">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-inner">
                        <x-heroicon-o-clock class="size-8" />
                    </div>
                    <div class="space-y-3">
                        <h2 class="text-xl font-semibold text-blue-900">Registration Submitted</h2>
                        <p class="text-sm text-blue-800 leading-relaxed font-medium">
                            Your application is now being reviewed by the GESA administration. This process usually takes <span class="font-semibold underline">24-48 hours</span>.
                        </p>
                        <ul class="space-y-3 text-sm text-blue-800 list-none pt-2">
                            <li class="flex gap-3 items-start transition-transform duration-300 hover:translate-x-1">
                                <x-heroicon-s-check-circle class="text-blue-600 mt-0.5 size-5" />
                                <span>We have received your documents and information.</span>
                            </li>
                            <li class="flex gap-3 items-start transition-transform duration-300 hover:translate-x-1">
                                <x-heroicon-o-paper-airplane class="text-blue-600 mt-0.5 size-5" />
                                <span>You will receive an <strong>email notification</strong> once your account is approved.</span>
                            </li>
                            <li class="flex gap-3 items-start transition-transform duration-300 hover:translate-x-1">
                                <x-heroicon-o-shield-check class="text-blue-600 mt-0.5 size-5" />
                                <span>Your account is currently in <strong>pending status</strong> for security verification.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="stagger-2 rounded-2xl border border-slate-200 bg-slate-50/30 p-8 transition-all duration-300 hover:bg-white/50">
                <div class="flex gap-6">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 shadow-inner">
                        <x-heroicon-o-light-bulb class="size-8" />
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-slate-900">What's Next?</h3>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="p-4 rounded-xl bg-white/50 border border-slate-100 transition-all hover:shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Step 1</p>
                                <p class="text-sm font-medium text-slate-700">Check your email for the approval confirmation.</p>
                            </div>
                            <div class="p-4 rounded-xl bg-white/50 border border-slate-100 transition-all hover:shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Step 2</p>
                                <p class="text-sm font-medium text-slate-700">Once approved, log in with your credentials.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stagger-3 flex justify-center pt-4">
                <a href="{{ route('login') }}" class="auth-button-press group relative flex items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-8 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                    <div class="flex items-center space-x-3 transition-transform duration-300 group-hover:scale-105">
                        <x-heroicon-o-arrow-left class="size-5" />
                        <span>Return to Login</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.auth>
