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

            {{-- Event List --}}
            <section class="space-y-6" x-data="{ eventViewMode: localStorage.getItem('eventViewMode') || 'table' }" x-init="$watch('eventViewMode', val => localStorage.setItem('eventViewMode', val))">
                <form method="GET" class="flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-sm border border-slate-200/60 lg:flex-row lg:items-center lg:justify-between">
                    {{-- Search & Filters --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative">
                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="h-10 w-full min-w-[200px] rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                        </div>
                        <select name="status" onchange="this.form.submit()" class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                            <option value="">All Statuses</option>
                            <option value="upcoming" @selected(request('status') === 'upcoming')>Upcoming</option>
                            <option value="live" @selected(request('status') === 'live')>Live Now</option>
                            <option value="past" @selected(request('status') === 'past')>Past</option>
                        </select>
                        <button type="submit" class="hidden"></button>
                    </div>

                    {{-- View Toggle & Display --}}
                    <div class="flex items-center gap-3">
                        <div class="flex rounded-xl bg-slate-100 p-1">
                            <button type="button" @click="eventViewMode = 'table'" :class="{ 'bg-white shadow-sm text-[#16136a]': eventViewMode === 'table', 'text-slate-500 hover:text-slate-700': eventViewMode !== 'table' }" class="flex h-8 w-10 items-center justify-center rounded-lg transition-all">
                                <i class="ri-list-check"></i>
                            </button>
                            <button type="button" @click="eventViewMode = 'grid'" :class="{ 'bg-white shadow-sm text-[#16136a]': eventViewMode === 'grid', 'text-slate-500 hover:text-slate-700': eventViewMode !== 'grid' }" class="flex h-8 w-10 items-center justify-center rounded-lg transition-all">
                                <i class="ri-grid-fill"></i>
                            </button>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <select name="per_page" onchange="this.form.submit()" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-xs font-semibold text-slate-600 outline-none focus:ring-2 focus:ring-[#16136a]/10">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div x-cloak>
                    {{-- Table View --}}
                    <div x-show="eventViewMode === 'table'" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-[10px] uppercase tracking-widest text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Event</th>
                                        <th class="px-6 py-4 font-semibold">Schedule</th>
                                        <th class="px-6 py-4 font-semibold">Venue</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 text-right font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($events as $event)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative flex h-10 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">
                                                        @if ($event->banner_url)
                                                            <img src="{{ $event->banner_url }}" alt="" class="h-full w-full object-cover">
                                                        @else
                                                            <i class="ri-calendar-event-line text-slate-400"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-slate-900 line-clamp-1 max-w-[200px]">{{ $event->title }}</p>
                                                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ Str::headline($event->category ?? 'General') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-slate-900">{{ $event->start_at->format('M j, Y') }}</p>
                                                <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $event->start_at->format('g:i A') }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-1.5 text-slate-600">
                                                    <i class="ri-map-pin-line text-slate-400"></i>
                                                    <span class="line-clamp-1 max-w-[150px]">{{ $event->location ?? 'TBA' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($event->start_at <= $now && (!$event->end_at || $event->end_at > $now))
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-600">
                                                        <span class="relative flex h-1.5 w-1.5">
                                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                        </span>
                                                        Live
                                                    </span>
                                                @elseif($event->start_at > $now)
                                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-blue-600">
                                                        Upcoming
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                                        Past
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if($event->cta_url)
                                                        <a href="{{ $event->cta_url }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="External Link">
                                                            <i class="ri-external-link-line"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.events.edit', $event) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event records?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                                No events found matching your criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Grid View --}}
                    <div x-show="eventViewMode === 'grid'" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($events as $event)
                            <article class="group flex flex-col rounded-2xl border border-slate-200/60 bg-white p-5 transition-all hover:border-slate-300 hover:shadow-md">
                                <div class="flex gap-4">
                                    <div class="relative aspect-square w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                        @if ($event->banner_url)
                                            <img src="{{ $event->banner_url }}" alt="" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-slate-50 text-slate-300">
                                                <i class="ri-calendar-event-line text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                                {{ Str::headline($event->category ?? 'General') }}
                                            </span>
                                            @if($event->start_at <= $now && (!$event->end_at || $event->end_at > $now))
                                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_0_4px_rgba(16,185,129,0.1)]"></span>
                                            @endif
                                        </div>
                                        <h3 class="mt-1 font-semibold text-slate-900 line-clamp-2 leading-snug group-hover:text-[#16136a] transition-colors">{{ $event->title }}</h3>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3">
                                    <div>
                                        <p class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Date</p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-700">{{ $event->start_at->format('M j') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Time</p>
                                        <p class="mt-0.5 text-xs font-medium text-slate-700">{{ $event->start_at->format('g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-50">
                                    <div class="flex gap-1">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-amber-500 transition-colors">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event records?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-rose-500 transition-colors">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($event->cta_url)
                                        <a href="{{ $event->cta_url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline">
                                            Action Link &rarr;
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                    <i class="ri-search-line text-3xl"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-slate-900">No events found</h3>
                                <p class="mt-1 text-xs text-slate-500">Try adjusting your filters.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                    <div class="mt-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-200/60">
                        {{ $events->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
