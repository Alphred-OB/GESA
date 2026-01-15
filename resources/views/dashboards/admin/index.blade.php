@php($title = 'Admin Dashboard')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <div class="space-y-10">
            <section class="relative isolate animate-fade-slide overflow-hidden rounded-[28px] border border-[#16136a]/15 bg-gradient-to-br from-[#16136a] via-[#16136a] to-[#16136a] p-6 text-white shadow-[0_24px_60px_-30px_rgba(22,19,106,0.4)] sm:p-10">
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.35em] text-emerald-100/80 sm:text-xs">GESA Admin Console</p>
                        <h1 class="text-2xl font-semibold sm:text-3xl md:text-4xl">{{ $hero['greeting'] ?? 'Welcome' }}, {{ $adminName ?? 'Administrator' }}</h1>
                        <p class="max-w-2xl text-xs text-emerald-100/85 sm:text-sm">{{ $hero['message'] ?? 'Monitor student activity, approvals, and support trends from a single view.' }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-xs text-emerald-50 shadow-inner sm:rounded-3xl sm:px-6 sm:py-4 sm:text-sm">
                        <p class="font-semibold uppercase tracking-[0.25em] text-emerald-200">Last updated</p>
                        <p class="mt-1 leading-relaxed sm:mt-2 sm:leading-6">{{ $hero['lastUpdated'] ?? now()->isoFormat('MMMM D, YYYY [at] h:mm A') }}</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                @foreach ($overviewCards as $card)
                    <article class="animate-fade-slide rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">{{ $card['label'] }}</p>
                                <p class="text-2xl font-semibold text-[#16136a]">{{ $card['value'] }}</p>
                                <p class="text-sm text-slate-500">{{ $card['description'] }}</p>
                            </div>
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
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

            <section class="grid gap-6 lg:grid-cols-2">
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
