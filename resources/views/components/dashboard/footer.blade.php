<footer x-data="{ loadingShell: true }" x-init="setTimeout(() => { loadingShell = false }, 600)" class="mt-12 border-t border-slate-200/80 bg-white/95">

    <div x-show="loadingShell" x-transition.opacity.duration.200ms class="mx-auto w-full max-w-6xl px-5 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-200/80 animate-pulse"></div>
                    <div class="h-4 w-40 rounded-full bg-slate-200/80 animate-pulse"></div>
                </div>
                <div class="h-3 w-56 rounded-full bg-slate-200/70 animate-pulse"></div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="h-3 w-20 rounded-full bg-slate-200/80 animate-pulse"></div>
                    @for ($i = 0; $i < 4; $i++)
                        <div class="h-3 w-32 rounded-full bg-slate-200/70 animate-pulse"></div>
                    @endfor
                </div>
                <div class="space-y-3">
                    <div class="h-3 w-20 rounded-full bg-slate-200/80 animate-pulse"></div>
                    @for ($i = 0; $i < 4; $i++)
                        <div class="h-3 w-32 rounded-full bg-slate-200/70 animate-pulse"></div>
                    @endfor
                </div>
            </div>

            <div class="space-y-3">
                <div class="h-3 w-32 rounded-full bg-slate-200/80 animate-pulse"></div>
                <div class="h-12 w-full rounded-2xl bg-slate-200/60 animate-pulse"></div>
                <div class="h-8 w-40 rounded-xl bg-slate-200/70 animate-pulse"></div>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-4 text-xs text-slate-500 sm:flex-row">
            <div class="h-3 w-40 rounded-full bg-slate-200/70 animate-pulse"></div>
            <div class="flex items-center gap-4">
                @for ($i = 0; $i < 3; $i++)
                    <div class="h-3 w-24 rounded-full bg-slate-200/70 animate-pulse"></div>
                @endfor
            </div>
        </div>
    </div>

    <div x-show="!loadingShell" x-cloak class="mx-auto w-full max-w-6xl px-5 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-[#16136a]">
                    <img src="{{ asset('logo.png') }}" alt="GESA Portal" class="h-10 w-10 rounded-xl border border-[#16136a]/20 object-contain" loading="lazy">
                    <span class="text-lg font-semibold">GESA Student Portal</span>
                </div>
                <p class="text-sm text-slate-600">Empowering students with self-service tools for dues, registration, and campus updates.</p>
            </div>

            <div class="grid grid-cols-2 gap-6 text-sm text-slate-600">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Portal</p>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-home-5-line text-base" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.announcements.index') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-megaphone-line text-base" aria-hidden="true"></i>
                                <span>Announcements</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.events.index') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-calendar-event-line text-base" aria-hidden="true"></i>
                                <span>Events</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.resources.index') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-book-open-line text-base" aria-hidden="true"></i>
                                <span>Academic resources</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Support</p>
                    <ul class="space-y-2">
                        <li>
                            <a href="mailto:gesaumat24@gmail.com" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-mail-line text-base" aria-hidden="true"></i>
                                <span>Email support</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.suggestions.index') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-feedback-line text-base" aria-hidden="true"></i>
                                <span>Suggestion box</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.privacy') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-shield-keyhole-line text-base" aria-hidden="true"></i>
                                <span>Privacy policy</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.terms') }}" class="inline-flex items-center gap-3 transition hover:text-[#16136a]">
                                <i class="ri-file-text-line text-base" aria-hidden="true"></i>
                                <span>Terms of service</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="space-y-3 text-sm text-slate-600">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Need assistance?</p>
                <p>Reach the GESA academic services team for questions about registrations, dues, or academic resources.</p>
                <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                           
                            <a href="tel:+233553185125" class="inline-flex items-center gap-2 text-[#16136a] hover:underline">
                                <i class="ri-customer-service-2-line text-base" aria-hidden="true"></i>
                                055 318 5125 - President
                            </a>
                        </div>
                        <p class="text-xs text-slate-500">Weekdays · 08:00–20:00 GMT · Alt: 059 787 0027 - Financial Secretary</p>
                        <div class="flex items-center justify-between text-sm pt-2">
                            
                            <a href="mailto:gesaumat24@gmail.com" class="inline-flex items-center gap-2 text-[#16136a] hover:underline">
                                <i class="ri-mail-send-line text-base" aria-hidden="true"></i>
                                gesaumat24@gmail.com
                            </a>
                        </div>
                        <p class="text-xs text-slate-500">We reply within one business day.</p>
                    </div>
                </div>
                <a href="{{ route('student.suggestions.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#16136a]/20 bg-[#16136a]/5 px-4 py-2 text-sm font-semibold text-[#16136a] transition hover:-translate-y-0.5 hover:border-[#16136a]/40">
                    Submit a support ticket
                </a>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-4 text-xs text-slate-500 sm:flex-row">
            <p>© {{ now()->year }} GESA. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('legal.privacy') }}" class="inline-flex items-center gap-2 transition hover:text-[#16136a]">
                    <i class="ri-lock-2-line text-base" aria-hidden="true"></i>
                    Privacy policy
                </a>
                <a href="{{ route('legal.terms') }}" class="inline-flex items-center gap-2 transition hover:text-[#16136a]">
                    <i class="ri-article-line text-base" aria-hidden="true"></i>
                    Terms of service
                </a>
                <a href="{{ route('legal.cookies') }}" class="inline-flex items-center gap-2 transition hover:text-[#16136a]">
                    <i class="ri-cookie-line text-base" aria-hidden="true"></i>
                    Cookie policy
                </a>
            </div>
        </div>
    </div>
</footer>
