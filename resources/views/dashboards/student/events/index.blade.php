<x-layouts.dashboard :title="$title">
    <div x-data="{ view: $persist('grid') }" class="mx-auto w-full max-w-full px-8 py-10 space-y-10">
        <div class="space-y-10">
            {{-- Simplified Bento Hero --}}
            <section class="relative isolate overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur-md">
                                <i class="ri-calendar-event-line"></i> Campus Life
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-tight text-white">Upcoming Events</h1>
                            <p class="text-sm font-medium text-white/70 leading-relaxed max-w-xl">
                                Stay on top of GESA seminars, workshops, and social meetups. Plan your week ahead.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Subtle background depth -->
                <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-white/5 blur-3xl"></div>
                <i class="ri-calendar-event-line absolute -right-10 -bottom-10 text-[240px] text-white/[0.03] -rotate-12 select-none pointer-events-none"></i>
            </section>

            <div class="grid gap-10 lg:grid-cols-4">
                {{-- Main Events Feed --}}
                <div class="space-y-10 lg:col-span-3">
                    <form method="GET" action="{{ route('student.events.index') }}" class="grid gap-4 rounded-xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                        <div class="flex flex-col gap-2 lg:flex-1">
                            <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400 ml-1">Search Events</span>
                            <div class="relative">
                                <input type="search" name="search" value="{{ $search }}" placeholder="e.g. robotics workshop..." class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40 transition-all">
                            </div>
                        </div>

                        @if ($categories->isNotEmpty())
                            <div class="flex flex-col gap-2 lg:w-56">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400 ml-1">Category</span>
                                <div class="relative">
                                    <select name="category" class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-4 pr-12 text-sm font-semibold text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                                        <option value="">All Events</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['slug'] }}" @selected($activeCategory === $category['slug'])> {{ $category['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <i class="ri-arrow-down-s-line pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-end gap-2 sm:col-span-2 lg:w-auto">
                            <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#16136a] px-6 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg transition hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                                <span>Filter Feed</span>
                            </button>
                            <a href="{{ route('student.events.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-[10px] font-semibold uppercase tracking-widest text-slate-600 transition hover:bg-slate-50 active:scale-95">Reset</a>
                        </div>
                    </form>

                    <section class="space-y-6">
                        <header class="flex flex-col gap-4 border-b border-[#16136a]/10 pb-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-xl font-semibold tracking-tight text-[#16136a]">Scheduled Activities</h2>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Chronological timeline</p>
                            </div>
                            
                            <div class="flex items-center gap-6">
                                {{-- View Switcher --}}
                                <div class="flex items-center gap-1 rounded-xl bg-slate-100 p-1 ring-1 ring-slate-200">
                                    <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-white text-[#16136a] shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                                        <i class="ri-grid-fill text-lg"></i>
                                    </button>
                                    <button @click="view = 'table'" :class="view === 'table' ? 'bg-white text-[#16136a] shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="flex h-9 w-9 items-center justify-center rounded-xl transition-all">
                                        <i class="ri-table-line text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </header>

                        @if ($events->isEmpty())
                            <article class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 py-20 text-center">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm ring-1 ring-slate-100">
                                    <i class="ri-calendar-event-line text-3xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-[#16136a]">No events scheduled.</p>
                                <p class="mt-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Check back later or adjust filters</p>
                            </article>
                        @else
                            {{-- Grid View --}}
                            <div x-show="view === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid gap-6 sm:grid-cols-2">
                                @foreach ($events as $event)
                                    <article class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-[#16136a]/5">
                                        <div class="relative aspect-[16/9] w-full bg-slate-100">
                                            @if (!empty($event['banner_url']))
                                                <img src="{{ $event['banner_url'] }}" alt="{{ $event['banner_alt'] ?? ($event['title'] . ' banner') }}" loading="lazy" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#16136a]/5 via-slate-100 to-[#16136a]/5">
                                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-[#16136a]/10 text-[#16136a]">
                                                        <i class="ri-image-line text-2xl"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex flex-1 flex-col p-8">
                                            <div class="space-y-4">
                                                <div class="flex items-start gap-4">
                                                    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-slate-50 text-[#16136a] shadow-sm ring-1 ring-slate-100">
                                                        <span class="text-[10px] font-semibold uppercase tracking-widest">{{ optional($event['start_at'])->format('M') }}</span>
                                                        <span class="text-xl font-semibold leading-none">{{ optional($event['start_at'])->format('d') }}</span>
                                                    </div>
                                                    <div class="pt-1">
                                                        <span class="rounded-xl bg-slate-50 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">{{ $event['category'] }}</span>
                                                        <h3 class="mt-3 text-lg font-semibold tracking-tight text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-2">
                                                            <a href="{{ route('student.events.show', $event['id']) }}" class="after:absolute after:inset-0">{{ $event['title'] }}</a>
                                                        </h3>
                                                    </div>
                                                </div>
                                                <p class="text-sm font-medium leading-relaxed text-slate-500 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($event['description'] ?? ''), 160) }}</p>
                                            </div>

                                            <div class="mt-8 flex flex-col gap-2 pt-6 border-t border-slate-50">
                                                <div class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                                    <i class="ri-time-line text-[#16136a]"></i>
                                                    <span>{{ optional($event['start_at'])->format('g:i A') }} @if ($event['end_at']) - {{ optional($event['end_at'])->format('g:i A') }} @endif</span>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            {{-- Table View --}}
                            <div x-show="view === 'table'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/50 border-b border-slate-100">
                                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 w-24">Date</th>
                                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Event Details</th>
                                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 hidden lg:table-cell">Category</th>
                                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Time</th>
                                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach ($events as $event)
                                            <tr 
                                                @click="window.location.href = '{{ route('student.events.show', $event['id']) }}'"
                                                class="group cursor-pointer hover:bg-slate-50/30 transition-colors"
                                            >
                                                <td class="px-8 py-6">
                                                    <div class="flex h-12 w-12 flex-col items-center justify-center rounded-xl bg-slate-50 text-[#16136a] shadow-sm ring-1 ring-slate-100">
                                                        <span class="text-[9px] font-semibold uppercase tracking-widest">{{ optional($event['start_at'])->format('M') }}</span>
                                                        <span class="text-lg font-semibold leading-none">{{ optional($event['start_at'])->format('d') }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div class="space-y-1">
                                                        <h4 class="text-sm font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $event['title'] }}</h4>
                                                        <p class="text-xs font-medium text-slate-400 line-clamp-1">{{ \Illuminate\Support\Str::limit(strip_tags($event['description'] ?? ''), 80) }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6 hidden lg:table-cell">
                                                    <span class="rounded-xl bg-slate-50 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">{{ $event['category'] }}</span>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ optional($event['start_at'])->format('g:i A') }}</span>
                                                </td>
                                                <td class="px-8 py-6 text-right">
                                                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-[#16136a] ring-1 ring-slate-100 transition-all group-hover:bg-[#16136a] group-hover:text-white group-hover:shadow-lg group-hover:shadow-[#16136a]/20">
                                                        <i class="ri-arrow-right-line text-lg"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                </div>

                {{-- Sidebar: Visual Calendar --}}
                <aside class="space-y-6">
                    @php
                        $today = \Carbon\Carbon::now();
                        $startOfMonth = $today->copy()->startOfMonth();
                        $daysInMonth = $today->daysInMonth;
                        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday
                        
                        $eventDays = [];
                        foreach($events as $e) {
                            if ($e['start_at'] && $e['start_at']->isSameMonth($today)) {
                                $eventDays[] = $e['start_at']->day;
                            }
                        }
                        $eventDays = array_unique($eventDays);
                    @endphp

                    <article class="rounded-xl border border-slate-100 bg-white p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[#16136a]">{{ $today->format('F Y') }}</h3>
                            <div class="flex gap-1">
                                <button class="h-8 w-8 rounded-xl bg-slate-50 text-slate-400 hover:text-[#16136a] transition"><i class="ri-arrow-left-s-line"></i></button>
                                <button class="h-8 w-8 rounded-xl bg-slate-50 text-slate-400 hover:text-[#16136a] transition"><i class="ri-arrow-right-s-line"></i></button>
                            </div>
                        </div>

                        <div class="grid grid-cols-7 gap-y-6 text-center">
                            {{-- Days of week headers --}}
                            @foreach(['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $day)
                                <div class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">{{ $day }}</div>
                            @endforeach

                            {{-- Empty slots for start of month --}}
                            @for ($i = 0; $i < $startDayOfWeek; $i++)
                                <div></div>
                            @endfor

                            {{-- Days of the month --}}
                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $isToday = $day === $today->day;
                                    $hasEvent = in_array($day, $eventDays);
                                @endphp
                                <div class="relative flex justify-center group {{ $hasEvent ? 'cursor-pointer' : '' }}">
                                    <div @class([
                                        'flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold transition-all duration-300',
                                        'bg-[#16136a] text-white shadow-md' => $isToday,
                                        'text-slate-900 group-hover:bg-[#16136a] group-hover:text-white shadow-sm ring-1 ring-slate-100 group-hover:ring-transparent' => !$isToday && $hasEvent,
                                        'text-slate-400 hover:bg-slate-50' => !$isToday && !$hasEvent,
                                    ])>
                                        {{ $day }}
                                    </div>
                                    @if($hasEvent)
                                        <div class="absolute -bottom-1.5 h-1 w-1 rounded-full bg-red-500 transition-transform duration-300 group-hover:scale-0"></div>
                                        
                                        @php
                                            $dayEvents = collect($events)->filter(fn($e) => $e['start_at'] && $e['start_at']->isSameMonth($today) && $e['start_at']->day === $day);
                                        @endphp
                                        <div class="pointer-events-none absolute bottom-full left-1/2 z-50 mb-3 -translate-x-1/2 translate-y-2 opacity-0 transition-all duration-300 group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100">
                                            <div class="w-48 rounded-xl bg-[#16136a] p-4 text-left text-white shadow-2xl shadow-[#16136a]/40">
                                                <div class="absolute -bottom-1 left-1/2 h-3 w-3 -translate-x-1/2 rotate-45 bg-[#16136a]"></div>
                                                <div class="relative z-10 space-y-3">
                                                    @foreach($dayEvents as $dayEvent)
                                                        <a href="{{ route('student.events.show', $dayEvent['id']) }}" class="block border-b border-white/10 pb-3 last:border-0 last:pb-0 group/link">
                                                            <p class="mb-1 text-[9px] font-semibold uppercase tracking-widest text-white/50">{{ optional($dayEvent['start_at'])->format('g:i A') }}</p>
                                                            <p class="text-xs font-semibold leading-snug text-white line-clamp-2 transition-colors group-hover/link:text-red-400">{{ $dayEvent['title'] }}</p>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </article>

                    {{-- Quick Action Card --}}
                    <article class="rounded-xl bg-[#16136a] p-8 text-white shadow-2xl shadow-[#16136a]/30">
                        <i class="ri-calendar-check-line text-4xl opacity-30"></i>
                        <h3 class="mt-6 text-xl font-semibold italic tracking-tight">Sync to Device</h3>
                        <p class="mt-4 text-xs font-semibold text-white/60 leading-relaxed uppercase tracking-wider">
                            Never miss an event. Sync the GESA academic calendar directly to your phone or laptop.
                        </p>
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <button data-calendar-trigger data-calendar-webcal="webcal://example.com/calendar.ics" class="flex w-full items-center justify-between group">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.3em] opacity-60 group-hover:opacity-100 transition-opacity">Add to Calendar</span>
                                <i class="ri-download-cloud-2-line text-xl transition-transform group-hover:-translate-y-1"></i>
                            </button>
                        </div>
                    </article>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
