@php($title = 'Admin Dashboard')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-10" role="status" aria-live="polite">
            <section class="hidden lg:block overflow-hidden rounded-[28px] border border-[#16136a]/15 bg-[#16136a] p-10 shadow-[0_24px_60px_-30px_rgba(22,19,106,0.45)]">
                <div class="space-y-4">
                    <div class="skeleton h-3 w-40 rounded-full bg-white/25"></div>
                    <div class="skeleton h-9 w-2/3 rounded-2xl bg-white/30"></div>
                    <div class="skeleton h-4 w-3/4 rounded-2xl bg-white/20"></div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-4">
                @for ($i = 0; $i < 4; $i++)
                    <article class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 space-y-3">
                                <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
                                <div class="skeleton h-7 w-20 rounded-2xl bg-slate-200"></div>
                                <div class="skeleton h-4 w-full rounded-2xl bg-slate-200/80"></div>
                            </div>
                            <div class="skeleton h-12 w-12 rounded-2xl bg-[#16136a]/10"></div>
                        </div>
                    </article>
                @endfor
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <article class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                    <div class="space-y-4">
                        <div class="skeleton h-4 w-48 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-3 w-64 rounded-full bg-slate-100"></div>
                        <div class="grid gap-4 md:grid-cols-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="skeleton h-24 rounded-2xl bg-slate-100"></div>
                            @endfor
                        </div>
                        <div class="skeleton h-24 rounded-3xl bg-slate-50"></div>
                    </div>
                </article>

                <aside class="space-y-6">
                    <article class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="space-y-3">
                            <div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
                            @for ($i = 0; $i < 3; $i++)
                                <div class="skeleton h-12 rounded-2xl bg-slate-100"></div>
                            @endfor
                        </div>
                    </article>

                    <article class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="space-y-3">
                            <div class="skeleton h-4 w-44 rounded-full bg-slate-200"></div>
                            @for ($i = 0; $i < 3; $i++)
                                <div class="skeleton h-10 rounded-2xl bg-slate-100"></div>
                            @endfor
                        </div>
                    </article>
                </aside>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <section class="relative isolate hidden lg:block animate-fade-slide overflow-hidden rounded-[28px] border border-[#16136a]/15 bg-gradient-to-br from-[#16136a] via-[#16136a] to-[#16136a] p-10 text-white shadow-[0_24px_60px_-30px_rgba(22,19,106,0.4)]">
                <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-100/80">GESA Admin Console</p>
                        <h1 class="text-3xl font-semibold md:text-4xl">{{ $hero['greeting'] ?? 'Welcome' }}, {{ $adminName ?? 'Administrator' }}</h1>
                        <p class="max-w-2xl text-sm text-emerald-100/85">{{ $hero['message'] ?? 'Monitor student activity, approvals, and support trends from a single view.' }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/20 bg-white/10 px-6 py-4 text-sm text-emerald-50 shadow-inner">
                        <p class="font-semibold uppercase tracking-[0.25em] text-emerald-200">Last updated</p>
                        <p class="mt-2 leading-6">{{ $hero['lastUpdated'] ?? now()->isoFormat('MMMM D, YYYY [at] h:mm A') }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-4">
                @foreach ($overviewCards as $card)
                    <article class="animate-fade-slide rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">{{ $card['label'] }}</p>
                                <p class="text-2xl font-semibold text-[#16136a]">{{ $card['value'] }}</p>
                                <p class="text-sm text-slate-500">{{ $card['description'] }}</p>
                            </div>
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                                <i class="{{ $card['icon'] ?? 'ri-checkbox-blank-circle-fill' }} text-2xl" aria-hidden="true"></i>
                            </span>
                        </div>

                        @if (!empty($card['link']))
                            <a href="{{ $card['link'] }}" class="mt-6 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/70 transition hover:text-[#16136a]">
                                {{ $card['cta'] ?? 'View details' }}
                                <i class="ri-arrow-right-up-line text-sm" aria-hidden="true"></i>
                            </a>
                        @endif
                    </article>
                @endforeach
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
            <article class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Dues overview</h2>
                        <p class="text-sm text-slate-500">Quick view of current outstanding invoices.</p>
                    </div>
                </header>

                <div class="mt-2 rounded-3xl border border-slate-200/70 bg-slate-50/60 p-6 text-sm text-slate-600">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Outstanding dues overview</p>
                            <p class="mt-2 text-lg font-semibold text-[#16136a]">{{ number_format($dueSummary['count'] ?? 0) }} invoices pending</p>
                            <p class="text-xs text-slate-500">Total balance: <span class="font-semibold text-[#16136a]">GHS {{ number_format((float) ($dueSummary['amount'] ?? 0), 2) }}</span></p>
                        </div>
                        <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/20 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] transition hover:-translate-y-0.5 hover:border-[#16136a]/40">Review dues log</a>
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                <article class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <header class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-[#16136a]">Upcoming events</h2>
                        <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/60 hover:text-[#16136a]">View all</a>
                    </header>
                    <ul class="mt-4 space-y-4">
                        @forelse ($upcomingEvents as $event)
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                                <p class="text-sm font-semibold text-slate-800">{{ $event['title'] }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.25em] text-[#16136a]/70">{{ $event['schedule'] ?? 'TBA' }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                    <span class="inline-flex items-center rounded-full bg-[#16136a]/10 px-3 py-1 font-semibold uppercase tracking-[0.2em] text-[#16136a]">{{ $event['category'] }}</span>
                                    @if (!empty($event['location']))
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ri-map-pin-2-fill text-base" aria-hidden="true"></i>
                                            {{ $event['location'] }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-6 text-center text-sm text-slate-500">No events scheduled yet. Create your first event to populate this list.</li>
                        @endforelse
                    </ul>
                </article>

                <article class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <header class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-[#16136a]">Latest suggestions</h2>
                        <a href="{{ route('admin.resources.index') }}" class="text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/60 hover:text-[#16136a]">View resources</a>
                    </header>
                    <ul class="mt-4 space-y-4 text-sm text-slate-600">
                        @forelse ($recentSuggestions as $suggestion)
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-slate-800">{{ $suggestion['subject'] }}</p>
                                    <span class="text-xs text-slate-400">{{ $suggestion['submitted_at'] }}</span>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ $suggestion['category'] }}</span>
                                    <span class="rounded-full bg-[#16136a]/10 px-3 py-1 font-semibold text-[#16136a]">{{ $suggestion['status'] }}</span>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Submitted by {{ $suggestion['owner'] }}</p>
                            </li>
                        @empty
                            <li class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-6 text-center text-sm text-slate-500">No recent suggestions. Encourage students to share feedback.</li>
                        @endforelse
                    </ul>
                </article>
            </aside>
        </section>
    </div>
</x-layouts.admin>
