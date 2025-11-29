<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-12" role="status" aria-live="polite">
            <section class="overflow-hidden rounded-[28px] border border-[#16136a]/15 bg-[#16136a] p-10 shadow-[0_24px_60px_-30px_rgba(22,19,106,0.45)]">
                <div class="space-y-5">
                    <div class="skeleton h-3 w-48 rounded-full bg-white/25"></div>
                    <div class="skeleton h-9 w-3/4 rounded-2xl bg-white/20"></div>
                    <div class="skeleton h-4 w-2/3 rounded-2xl bg-white/15"></div>
                    <div class="flex flex-wrap gap-2">
                        @for ($i = 0; $i < 4; $i++)
                            <span class="skeleton inline-block h-6 w-20 rounded-full bg-white/10"></span>
                        @endfor
                    </div>
                </div>
            </section>

            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @for ($i = 0; $i < 3; $i++)
                    <article class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <div class="flex items-start gap-4">
                            <div class="skeleton h-12 w-12 rounded-2xl bg-[#16136a]/10"></div>
                            <div class="flex-1 space-y-3">
                                <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                <div class="skeleton h-7 w-20 rounded-2xl bg-slate-200"></div>
                                <div class="skeleton h-4 w-full rounded-2xl bg-slate-200/80"></div>
                                <div class="skeleton h-4 w-1/2 rounded-full bg-slate-200/80"></div>
                            </div>
                        </div>
                    </article>
                @endfor
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <article class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                    <div class="space-y-4">
                        <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-4 w-72 rounded-full bg-slate-100"></div>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="skeleton h-20 rounded-2xl bg-slate-100"></div>
                            @endfor
                        </div>
                    </div>
                </article>
                <aside class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="space-y-4">
                        <div class="skeleton h-5 w-44 rounded-full bg-slate-200"></div>
                        @for ($i = 0; $i < 3; $i++)
                            <div class="space-y-2">
                                <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-full rounded-2xl bg-slate-100"></div>
                                <div class="skeleton h-4 w-32 rounded-full bg-slate-100"></div>
                            </div>
                        @endfor
                    </div>
                </aside>
            </section>

            <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="space-y-4">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-72 rounded-full bg-slate-100"></div>
                    <div class="space-y-3">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="skeleton h-16 rounded-2xl bg-slate-100"></div>
                        @endfor
                    </div>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-12">
        @php($duesAction = collect($quickActions ?? [])->firstWhere('label', 'Outstanding dues'))
        @php($nextEvent = ($events ?? collect())->first())

        <section class="relative animate-fade-slide overflow-hidden rounded-[28px] border border-[#16136a]/15 bg-[#16136a] bg-gradient-to-br from-[#16136a] via-[#16136a] to-[#16136a] p-10 text-white shadow-[0_24px_60px_-30px_rgba(22,19,106,0.4)]">
            <div class="pointer-events-none absolute -inset-20 opacity-40">
                <div class="h-full w-full animate-spin duration-[48000ms] ease-linear motion-reduce:animate-none">
                    <div class="h-full w-full rounded-[56px] bg-[conic-gradient(from_120deg_at_50%_50%,rgba(255,255,255,0.35),rgba(255,255,255,0)_70%)] blur-3xl"></div>
                </div>
            </div>
            <div class="relative flex flex-col gap-6">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-100/80">GESA Student Portal</p>
                <div class="space-y-4">
                    <h1 class="text-3xl font-semibold md:text-4xl">{{ $hero['greeting'] ?? 'Welcome back' }}, {{ $hero['first_name'] ?? 'Student' }}!</h1>
                    <p class="max-w-2xl text-sm text-slate-100/85">
                        {{ $hero['message'] ?? 'Stay on top of your academic tasks, dues, and campus life in one place.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-100/80">
                    @foreach ($hero['chips'] ?? [] as $chip)
                        <span class="rounded-full bg-white/10 px-3 py-1">{{ $chip }}</span>
                    @endforeach
                </div>
                @if ($duesAction || $nextEvent)
                    <div class="mt-2 flex flex-wrap gap-3 text-xs text-slate-100/80">
                        @if ($duesAction)
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                                <i class="ri-wallet-3-line text-sm" aria-hidden="true"></i>
                                <span class="text-[11px] font-semibold tracking-[0.22em]">{{ strtoupper($duesAction['state'] ?? 'Dues') }}</span>
                                @if (!empty($duesAction['value']))
                                    <span class="text-slate-100/80">· {{ $duesAction['value'] }}</span>
                                @endif
                            </div>
                        @endif
                        @if ($nextEvent)
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                                <i class="ri-calendar-event-line text-sm" aria-hidden="true"></i>
                                <span class="text-[11px] font-semibold tracking-[0.22em]">NEXT EVENT</span>
                                <span class="text-xs font-medium text-slate-100">
                                    {{ \Illuminate\Support\Str::limit($nextEvent['title'] ?? 'Upcoming event', 40) }}
                                </span>
                                @if (!empty($nextEvent['datetime']))
                                    <span class="text-slate-100/80">· {{ $nextEvent['datetime'] }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($quickActions as $index => $action)
                <article class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/12 animate-fade-slide {{ $index > 0 ? 'delay-[' . (60 * $index) . 'ms]' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" class="h-7 w-7" aria-hidden="true">
                                {!! $action['icon_svg'] ?? '<circle cx="12" cy="12" r="6" />' !!}
                            </svg>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/70">
                                <span>{{ $action['label'] ?? 'Action' }}</span>
                                @if (!empty($action['state']))
                                    <span class="rounded-full bg-[#16136a]/7 px-2 py-0.5 text-[10px] tracking-[0.22em]">
                                        {{ strtoupper($action['state']) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-2xl font-semibold text-[#16136a]">{{ $action['value'] ?? '--' }}</p>
                            <p class="text-sm text-slate-600">{{ $action['summary'] ?? '' }}</p>
                            <a href="{{ $action['cta_url'] ?? '#' }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition hover:underline">
                                {{ $action['cta'] ?? 'Open' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="m9 5 7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="space-y-6">
            @php($tipCollection = $securityTips->values())
            @php($firstTip = $tipCollection->first())

            <article class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10" data-tip-slider data-tip-autoplay="5000" data-tip-tips='@json($tipCollection)'>
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-[#16136a]">Daily tech & security tips</h2>
                    <div class="hidden items-center gap-2 text-xs text-slate-500">
                        <span data-tip-counter>{{ $tipCollection->isNotEmpty() ? '1/' . $tipCollection->count() : '0/0' }}</span>
                        <div class="flex items-center gap-1">
                            <button type="button" class="rounded-full border border-slate-200 p-1 text-[#16136a] transition hover:bg-[#16136a]/10 disabled:opacity-40" data-tip-prev aria-label="Previous tip">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="m15 19-7-7 7-7" />
                                </svg>
                            </button>
                            <button type="button" class="rounded-full border border-slate-200 p-1 text-[#16136a] transition hover:bg-[#16136a]/10 disabled:opacity-40" data-tip-next aria-label="Next tip">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="m9 5 7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if ($tipCollection->isEmpty())
                    <div class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                        No security advisories today. Stay vigilant and check back tomorrow.
                    </div>
                @else
                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl border border-slate-200/70 bg-white/80 p-5" data-tip-panel>
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                <span data-tip-category>{{ $firstTip['category'] ?? 'Security' }}</span>
                                <span data-tip-published>{{ $firstTip['published'] ?? '' }}</span>
                            </div>
                            <h3 class="mt-3 text-base font-semibold text-slate-900" data-tip-title>{{ $firstTip['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm text-slate-600" data-tip-excerpt>{{ $firstTip['excerpt'] ?? '' }}</p>
                        </div>
                        <div class="hidden items-center gap-2" data-tip-dots>
                            @foreach ($tipCollection as $index => $tip)
                                <button type="button" class="h-1.5 w-6 rounded-full transition {{ $index === 0 ? 'bg-[#16136a]' : 'bg-slate-200' }}" data-tip-dot="{{ $index }}" aria-label="View tip"></button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <article class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10 delay-[120ms]">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-[#16136a]">Upcoming events</h2>
                    <a href="{{ route('student.events.index') }}" class="text-sm font-semibold text-[#16136a] transition hover:underline">All events</a>
                </div>
                <ul class="mt-5 space-y-3">
                    @forelse ($events as $event)
                        <li class="rounded-2xl border border-slate-200/60 bg-white/80 px-4 py-3">
                            <div class="flex items-start gap-3">
                                @if (!empty($event['banner_url']))
                                    <img
                                        src="{{ $event['banner_url'] }}"
                                        alt="{{ $event['banner_alt'] ?? ($event['title'] . ' banner') }}"
                                        class="h-12 w-12 rounded-2xl object-cover ring-1 ring-[#16136a]/10"
                                        loading="lazy"
                                    >
                                @else
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                                        <i class="ri-calendar-event-fill text-lg" aria-hidden="true"></i>
                                    </span>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900">{{ $event['title'] }}</h3>
                                        <span class="text-xs text-slate-500 whitespace-nowrap">{{ $event['datetime'] }}</span>
                                    </div>
                                    @if ($event['location'])
                                        <p class="mt-1 text-xs text-slate-500">{{ $event['location'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                            No upcoming events scheduled. We’ll update this space soon.
                        </li>
                    @endforelse
                </ul>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <article class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Department calendar</h2>
                        <p class="text-sm text-slate-500">Stay aligned with upcoming events and key milestones.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/20 bg-[#16136a]/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/80">{{ $calendarMonthLabel }}</span>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                    <div class="overflow-x-auto sm:overflow-visible">
                        <div class="grid w-full grid-cols-7 bg-slate-50 text-[11px] uppercase tracking-[0.25em] text-slate-500 sm:text-xs">
                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday)
                                <div class="px-3 py-2 font-semibold">{{ $weekday }}</div>
                            @endforeach
                        </div>

                        <div class="grid w-full grid-cols-7 border-t border-slate-200 text-xs sm:text-sm">
                            @foreach ($calendarWeeks as $week)
                                @foreach ($week as $day)
                                    <div class="flex min-h-[96px] flex-col border-slate-200 p-2 sm:p-3 {{ $loop->last ? '' : 'border-r' }} {{ $loop->parent->last ? '' : 'border-b' }} {{ $day['is_current_month'] ? 'bg-white' : 'bg-slate-50/70 text-slate-400' }}">
                                        <div class="flex items-center justify-between">
                                            <span class="hidden text-xs font-semibold uppercase tracking-[0.2em] {{ $day['is_today'] ? 'text-[#16136a]' : 'text-slate-400' }} sm:inline">{{ $day['month'] }}</span>
                                            <span @class([
                                                'flex h-7 w-7 items-center justify-center rounded-full text-sm font-semibold',
                                                'bg-[#16136a] text-white' => $day['is_today'],
                                                'bg-[#16136a]/10 text-[#16136a] ring-1 ring-[#16136a]/40' => !$day['is_today'] && $day['is_current_month'] && !empty($day['has_upcoming']),
                                                'text-slate-700' => !$day['is_today'] && !($day['is_current_month'] && !empty($day['has_upcoming'])),
                                            ])>
                                                {{ $day['day'] }}
                                            </span>
                                        </div>
                                        <div class="mt-2 space-y-2 sm:mt-3">
                                            @foreach ($day['events'] as $event)
                                                <a
                                                    href="{{ $event['cta_url'] ?? route('student.events.index') }}"
                                                    @class([
                                                        'group relative hidden w-full rounded-xl border border-[#16136a]/15 bg-[#16136a]/5 p-2 text-[11px] text-[#16136a] transition hover:-translate-y-0.5 hover:shadow-md sm:block',
                                                        'ring-2 ring-[#16136a]/30' => !empty($event['is_upcoming']),
                                                    ])
                                                    x-data="{ open: false }"
                                                    @mouseenter="open = true"
                                                    @focus="open = true"
                                                    @mouseleave="open = false"
                                                    @blur="open = false"
                                                    :class="open ? 'z-40 shadow-xl' : 'z-10'"
                                                    @if (!empty($event['is_external'])) target="_blank" rel="noopener" @endif
                                                    aria-label="{{ $event['title'] }}{{ $event['time'] ? ' · ' . $event['time'] : '' }}"
                                                >
                                                    <div class="hidden items-start justify-between gap-2 sm:flex">
                                                        <p class="max-w-full truncate font-semibold" title="{{ $event['title'] }}">{{ \Illuminate\Support\Str::limit($event['title'], 28) }}</p>
                                                        <span class="hidden rounded-full bg-white/40 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]/80 group-hover:inline">{{ $event['category'] ?? 'Event' }}</span>
                                                    </div>
                                                    <p class="mt-1 hidden text-[10px] font-medium text-[#16136a]/70 sm:block sm:text-[11px] sm:uppercase sm:tracking-[0.2em]">{{ $event['time'] ?? 'All day' }}</p>
                                                    @if (!empty($event['location']))
                                                        <p class="mt-1 hidden truncate text-[11px] text-slate-600 sm:block" title="{{ $event['location'] }}">{{ \Illuminate\Support\Str::limit($event['location'], 24) }}</p>
                                                    @endif
                                                    @if (!empty($event['description']))
                                                        <div
                                                            x-show="open"
                                                            x-transition.opacity.duration.150ms
                                                            class="absolute right-0 top-full z-50 mt-2 hidden w-56 rounded-lg border border-white/30 bg-white/95 p-3 text-left text-[10px] text-slate-700 shadow-xl sm:block"
                                                            role="tooltip"
                                                        >
                                                            <p class="font-semibold text-[#16136a]">{{ $event['title'] }}</p>
                                                            <p class="mt-1 text-[11px] uppercase tracking-[0.2em] text-[#16136a]/70">{{ $event['time'] ?? 'All day' }}</p>
                                                            <p class="mt-1 text-[11px] text-slate-600">{{ $event['description'] }}</p>
                                                            @if (!empty($event['cta_url']))
                                                                <span class="mt-2 inline-flex items-center gap-1 text-[10px] font-semibold text-[#16136a]">Open details →</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block h-3 w-3 rounded-full bg-[#16136a]"></span>
                        Today
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block h-3 w-3 rounded-full border border-[#16136a]/40 bg-[#16136a]/10"></span>
                        Upcoming event
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="inline-block h-3 w-3 rounded-full border border-amber-300 bg-amber-200"></span>
                        Academic timeline milestone
                    </span>
                </div>
            </article>

            <aside class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10 delay-[120ms]">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Upcoming events</h2>
                        <p class="text-sm text-slate-500">Quick view of what’s next on your calendar.</p>
                    </div>
                    <a href="{{ route('student.events.index') }}" class="text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/70 transition hover:text-[#16136a]">All events</a>
                </div>
                <ul class="mt-5 space-y-4">
                    @forelse ($events as $event)
                        <li class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white/90 p-4">
                            <span class="inline-flex h-11 w-11 flex-col items-center justify-center rounded-2xl border border-[#16136a]/20 bg-[#16136a]/5 text-[11px] font-semibold text-[#16136a]">
                                <span>{{ $event['month_label'] ?? 'TBA' }}</span>
                                <span class="text-base">{{ $event['day_label'] ?? '--' }}</span>
                            </span>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]/70">
                                    <span>{{ $event['category'] ?? 'Event' }}</span>
                                    @if (!empty($event['location']))
                                        <span class="text-slate-400">{{ \Illuminate\Support\Str::limit($event['location'], 24) }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-1 text-base font-semibold text-slate-900">{{ \Illuminate\Support\Str::limit($event['title'], 60) }}</h3>
                                @if (!empty($event['datetime']))
                                    <p class="mt-1 text-xs text-slate-500">{{ $event['datetime'] }}</p>
                                @endif
                                <a
                                    href="{{ $event['cta_url'] ?? route('student.events.index') }}"
                                    @if (!empty($event['is_external'])) target="_blank" rel="noopener" @endif
                                    class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition hover:underline"
                                >
                                    View details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path d="m9 5 7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                            Upcoming event highlights will appear here as your schedule fills up.
                        </li>
                    @endforelse
                </ul>
            </aside>
        </section>

        <section class="animate-fade-slide rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-[#16136a]">Academic timeline</h2>
                    <p class="text-sm text-slate-500">Track upcoming milestones and deadlines set by your department.</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/20 bg-[#16136a]/5 px-3 py-1 text-[#16136a]/80">Upcoming</span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-slate-400">Past</span>
                </div>
            </div>

            <div class="mt-6 relative">
                <div class="absolute left-[1.25rem] top-0 bottom-0 w-px bg-slate-200 sm:left-1/2"></div>
                <ul class="space-y-4">
                    @forelse ($timelineEntries as $entry)
                        <li class="relative grid grid-cols-1 sm:grid-cols-2">
                            @php($isLeftAligned = $loop->iteration % 2 === 1)

                            <div class="col-span-1 pl-12 {{ $isLeftAligned ? 'sm:col-start-1 sm:pr-8' : 'sm:col-start-2 sm:pl-8' }}">
                                <div @class([
                                    'rounded-2xl border p-5 transition',
                                    'border-[#16136a]/25 bg-[#16136a]/5 shadow-inner shadow-[#16136a]/10' => empty($entry['is_past']),
                                    'border-slate-200 bg-white/80 text-slate-500' => !empty($entry['is_past']),
                                ])>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="space-y-1">
                                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                                                {{ $entry['date_label'] ?? ($entry['starts_at'] ?? 'TBA') }}
                                            </p>
                                            <h3 class="text-base font-semibold text-[#16136a]">{{ $entry['title'] }}</h3>
                                        </div>
                                        @if (!empty($entry['cta_url']) && !empty($entry['cta_label']))
                                            <a href="{{ $entry['cta_url'] }}" class="inline-flex items-center gap-2 self-start rounded-full border border-[#16136a]/20 bg-white px-4 py-2 text-sm font-semibold text-[#16136a] transition hover:-translate-y-0.5 hover:border-[#16136a]/40">
                                                {{ $entry['cta_label'] }}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                    <path d="m9 5 7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute left-[1.25rem] top-3 z-10 -translate-x-1/2 sm:left-1/2">
                                <div @class([
                                    'inline-flex h-10 w-10 flex-col items-center justify-center rounded-2xl border text-[11px] font-semibold',
                                    'border-[#16136a]/40 bg-[#16136a]/5 text-[#16136a]' => empty($entry['is_past']),
                                    'border-slate-200 bg-white text-slate-400' => !empty($entry['is_past']),
                                ])>
                                    <span class="leading-none uppercase tracking-[0.18em]">{{ $entry['month_label'] ?? 'TBA' }}</span>
                                    <span class="text-sm leading-tight">{{ $entry['day_label'] ?? '—' }}</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white/60 px-4 py-6 text-center text-sm text-slate-500">
                            Academic timeline updates from your department will appear here once published.
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
</x-layouts.dashboard>
