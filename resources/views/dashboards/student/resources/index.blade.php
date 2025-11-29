<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <section class="hidden md:block overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-[#16136a] p-8 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)]">
                <div class="space-y-6 md:flex md:items-center md:justify-between md:gap-10">
                    <div class="space-y-4">
                        <div class="skeleton h-3 w-32 rounded-full bg-white/25"></div>
                        <div class="skeleton h-8 w-72 rounded-2xl bg-white/20"></div>
                        <div class="skeleton h-4 w-80 rounded-2xl bg-white/15"></div>
                    </div>
                    <div class="mt-6 w-full max-w-xs rounded-3xl border border-white/20 bg-white/10 p-6 text-white shadow-inner md:mt-0">
                        <div class="skeleton h-3 w-36 rounded-full bg-white/30"></div>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="skeleton h-3 w-24 rounded-full bg-white/20"></span>
                                <span class="skeleton h-4 w-12 rounded-full bg-white/30"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid gap-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:grid-cols-[1fr_auto]">
                <div class="space-y-2">
                    <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-12 rounded-2xl bg-slate-100"></div>
                </div>
                <div class="flex items-end gap-3">
                    <div class="skeleton h-11 w-32 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-11 w-24 rounded-full bg-slate-100"></div>
                </div>
            </div>

            <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @for ($i = 0; $i < 3; $i++)
                    <article class="flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                        <div class="space-y-3">
                            <div class="skeleton h-7 w-28 rounded-full bg-[#16136a]/10"></div>
                            <div class="skeleton h-5 w-3/4 rounded-2xl bg-slate-200"></div>
                            <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                        </div>
                        <div class="mt-5 space-y-2">
                            <div class="skeleton h-4 w-2/3 rounded-full bg-slate-100"></div>
                            <div class="skeleton h-4 w-32 rounded-full bg-slate-100"></div>
                        </div>
                    </article>
                @endfor
            </section>

            <section class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                <div class="space-y-3">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-72 rounded-full bg-slate-100"></div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <div class="skeleton h-4 w-28 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                            <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
                        </div>
                    @endfor
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <section class="relative isolate hidden md:block animate-fade-slide overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-gradient-to-br from-[#16136a] via-[#16136a] to-[#16136a] p-8 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)]">
                <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-100">Academics</span>
                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold md:text-4xl">Academic resources</h1>
                            <p class="max-w-2xl text-sm text-emerald-100/85">
                                Explore lecture handouts, past questions, recordings, and course links curated by the academic office. Use the search tool to quickly locate what you need.
                            </p>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-white/15 bg-white/10 px-6 py-5 text-sm shadow-inner">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-200">Resource stats</p>
                        <dl class="mt-3 space-y-2 text-xs text-emerald-100/80">
                            <div class="flex items-center justify-between gap-4">
                                <dt>Available resources</dt>
                                <dd class="text-sm font-semibold text-white">{{ $totalResources }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            <form method="GET" action="{{ route('student.resources.index') }}" class="grid gap-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:grid-cols-[1fr_auto]">
                <div class="flex flex-col gap-2">
                    <label for="search" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search library</label>
                    <div class="relative">
                        <input id="search" name="search" type="search" value="{{ $search }}" placeholder="e.g. algorithms handout, level 200" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pl-10 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M10 18a8 8 0 1 1 8-8" />
                            <path d="m22 22-4.35-4.35" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:bg-[#18188a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                        Apply filters
                    </button>
                    <a href="{{ route('student.resources.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Reset</a>
                </div>
            </form>

            @if ($resources->isEmpty())
                <section class="rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center text-sm text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="m9 5 7 7-7 7" />
                        <path d="M4 4h7a3 3 0 0 1 3 3v13" />
                    </svg>
                    <p class="mt-4 font-semibold text-slate-600">No academic resources match your filters yet.</p>
                    <p class="mt-2">Try a different keyword or browse another category.</p>
                </section>
            @else
                <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($resources as $resource)
                        <article class="flex h-full flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5 transition hover:-translate-y-1 hover:shadow-[#16136a]/15">
                            <div class="space-y-3">
                                <span class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/80">
                                    <i class="fa-solid {{ $resource['badge_icon'] ?? 'fa-book-open-reader' }} text-[#16136a]"></i>
                                    {{ $resource['badge_label'] ?? 'Resource' }}
                                </span>
                                <h2 class="text-lg font-semibold text-slate-900">{{ $resource['title'] }}</h2>
                                <p class="text-sm text-slate-600">{{ $resource['description'] }}</p>
                            </div>
                            @php
                                $target = ($resource['is_file'] ?? false) || \Illuminate\Support\Str::startsWith($resource['cta_url'], ['http://', 'https://']) ? '_blank' : '_self';
                            @endphp
                            <a href="{{ $resource['cta_url'] }}" target="{{ $target }}" rel="noopener" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition hover:underline">
                                {{ $resource['cta_label'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                        </article>
                    @endforeach
                </section>
            @endif

            <section class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                <h2 class="text-lg font-semibold text-[#16136a]">Need academic assistance?</h2>
                <p class="mt-2 text-sm text-slate-600">Reach out to your department or academic services for questions about course materials.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">Faculty office</p>
                        <p class="mt-1">Room 204, Engineering Block<br><span class="text-xs text-slate-500">Weekdays · 08:00–16:00</span></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">Academic support inbox</p>
                        <p class="mt-1"><a href="mailto:gesaumat24@gmail.com" class="text-[#16136a] hover:underline">gesaumat24@gmail.com</a></p>
                        <p class="text-xs text-slate-500">Expect a response within one business day.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">Peer learning hub</p>
                        <p class="mt-1">Join study groups and tutoring sessions hosted by senior mentors.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layouts.dashboard>
