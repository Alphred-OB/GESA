<x-layouts.marketing title="ACSES Developers">
    @include('components.dashboard.skeleton-styles')

    @php
        $hasContactRoute = \Illuminate\Support\Facades\Route::has('marketing.contact');
    @endphp

    <section x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(11,48,25,0.08),_transparent_60%)]"></div>
        <div class="relative mx-auto w-full max-w-6xl px-5 py-16 sm:px-6 lg:px-8">
            <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-16" role="status" aria-live="polite">
                <div class="mx-auto max-w-4xl">
                    <div class="relative overflow-hidden rounded-[32px] border border-[#0b3019]/10 bg-[#0b3019] px-8 py-12 text-center shadow-lg shadow-[#0b3019]/15">
                        <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
                        <div class="absolute -bottom-16 left-6 h-56 w-56 rounded-full bg-white/5 blur-3xl"></div>
                        <div class="relative space-y-4">
                            <div class="mx-auto flex justify-center">
                                <span class="skeleton inline-block h-8 w-44 rounded-full bg-white/25"></span>
                            </div>
                            <div class="space-y-3">
                                <div class="skeleton mx-auto h-10 w-96 rounded-2xl bg-white/25"></div>
                                <div class="skeleton mx-auto h-4 w-72 rounded-2xl bg-white/20"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    @for ($i = 0; $i < 3; $i++)
                        <article class="rounded-3xl border border-[#0b3019]/10 bg-white/85 p-6 shadow-lg shadow-[#0b3019]/10">
                            <div class="h-64 w-full overflow-hidden rounded-2xl border border-[#0b3019]/10 bg-slate-100">
                                <div class="skeleton h-full w-full"></div>
                            </div>
                            <div class="mt-6 space-y-3">
                                <div class="skeleton mx-auto h-5 w-44 rounded-full bg-slate-200"></div>
                                <div class="skeleton mx-auto h-3 w-52 rounded-full bg-slate-100"></div>
                                <div class="skeleton mx-auto h-3 w-60 rounded-full bg-slate-100"></div>
                            </div>
                            <div class="mt-6 flex justify-center">
                                <div class="skeleton h-10 w-44 rounded-full bg-[#0a66c2]/25"></div>
                            </div>
                        </article>
                    @endfor
                </div>

                <div class="relative overflow-hidden rounded-[32px] border border-[#0b3019]/10 bg-white/85 p-8 text-center shadow-lg shadow-[#0b3019]/10">
                    <div class="skeleton mx-auto h-4 w-56 rounded-full bg-[#0b3019]/15"></div>
                    <div class="skeleton mx-auto mt-4 h-3 w-80 rounded-full bg-slate-100"></div>
                    <div class="skeleton mx-auto mt-3 h-3 w-64 rounded-full bg-slate-100"></div>
                    <div class="skeleton mx-auto mt-6 h-10 w-48 rounded-full bg-[#0b3019]/10"></div>
                    @if ($hasContactRoute)
                        <div class="skeleton mx-auto mt-3 h-10 w-48 rounded-full bg-[#0b3019]/10"></div>
                    @endif
                </div>
            </div>

            <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-16">
                <div class="mx-auto max-w-4xl">
                    <div class="relative overflow-hidden rounded-[32px] border border-[#0b3019]/15 bg-gradient-to-br from-[#0b3019] via-[#114127] to-[#0b3019] px-8 py-12 text-center text-white shadow-xl shadow-[#0b3019]/20">
                        <div class="pointer-events-none absolute -inset-20 opacity-40">
                            <div class="h-full w-full animate-spin duration-[48000ms] ease-linear motion-reduce:animate-none">
                                <div class="h-full w-full rounded-[64px] bg-[conic-gradient(from_120deg_at_50%_50%,rgba(255,255,255,0.35),rgba(255,255,255,0)_70%)] blur-3xl"></div>
                            </div>
                        </div>
                        <div class="relative space-y-6">
                            <span class="inline-flex items-center justify-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs font-semibold uppercase tracking-[0.35em] text-emerald-100/90">ACSES Product Studio</span>
                            <h1 class="text-4xl font-semibold sm:text-5xl">Full-stack team shaping every ACSES touchpoint</h1>
                            <p class="mx-auto max-w-2xl text-base text-emerald-50/90 sm:text-lg">We design, build, and refine cohesive experiences so students and administrators feel supported from first sign-in to graduation day.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-[#0b3019]/15 bg-white/95 shadow-xl shadow-[#0b3019]/15 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="relative h-64 w-full overflow-hidden">
                            <img src="{{ asset('assets/images/Kingsley.jpg') }}" alt="Kingsley Adu" class="h-full w-full object-cover" loading="lazy">
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/25 to-transparent"></div>
                        </div>
                        <div class="flex flex-1 flex-col gap-5 px-8 pb-8 pt-8 text-center md:text-left">
                            <div class="space-y-2">
                                <h2 class="text-xl font-semibold text-slate-900">Kingsley Adu</h2>
                                <p class="text-sm uppercase tracking-[0.3em] text-[#0b3019]/70">Project Lead · Full-stack Engineer</p>
                                <p class="text-sm text-slate-600">Guides the ACSES roadmap end-to-end, keeping strategy, delivery, and support moving in sync.</p>
                            </div>
                            <div class="mt-auto flex justify-center border-t border-[#0b3019]/10 pt-6">
                                <a href="https://www.linkedin.com/in/kingsley-aduhene-778538224/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#0b3019] to-[#114127] px-5 py-2 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                                    <i class="ri-linkedin-box-fill text-base" aria-hidden="true"></i>
                                    Connect on LinkedIn
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-[#0b3019]/15 bg-white/95 shadow-xl shadow-[#0b3019]/15 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="relative h-64 w-full overflow-hidden">
                            <img src="{{ asset('assets/images/Alfred.png') }}" alt="Alfred Boakye" class="h-full w-full object-cover" loading="lazy">
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/25 to-transparent"></div>
                        </div>
                        <div class="flex flex-1 flex-col gap-5 px-8 pb-8 pt-8 text-center md:text-left">
                            <div class="space-y-2">
                                <h2 class="text-xl font-semibold text-slate-900">Alfred Boakye</h2>
                                <p class="text-sm uppercase tracking-[0.3em] text-[#0b3019]/70">Lead Portal Engineer · Full-stack</p>
                                <p class="text-sm text-slate-600">Keeps student and admin workflows resilient, translating product ideas into dependable full-stack delivery.</p>
                            </div>
                            <div class="mt-auto flex justify-center border-t border-[#0b3019]/10 pt-6">
                                <a href="https://www.linkedin.com/in/alfredboakye/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#0b3019] to-[#114127] px-5 py-2 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                                    <i class="ri-linkedin-box-fill text-base" aria-hidden="true"></i>
                                    Connect on LinkedIn
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-[#0b3019]/15 bg-white/95 shadow-xl shadow-[#0b3019]/15 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="relative h-64 w-full overflow-hidden">
                            <img src="{{ asset('assets/images/Obed.jpeg') }}" alt="Obed Acquah" class="h-full w-full object-cover object-top" loading="lazy">
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/25 to-transparent"></div>
                        </div>
                        <div class="flex flex-1 flex-col gap-5 px-8 pb-8 pt-8 text-center md:text-left">
                            <div class="space-y-2">
                                <h2 class="text-xl font-semibold text-slate-900">Obed Acquah</h2>
                                <p class="text-sm uppercase tracking-[0.3em] text-[#0b3019]/70">Web Experience Engineer · Full-stack</p>
                                <p class="text-sm text-slate-600">Bridges marketing and in-app journeys, shaping full-stack experiences that feel consistent and quick.</p>
                            </div>
                            <div class="mt-auto flex justify-center border-t border-[#0b3019]/10 pt-6">
                                <a href="https://www.linkedin.com/in/obed-acquah-017687301/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#0b3019] to-[#114127] px-5 py-2 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                                    <i class="ri-linkedin-box-fill text-base" aria-hidden="true"></i>
                                    Connect on LinkedIn
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="relative overflow-hidden rounded-[32px] border border-[#0b3019]/15 bg-gradient-to-br from-[#0b3019] via-[#114127] to-[#0b3019] px-8 py-12 text-center text-white shadow-xl shadow-[#0b3019]/25">
                    <div class="pointer-events-none absolute -inset-20 opacity-40">
                        <div class="h-full w-full animate-spin duration-[48000ms] ease-linear motion-reduce:animate-none">
                            <div class="h-full w-full rounded-[64px] bg-[conic-gradient(from_120deg_at_50%_50%,rgba(255,255,255,0.35),rgba(255,255,255,0)_70%)] blur-3xl"></div>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_65%)]"></div>
                    <div class="relative space-y-4">
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-100/90">Partner with ACSES</p>
                        <h2 class="text-3xl font-semibold sm:text-4xl">Need help shaping student success tools?</h2>
                        <p class="mx-auto max-w-2xl text-base text-emerald-50/90">Our engineers collaborate with campus leaders to co-design digital experiences that feel intentional, accessible, and future-ready.</p>
                        <div class="mt-6 flex flex-wrap justify-center gap-4">
                            <a href="mailto:hello@acses.edu" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#0b3019] shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                                <i class="fa-solid fa-envelope-open-text text-base"></i>
                                Start the conversation
                            </a>
                            @if ($hasContactRoute)
                                <a href="{{ route('marketing.contact') }}" class="inline-flex items-center gap-2 rounded-full border border-white/60 bg-transparent px-5 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/10">
                                    <i class="fa-solid fa-calendar-check text-base"></i>
                                    Schedule a discovery call
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-white"></div>
    </section>
</x-layouts.marketing>
