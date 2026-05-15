<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-8">
        {{-- Main Content --}}
        <div class="space-y-10">
            
            {{-- Dashboard Logic --}}
            @php
                $duesValue = collect($quickActions ?? [])->firstWhere('label', 'Outstanding dues')['value'] ?? 'GHS 0.00';
            @endphp

            {{-- God-Tier Bento Hero --}}
            <section class="relative isolate overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur-md">
                                <i class="ri-graduation-cap-fill"></i> {{ $hero['chips'][0] ?? 'Geomatic Engineering' }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-tight text-white">
                                {{ $hero['greeting'] ?? 'Good morning' }}, <br>
                                <span>{{ $hero['first_name'] ?? 'Student' }}</span>
                            </h1>
                            <p class="text-sm font-medium text-white/70 leading-relaxed max-w-xl">
                                Welcome to your central command. Track your academic milestones, settle financials, and stay connected with the department.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Subtle background depth -->
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-[#16136a]/50 blur-3xl"></div>
                <i class="ri-dashboard-3-line absolute -right-10 -bottom-10 text-[280px] text-white/[0.03] -rotate-12 select-none pointer-events-none"></i>
            </section>

            {{-- Bento Grid Primary Actions --}}
            <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($quickActions as $index => $action)
                    <article @class([
                        'group relative overflow-hidden rounded-xl p-0.5 transition-all duration-500 hover:scale-[1.01]',
                        'bg-white hover:shadow-xl hover:shadow-[#16136a]/5',
                        'lg:col-span-1'
                    ])>
                        <div class="relative h-full space-y-8 rounded-xl border border-slate-100 bg-white p-6 group-hover:border-[#16136a]/10 transition-colors">
                            <div class="flex items-start justify-between relative z-10">
                                <div @class([
                                    'flex h-12 w-12 items-center justify-center rounded-xl transition-all duration-500',
                                    'bg-slate-50 text-[#16136a] group-hover:bg-[#16136a] group-hover:text-white',
                                ])>
                                    <i class="{{ $action['icon'] ?? 'ri-circle-line' }} text-2xl"></i>
                                </div>
                            </div>

                            <div class="space-y-1 relative z-10">
                                <h3 class="text-[10px] font-semibold uppercase tracking-[0.15em] text-[#16136a]/40 group-hover:text-[#16136a] transition-colors">{{ $action['label'] }}</h3>
                                <p class="text-3xl font-semibold tabular-nums tracking-tight text-slate-900">
                                    {{ $action['value'] }}
                                </p>
                            </div>

                            <div class="space-y-4 relative z-10">
                                <p class="text-sm font-medium text-slate-500 leading-relaxed line-clamp-2">
                                    {{ $action['summary'] }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <a href="{{ $action['cta_url'] ?? '#' }}" class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-[#16136a] group-hover:gap-3 transition-all">
                                        <span>{{ $action['cta'] }}</span>
                                        <i class="ri-arrow-right-line"></i>
                                    </a>
                                    <div class="h-1 w-8 rounded-full bg-slate-100 transition-all duration-500 group-hover:w-12 group-hover:bg-[#16136a]"></div>
                                </div>
                            </div>

                            {{-- Background Depth Effect --}}
                            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-slate-50 transition-all duration-700 group-hover:scale-[2] group-hover:bg-[#16136a]/[0.01]"></div>
                        </div>
                    </article>
                @endforeach
            </section>

            {{-- Secondary Layout: Feed & Schedule --}}
            <div class="grid gap-10 lg:grid-cols-12">
                
                {{-- Left Column: Feed & Milestones --}}
                <div class="space-y-12 lg:col-span-8">
                    
                    {{-- Latest Announcements --}}
                    <section class="space-y-6">
                        <div class="flex items-center justify-between border-b border-[#16136a]/10 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-md shadow-[#16136a]/10">
                                    <i class="ri-megaphone-fill"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Departmental Updates</h2>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Latest announcements</p>
                                </div>
                            </div>
                            <a href="{{ route('student.announcements.index') }}" class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-[10px] font-semibold uppercase tracking-widest text-[#16136a] shadow-sm ring-1 ring-slate-200 transition-all hover:bg-[#16136a] hover:text-white hover:ring-[#16136a]">
                                <span>All Notices</span>
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>

                        <div class="grid gap-5">
                            @forelse ($announcements as $announcement)
                                <article class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:shadow-xl hover:shadow-[#16136a]/5">
                                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                                        {{-- Priority Indicator --}}
                                        <div @class([
                                            'hidden h-12 w-1.5 flex-shrink-0 rounded-full sm:block',
                                            'bg-red-500' => $announcement['priority'] === 'high',
                                            'bg-amber-400' => $announcement['priority'] === 'medium',
                                            'bg-emerald-400' => $announcement['priority'] === 'low',
                                        ])></div>

                                        <div class="flex-1 space-y-4">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span @class([
                                                        'rounded-xl px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest ring-1',
                                                        'bg-red-50 text-red-600 ring-red-100' => $announcement['priority'] === 'high',
                                                        'bg-slate-50 text-slate-500 ring-slate-100' => $announcement['priority'] !== 'high',
                                                    ])>
                                                        {{ $announcement['type'] }}
                                                    </span>
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $announcement['time_ago'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400">
                                                    <div class="h-5 w-5 overflow-hidden rounded-full bg-slate-100 ring-1 ring-slate-200">
                                                        @if($announcement['author_avatar'])
                                                            <img src="{{ $announcement['author_avatar'] }}" alt="" class="h-full w-full object-cover">
                                                        @else
                                                            <div class="flex h-full w-full items-center justify-center bg-[#16136a] text-[8px] text-white">{{ substr($announcement['author_name'], 0, 1) }}</div>
                                                        @endif
                                                    </div>
                                                    <span>{{ $announcement['author_name'] }}</span>
                                                </div>
                                            </div>

                                            <div class="space-y-1">
                                                <h3 class="text-xl font-semibold tracking-tight text-slate-900 group-hover:text-[#16136a] transition-colors">
                                                    <a href="{{ route('student.announcements.show', $announcement['slug']) }}">{{ $announcement['title'] }}</a>
                                                </h3>
                                                <p class="text-sm font-medium leading-relaxed text-slate-500 line-clamp-2">
                                                    {{ $announcement['excerpt'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Hover Decoration --}}
                                    <div class="absolute -bottom-10 -right-10 h-32 w-32 rounded-full bg-[#16136a]/[0.02] transition-transform duration-700 group-hover:scale-150"></div>
                                </article>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 py-16 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm ring-1 ring-slate-100">
                                        <i class="ri-megaphone-line text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-400">No new updates today.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- Upcoming Events Feed --}}
                    <section class="space-y-6">
                        <div class="flex items-center justify-between border-b border-[#16136a]/10 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-md shadow-[#16136a]/10">
                                    <i class="ri-calendar-event-fill"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Events</h2>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Mark your calendar</p>
                                </div>
                            </div>
                            <a href="{{ route('student.events.index') }}" class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-[10px] font-semibold uppercase tracking-widest text-[#16136a] shadow-sm ring-1 ring-slate-200 transition-all hover:bg-[#16136a] hover:text-white hover:ring-[#16136a]">
                                <span>All Events</span>
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @forelse ($events->take(4) as $event)
                                <article class="flex items-center gap-5 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-100 transition-all hover:shadow-lg hover:shadow-[#16136a]/5 group">
                                    <div class="flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-slate-50 text-[#16136a] transition-all duration-500 group-hover:bg-[#16136a] group-hover:text-white">
                                        <span class="text-[9px] font-semibold uppercase tracking-tighter opacity-60">{{ $event['month_label'] ?? 'SEP' }}</span>
                                        <span class="text-xl font-semibold leading-none">{{ $event['day_label'] ?? '12' }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1 space-y-0.5">
                                        <h4 class="truncate text-base font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors">{{ $event['title'] }}</h4>
                                        <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400">
                                            <i class="ri-map-pin-2-fill text-[#16136a]/40"></i>
                                            <span class="truncate">{{ $event['location'] ?? 'Campus' }}</span>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="col-span-full rounded-xl bg-white border border-dashed border-slate-100 py-12 text-center">
                                    <p class="text-sm font-semibold text-slate-400">No events found.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- Academic Timeline --}}
                    <section class="space-y-6">
                        <div class="flex items-center justify-between border-b border-[#16136a]/10 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-md shadow-[#16136a]/10">
                                    <i class="ri-pulse-fill"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Milestones</h2>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Semester Roadmap</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative space-y-6 before:absolute before:left-7 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @foreach ($timelineEntries->take(3) as $entry)
                                <div class="relative flex items-start gap-8 pl-14 group">
                                    <div @class([
                                        'absolute left-5 top-4 h-4 w-4 rounded-full border-[3px] border-[#F4F7FB] transition-all duration-700 z-10',
                                        'bg-[#16136a] shadow-[0_0_0_6px_rgba(22,19,106,0.05)]' => empty($entry['is_past']),
                                        'bg-slate-300' => !empty($entry['is_past']),
                                    ])></div>
                                    <div @class([
                                        'flex-1 rounded-xl p-6 transition-all duration-700 shadow-sm',
                                        'bg-white ring-1 ring-slate-100 group-hover:shadow-xl group-hover:shadow-[#16136a]/5' => empty($entry['is_past']),
                                        'bg-slate-100/40 opacity-60' => !empty($entry['is_past']),
                                    ])>
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="space-y-0.5">
                                                <div @class([
                                                    'flex items-center gap-2 text-[9px] font-semibold uppercase tracking-[0.2em]',
                                                    'text-[#16136a]' => empty($entry['is_past']),
                                                    'text-slate-400' => !empty($entry['is_past']),
                                                ])>
                                                    <span>{{ $entry['date_label'] ?? 'TBA' }}</span>
                                                </div>
                                                <h3 class="text-base font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors">{{ $entry['title'] }}</h3>
                                            </div>
                                            @if (!empty($entry['cta_url']))
                                                <a href="{{ $entry['cta_url'] }}" class="inline-flex h-9 items-center rounded-xl bg-slate-900 px-4 text-[10px] font-semibold uppercase tracking-widest text-white transition-all hover:bg-[#16136a] shadow-md">
                                                    {{ $entry['cta_label'] }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Right Column: Side-Rails --}}
                <aside class="space-y-8 lg:col-span-4">
                    
                    {{-- Calendar --}}
                    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="font-semibold text-slate-900 text-sm">Calendar</h2>
                            <span class="text-[10px] font-semibold text-[#16136a] uppercase tracking-widest bg-[#16136a]/5 px-2 py-1 rounded-xl">{{ $calendarMonthLabel }}</span>
                        </div>
                        <div class="grid grid-cols-7 gap-2 text-center text-[9px] font-semibold text-slate-400 mb-6">
                            @foreach (['M','T','W','T','F','S','S'] as $day)
                                <div>{{ $day }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-2">
                            @foreach ($calendarWeeks as $week)
                                @foreach ($week as $day)
                                    <div @class([
                                        'flex h-8 w-8 items-center justify-center rounded-xl text-[10px] transition-all duration-300',
                                        'bg-[#16136a] font-semibold text-white shadow-lg shadow-[#16136a]/20 scale-105' => $day['is_today'],
                                        'bg-emerald-50 font-semibold text-emerald-600' => !$day['is_today'] && $day['is_current_month'] && !empty($day['has_upcoming']),
                                        'text-slate-900 font-semibold hover:bg-slate-50' => !$day['is_today'] && $day['is_current_month'] && empty($day['has_upcoming']),
                                        'text-slate-200' => !$day['is_current_month'],
                                    ])>
                                        {{ $day['day'] }}
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </section>

                    {{-- Tips Carousel --}}
                    <section x-data="{ 
                        active: 0, 
                        tips: {{ $securityTips->toJson() }},
                        next() { this.active = (this.active + 1) % this.tips.length },
                        init() { setInterval(() => this.next(), 6000) }
                    }" class="relative overflow-hidden rounded-xl bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/10 min-h-[220px]">
                        <template x-for="(tip, index) in tips" :key="index">
                            <div x-show="active === index" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-xl bg-white/10 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest ring-1 ring-white/20" x-text="tip.category"></span>
                                    <i class="ri-lightbulb-flash-fill text-xl text-amber-300"></i>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-semibold leading-tight" x-text="tip.title"></h3>
                                    <p class="text-xs text-slate-300 leading-relaxed opacity-80" x-text="tip.excerpt"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Pagination Dots --}}
                        <div class="absolute bottom-6 left-8 flex gap-1.5">
                            <template x-for="(tip, index) in tips" :key="index">
                                <button @click="active = index" 
                                        :class="active === index ? 'w-4 bg-white' : 'w-1 bg-white/20'"
                                        class="h-1 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>
                    </section>

                </aside>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
