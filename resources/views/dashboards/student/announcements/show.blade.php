<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-3xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <nav class="flex items-center gap-2 text-sm text-slate-400">
                <span class="skeleton h-4 w-20 rounded-full bg-slate-200"></span>
                <span aria-hidden="true">/</span>
                <span class="skeleton h-4 w-32 rounded-full bg-slate-200"></span>
                <span aria-hidden="true">/</span>
                <span class="skeleton h-4 w-40 rounded-full bg-slate-300"></span>
            </nav>

            <section class="space-y-6 rounded-[28px] border border-[#16136a]/15 bg-white p-8 shadow-xl shadow-[#16136a]/10">
                <header class="space-y-5">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="skeleton h-6 w-28 rounded-full bg-slate-100"></span>
                        <span class="skeleton h-6 w-36 rounded-full bg-slate-100"></span>
                        <span class="skeleton h-6 w-32 rounded-full bg-slate-100"></span>
                    </div>
                    <div class="space-y-3">
                        <div class="skeleton h-8 w-4/5 rounded-2xl bg-slate-200"></div>
                        <div class="skeleton h-4 w-48 rounded-full bg-slate-100"></div>
                    </div>
                </header>

                <div class="space-y-4">
                    <div class="skeleton h-4 w-full rounded-2xl bg-[#16136a]/10"></div>
                    <div class="skeleton h-4 w-5/6 rounded-2xl bg-[#16136a]/10"></div>
                </div>

                <div class="space-y-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                    @endfor
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-6">
                    <div class="skeleton h-4 w-48 rounded-full bg-slate-200"></div>
                    <div class="mt-3 space-y-2">
                        <div class="skeleton h-4 w-3/4 rounded-full bg-slate-100"></div>
                        <div class="skeleton h-10 w-48 rounded-full bg-[#16136a]/20"></div>
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                <div class="space-y-3">
                    @for ($i = 0; $i < 3; $i++)
                        <article class="space-y-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-md shadow-[#16136a]/5">
                            <div class="flex flex-wrap gap-2">
                                <span class="skeleton h-5 w-24 rounded-full bg-slate-100"></span>
                                <span class="skeleton h-5 w-24 rounded-full bg-slate-100"></span>
                            </div>
                            <div class="skeleton h-5 w-3/4 rounded-2xl bg-slate-200"></div>
                            <div class="space-y-2">
                                <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                                <div class="skeleton h-4 w-2/3 rounded-2xl bg-slate-100"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="skeleton h-4 w-32 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-16 rounded-full bg-[#16136a]/20"></div>
                            </div>
                        </article>
                    @endfor
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <nav class="flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('student.dashboard') }}" class="transition hover:text-[#16136a]">Dashboard</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('student.announcements.index') }}" class="transition hover:text-[#16136a]">Announcements</a>
                <span aria-hidden="true">/</span>
                <span class="font-semibold text-[#16136a]">{{ $announcement->title }}</span>
            </nav>

            <article class="space-y-8 rounded-[28px] border border-[#16136a]/15 bg-white p-8 shadow-xl shadow-[#16136a]/10">
                <header class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">{{ $announcement->type_label ?? Str::headline($announcement->type) }}</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">Priority: {{ $announcement->priority_label ?? Str::headline($announcement->priority) }}</span>
                        @if ($announcement->author)
                            <span class="text-slate-400">By {{ $announcement->author->fullname ?? $announcement->author->username }}</span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold text-[#16136a] md:text-4xl">{{ $announcement->title }}</h1>
                        <p class="text-sm text-slate-500">Published {{ $announcement->published_at?->format('M j, Y · g:i A') }}</p>
                    </div>
                </header>

                @if ($announcement->excerpt)
                    <p class="rounded-3xl bg-[#16136a]/5 px-5 py-4 text-sm font-medium text-[#16136a]">{{ $announcement->excerpt }}</p>
                @endif

                <div class="prose prose-slate max-w-none prose-headings:text-[#16136a] prose-a:text-[#16136a] prose-strong:text-[#16136a]">
                    {!! $renderedContent ?: nl2br(e($announcement->content)) !!}
                </div>

                <footer class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50/70 p-6 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="font-semibold text-[#16136a]">Need to revisit later?</p>
                        <p class="text-sm text-slate-500">This announcement will stay in your inbox – you can always access it from the announcements hub.</p>
                    </div>
                    <a href="{{ route('student.announcements.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#16136a]/30 px-5 py-2 text-sm font-semibold text-[#16136a] transition hover:-translate-y-0.5 hover:border-[#16136a]/50">
                        Back to announcements
                    </a>
                </footer>
            </article>

            @if ($related->isNotEmpty())
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold text-[#16136a]">More announcements</h2>
                    <div class="space-y-4">
                        @foreach ($related as $item)
                            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-md shadow-[#16136a]/5 transition hover:-translate-y-1 hover:shadow-[#16136a]/15">
                                <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ Str::headline($item->type) }}</span>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ Str::headline($item->priority) }}</span>
                                </div>
                                <h3 class="mt-3 text-base font-semibold text-slate-900">{{ $item->title }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 140) }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
                                    <span>{{ $item->published_at?->format('M j, Y · g:i A') }}</span>
                                    <a href="{{ route('student.announcements.show', $item) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition hover:underline">
                                        Read
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path d="m9 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
