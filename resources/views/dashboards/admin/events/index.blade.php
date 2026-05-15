@php
    $title = $title ?? 'Event Schedule';
    $now = now();
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header Section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Event Schedule</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Orchestrate and manage campus activities</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Scheduled Events</span>
                        <span class="text-2xl font-semibold text-[#16136a]">{{ number_format($events->total()) }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 mx-2"></div>
                    <a href="{{ route('admin.events.create') }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <i class="ri-add-line text-lg transition-transform group-hover:rotate-90"></i>
                        New Event
                    </a>
                </div>
            </header>

            {{-- Summary Cards (Bento Style) --}}
            @php
                $items = collect($events->items());
                $upcomingCount = $items->filter(fn($e) => $e->start_at > $now)->count();
                $liveCount = $items->filter(fn($e) => $e->start_at <= $now && ($e->end_at > $now || !$e->end_at))->count();
                $pastCount = $items->filter(fn($e) => $e->end_at && $e->end_at <= $now)->count();
            @endphp
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Active Engagement</p>
                        <p class="mt-4 text-4xl font-semibold text-emerald-400">{{ $liveCount }} <span class="text-lg text-white/40 font-semibold uppercase tracking-widest">Live Now</span></p>
                        <p class="mt-2 text-xs font-semibold text-white/40 italic">Events currently in progress</p>
                    </div>
                    <i class="ri-broadcast-line absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12"></i>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Future Pipeline</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $upcomingCount }} <span class="text-lg text-slate-300 uppercase tracking-widest">Upcoming</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Scheduled for later dates</p>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Past Activities</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $pastCount }} <span class="text-lg text-slate-300 uppercase tracking-widest">Archived</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Completed event records</p>
                </div>
            </div>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Event Grid --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Scheduled Events</h2>
                    <form method="GET" class="flex items-center gap-3">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Display</label>
                        <select name="per_page" onchange="this.form.submit()" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-xs font-semibold text-slate-600 outline-none focus:ring-2 focus:ring-[#16136a]/10">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @if($option === $currentPerPage) selected @endif>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid gap-8 md:grid-cols-2">
                    @forelse ($events as $event)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white p-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60">
                            <div class="relative z-10 flex flex-col h-full">
                                <header class="flex items-start justify-between gap-4">
                                    <div class="relative aspect-video w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100 shadow-lg group-hover:scale-105 transition-transform">
                                        @if ($event->banner_url)
                                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#16136a]/5 to-[#16136a]/10 text-[#16136a]">
                                                <i class="ri-calendar-event-line text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-2 text-right">
                                        <span @class([
                                            'inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-[10px] font-semibold uppercase tracking-widest',
                                            'bg-emerald-50 text-emerald-600' => ($event->start_at <= $now && (!$event->end_at || $event->end_at > $now)),
                                            'bg-blue-50 text-blue-600' => ($event->start_at > $now),
                                            'bg-slate-100 text-slate-500' => ($event->end_at && $event->end_at <= $now),
                                        ])>
                                            @if($event->start_at <= $now && (!$event->end_at || $event->end_at > $now))
                                                <span class="relative flex h-2 w-2">
                                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                                </span>
                                                Live Now
                                            @elseif($event->start_at > $now)
                                                Upcoming
                                            @else
                                                Past
                                            @endif
                                        </span>
                                        <span class="text-[10px] font-semibold text-slate-300 uppercase tracking-widest">
                                            {{ Str::headline($event->category ?? 'General') }}
                                        </span>
                                    </div>
                                </header>

                                <div class="mt-6 flex-1">
                                    <h3 class="text-xl font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $event->title }}</h3>
                                    <p class="mt-2 text-sm font-semibold text-slate-400 line-clamp-2 leading-relaxed">
                                        {{ $event->description ? strip_tags($event->description) : 'No description provided for this event.' }}
                                    </p>
                                    
                                    <div class="mt-6 grid grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-300">Schedule</p>
                                            <p class="text-xs font-semibold text-[#16136a]">
                                                {{ $event->start_at->format('M j, Y') }}
                                                <span class="block text-[10px] text-slate-400 font-semibold uppercase">{{ $event->start_at->format('g:i A') }}</span>
                                            </p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-300">Venue</p>
                                            <p class="text-xs font-semibold text-slate-700 line-clamp-2">
                                                {{ $event->location ?? 'TBA' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <footer class="mt-8 flex items-center justify-between gap-4 pt-6 border-t border-slate-50">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-[#16136a] hover:text-white">
                                            <i class="ri-edit-line text-lg"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event records?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-400 transition-all hover:bg-rose-500 hover:text-white">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($event->cta_url)
                                        <a href="{{ $event->cta_url }}" target="_blank" class="flex h-10 items-center gap-2 rounded-xl bg-blue-500 px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-blue-500/20 transition-transform hover:-translate-y-0.5">
                                            <i class="ri-external-link-line"></i>
                                            Action Link
                                        </a>
                                    @endif
                                </footer>
                            </div>
                        </article>
                    @empty
                        <div class="md:col-span-2 rounded-[2.5rem] border border-dashed border-slate-300 p-20 text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                <i class="ri-calendar-line text-5xl"></i>
                            </div>
                            <h3 class="mt-6 text-lg font-semibold text-slate-900">No Events Scheduled</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-400">Start populating the campus calendar with upcoming activities.</p>
                            <a href="{{ route('admin.events.create') }}" class="mt-8 inline-flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-8 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20">
                                <i class="ri-add-line text-lg"></i>
                                Add First Event
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                    <div class="mt-8 rounded-[2rem] bg-slate-50 p-4 text-center">
                        {{ $events->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
