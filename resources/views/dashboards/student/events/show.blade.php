<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="space-y-10">
            {{-- God-Tier Header / Breadcrumb --}}
            <nav class="flex items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">
                <a href="{{ route('student.dashboard') }}" class="transition hover:text-[#16136a]">Core</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <a href="{{ route('student.events.index') }}" class="transition hover:text-[#16136a]">Events Feed</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <span class="text-[#16136a] truncate max-w-[200px]">{{ $event->title }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-4">
                {{-- Main Content Column --}}
                <div class="lg:col-span-3 space-y-10">
                    <article class="relative overflow-hidden rounded-xl border border-slate-100 bg-white p-8 shadow-sm lg:p-16">
                        <header class="space-y-8">
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="rounded-xl bg-slate-50 px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">
                                    {{ Str::headline($event->category) }}
                                </span>
                                @if($event->type === 'online')
                                    <span class="rounded-xl bg-emerald-50 px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-emerald-600 ring-1 ring-emerald-100">
                                        <x-heroicon-o-globe-alt class="mr-1 size-5" /> Online
                                    </span>
                                @elseif($event->type === 'hybrid')
                                    <span class="rounded-xl bg-amber-50 px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-amber-600 ring-1 ring-amber-100">
                                        <x-heroicon-o-code-bracket class="mr-1 size-5" /> Hybrid
                                    </span>
                                @else
                                    <span class="rounded-xl bg-blue-50 px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-blue-600 ring-1 ring-blue-100">
                                        <x-heroicon-o-map-pin class="mr-1 size-5" /> Physical
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-6">
                                <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl md:text-6xl leading-[1.1]">
                                    {{ $event->title }}
                                </h1>
                                
                                <div class="flex flex-wrap items-center gap-x-8 gap-y-4 pt-2">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] ring-1 ring-slate-100">
                                            <x-heroicon-o-calendar-days class="size-5" />
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-900">{{ $event->start_at?->format('M j, Y') }}</span>
                                            <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">{{ $event->start_at?->format('g:i A') }} @if($event->end_at) - {{ $event->end_at->format('g:i A') }} @endif</span>
                                        </div>
                                    </div>

                                    @if($event->location && in_array($event->type, ['physical', 'hybrid']))
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] ring-1 ring-slate-100">
                                                <x-heroicon-o-map-pin class="size-5" />
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-900">Location</span>
                                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">{{ $event->location }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </header>

                        @if ($event->banner_url)
                            <div class="mt-12 overflow-hidden rounded-xl bg-slate-100">
                                <img src="{{ $event->banner_url }}" alt="{{ $event->banner_alt ?? $event->title }}" class="w-full object-cover max-h-[400px]">
                            </div>
                        @else
                            <div class="mt-12 h-px w-full bg-gradient-to-r from-slate-100 via-slate-100 to-transparent"></div>
                        @endif

                        <div class="mt-12 prose prose-slate max-w-none prose-headings:font-semibold prose-headings:tracking-tight prose-headings:text-[#16136a] prose-p:text-base prose-p:font-medium prose-p:leading-relaxed prose-p:text-slate-600 prose-strong:font-semibold prose-strong:text-slate-900">
                            {!! $event->description ? nl2br(e($event->description)) : '<p class="text-slate-400 italic">No additional details provided.</p>' !!}
                        </div>

                        @if($event->cta_url)
                            <div class="mt-12">
                                <a href="{{ $event->cta_url }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-8 py-4 text-xs font-semibold uppercase tracking-widest text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-xl hover:shadow-[#16136a]/20">
                                    <span>Register / External Link</span>
                                    <x-heroicon-o-arrow-top-right-on-square class="size-5" />
                                </a>
                            </div>
                        @endif

                    </article>
                </div>

                {{-- Sidebar / Meta Column --}}
                <aside class="space-y-10">
                    {{-- Meeting details card (if online or hybrid) --}}
                    @if(in_array($event->type, ['online', 'hybrid']) && $event->meeting_link)
                        <article class="rounded-xl border border-emerald-100 bg-emerald-50 p-8 shadow-sm">
                            <x-heroicon-o-video-camera class="text-4xl text-emerald-600/30 size-5" />
                            <h3 class="mt-6 text-xl font-semibold italic tracking-tight text-emerald-900">Virtual Meeting</h3>
                            <p class="mt-4 text-[10px] font-semibold text-emerald-700/70 leading-relaxed uppercase tracking-widest">
                                This event includes an online component. Click below to join the virtual session.
                            </p>
                            @if($event->meeting_passcode)
                                <div class="mt-6 flex items-center gap-3 rounded-xl bg-white/60 p-4 ring-1 ring-emerald-200">
                                    <x-heroicon-o-key class="text-emerald-600 size-5" />
                                    <div>
                                        <p class="text-[9px] font-semibold uppercase tracking-widest text-emerald-800/60">Meeting Passcode</p>
                                        <p class="text-sm font-semibold text-emerald-900">{{ $event->meeting_passcode }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-6">
                                <a href="{{ $event->meeting_link }}" target="_blank" class="flex items-center justify-between group rounded-xl bg-emerald-600 px-6 py-4 text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 hover:-translate-y-1">
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em]">Join Meeting</span>
                                    <x-heroicon-o-arrow-up-right class="size-6" />
                                </a>
                            </div>
                        </article>
                    @endif

                    {{-- Calendar Add Card --}}
                    <article class="rounded-xl bg-[#16136a] p-8 text-white shadow-2xl shadow-[#16136a]/30">
                        <x-heroicon-o-calendar-days class="text-4xl opacity-30 size-5" />
                        <h3 class="mt-6 text-xl font-semibold italic tracking-tight">Sync Event</h3>
                        <p class="mt-4 text-[10px] font-semibold text-white/60 leading-relaxed uppercase tracking-wider">
                            Add this specific event to your personal calendar.
                        </p>
                        <div class="mt-8 pt-8 border-t border-white/10 space-y-4">
                            <a href="{{ $calendarLinks['google'] }}" target="_blank" class="flex items-center justify-between group">
                                <div class="flex items-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <x-heroicon-s-star class="size-5" />
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em]">Google Calendar</span>
                                </div>
                                <x-heroicon-o-arrow-right class="transition-transform group-hover:translate-x-1 size-5" />
                            </a>
                            <a href="{{ $calendarLinks['outlook'] }}" target="_blank" class="flex items-center justify-between group">
                                <div class="flex items-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <x-heroicon-s-star class="size-5" />
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em]">Outlook</span>
                                </div>
                                <x-heroicon-o-arrow-right class="transition-transform group-hover:translate-x-1 size-5" />
                            </a>
                            <a href="{{ $calendarLinks['webcal'] }}" class="flex items-center justify-between group">
                                <div class="flex items-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <x-heroicon-s-star class="size-5" />
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em]">Apple Calendar</span>
                                </div>
                                <x-heroicon-o-arrow-right class="transition-transform group-hover:translate-x-1 size-5" />
                            </a>
                        </div>
                    </article>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
