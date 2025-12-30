<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <section class="hidden lg:block overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-[#16136a] p-8 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)]">
                <div class="space-y-4">
                    <div class="skeleton h-3 w-32 rounded-full bg-white/30"></div>
                    <div class="skeleton h-8 w-2/3 rounded-2xl bg-white/25"></div>
                    <div class="skeleton h-4 w-1/2 rounded-2xl bg-white/20"></div>
                </div>
            </section>

            <div class="grid gap-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 md:grid-cols-4">
                <div class="skeleton h-12 rounded-2xl bg-slate-100 md:col-span-2"></div>
                <div class="skeleton h-12 rounded-2xl bg-slate-100"></div>
                <div class="skeleton h-12 rounded-2xl bg-slate-100"></div>
                <div class="flex items-end gap-2">
                    <div class="skeleton h-12 flex-1 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-12 w-24 rounded-full bg-slate-100"></div>
                </div>
            </div>

            <section class="space-y-4">
                <div class="space-y-2">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-64 rounded-full bg-slate-100"></div>
                </div>
                <div class="space-y-3">
                    @for ($i = 0; $i < 3; $i++)
                        <article class="space-y-3 rounded-3xl border border-slate-200/70 bg-white p-5 shadow">
                            <div class="flex flex-wrap gap-2">
                                <span class="skeleton h-7 w-24 rounded-full bg-slate-100"></span>
                                <span class="skeleton h-7 w-20 rounded-full bg-slate-100"></span>
                            </div>
                            <div class="skeleton h-6 w-3/4 rounded-2xl bg-slate-200"></div>
                            <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                            <div class="flex items-center justify-between">
                                <span class="skeleton h-4 w-24 rounded-full bg-slate-100"></span>
                                <span class="skeleton h-4 w-16 rounded-full bg-slate-100"></span>
                            </div>
                        </article>
                    @endfor
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <section class="relative isolate animate-fade-slide overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-gradient-to-br from-[#16136a] via-[#16136a] to-[#16136a] p-6 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)] sm:p-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-slate-100/80 sm:text-xs">Updates</span>
                        <div class="space-y-3">
                            <h1 class="text-2xl font-bold sm:text-3xl md:text-4xl">Campus pulse</h1>
                            <p class="max-w-2xl text-xs text-slate-100/85 sm:text-sm">
                                Stay informed about academic deadlines, workshops, security advisories, and key GESA portal changes.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <form method="GET" action="{{ route('student.announcements.index') }}" class="grid gap-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                <div class="flex flex-col gap-2 lg:flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Search</span>
                    <input type="search" name="search" value="{{ $filters['search'] }}" placeholder="Keyword, department..." class="h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                </div>

                <div class="flex flex-col gap-2 lg:w-44">
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Type</span>
                    <div class="relative">
                        <select name="type" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            <option value="">All Types</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" @selected($filters['type'] === $value)> {{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2 lg:w-44">
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Priority</span>
                    <div class="relative">
                        <select name="priority" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-white py-3 pl-4 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            <option value="">All Priorities</option>
                            @foreach ($priorities as $value => $label)
                                <option value="{{ $value }}" @selected($filters['priority'] === $value)> {{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end gap-2 sm:col-span-2 lg:w-auto">
                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-bold uppercase tracking-[0.2em] text-white shadow-lg transition hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                        <i class="ri-equalizer-line text-base"></i>
                        Filter
                    </button>
                    <a href="{{ route('student.announcements.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-600 transition hover:bg-slate-50 active:scale-95">Reset</a>
                </div>
            </form>

            <section class="space-y-4">
                <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">All announcements</h2>
                        <p class="text-sm text-slate-500">Sorted by most recent first. Use filters above to narrow down the results.</p>
                    </div>
                    @if ($announcements->hasPages())
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <span>Page {{ $announcements->currentPage() }} of {{ $announcements->lastPage() }}</span>
                            {{ $announcements->onEachSide(1)->links('vendor.pagination.simple-tailwind') }}
                        </div>
                    @endif
                </header>

                @if ($announcements->isEmpty())
                    <article class="rounded-3xl border border-dashed border-slate-300 bg-white/70 p-8 text-center text-sm text-slate-500">
                        <p>No announcements match your filters right now. Try clearing the filters or check back later.</p>
                    </article>
                @else
                    <div class="space-y-5">
                        @foreach ($announcements as $announcement)
                            <article class="flex flex-col rounded-3xl border border-slate-200/80 bg-white p-5 shadow-lg shadow-[#16136a]/5 transition hover:-translate-y-1 hover:shadow-[#16136a]/15">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ $types[$announcement->type] ?? Str::headline($announcement->type) }}</span>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ $priorities[$announcement->priority] ?? Str::headline($announcement->priority) }}</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $announcement->title }}</h3>
                                    <p class="text-sm text-slate-500">{{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content), 140) }}</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                    <span>{{ $announcement->published_at?->format('M j, Y') }}</span>
                                    <span>{{ $announcement->published_at?->format('g:i A') }}</span>
                                </div>
                                <a href="{{ route('student.announcements.show', $announcement) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition hover:underline">
                                    Read more
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.dashboard>
