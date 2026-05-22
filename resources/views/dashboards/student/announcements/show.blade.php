<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="space-y-10">
            {{-- God-Tier Header / Breadcrumb --}}
            <nav class="flex items-center gap-3 text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">
                <a href="{{ route('student.dashboard') }}" class="transition hover:text-[#16136a]">Core</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <a href="{{ route('student.announcements.index') }}" class="transition hover:text-[#16136a]">Pulse</a>
                <x-heroicon-o-chevron-right class="size-3.5" />
                <span class="text-[#16136a] truncate max-w-[200px]">{{ $announcement->title }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-4">
                {{-- Main Content Column --}}
                <div class="lg:col-span-3 space-y-10">
                    <article class="relative overflow-hidden rounded-xl border border-slate-100 bg-white p-8 shadow-sm lg:p-16">

                        <header class="space-y-8">
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="rounded-xl bg-slate-50 px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">
                                    {{ $announcement->type_label ?? Str::headline($announcement->type) }}
                                </span>
                                <span @class([
                                    'rounded-xl px-4 py-1.5 text-[10px] font-semibold uppercase tracking-widest ring-1',
                                    'bg-red-50 text-red-600 ring-red-100' => $announcement->priority === 'high',
                                    'bg-amber-50 text-amber-600 ring-amber-100' => $announcement->priority === 'medium',
                                    'bg-emerald-50 text-emerald-600 ring-emerald-100' => $announcement->priority === 'low',
                                ])>
                                    {{ $announcement->priority_label ?? Str::headline($announcement->priority) }} Priority
                                </span>
                            </div>

                            <div class="space-y-4">
                                <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl md:text-6xl leading-[1.1]">
                                    {{ $announcement->title }}
                                </h1>
                                <div class="flex items-center gap-4 pt-2">
                                    @if ($announcement->author)
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a] text-[10px] font-semibold text-white shadow-lg shadow-[#16136a]/20 uppercase">
                                                {{ mb_strtoupper(mb_substr($announcement->author->fullname ?? $announcement->author->username, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-900">{{ $announcement->author->fullname ?? $announcement->author->username }}</span>
                                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">Portal Administrator</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="h-8 w-px bg-slate-100 mx-2"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-900">{{ $announcement->published_at?->format('M j, Y') }}</span>
                                        <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">{{ $announcement->published_at?->format('g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <div class="mt-12 h-px w-full bg-gradient-to-r from-slate-100 via-slate-100 to-transparent"></div>

                        <div class="mt-12 prose prose-slate max-w-none prose-headings:font-semibold prose-headings:tracking-tight prose-headings:text-[#16136a] prose-p:text-base prose-p:font-medium prose-p:leading-relaxed prose-p:text-slate-600 prose-strong:font-semibold prose-strong:text-slate-900">
                            @if ($announcement->excerpt)
                                <p class="text-xl font-semibold italic text-slate-400 leading-relaxed mb-10 border-l-4 border-slate-100 pl-8">
                                    {{ $announcement->excerpt }}
                                </p>
                            @endif
                            
                            {!! $renderedContent ?: nl2br(e($announcement->content)) !!}
                        </div>

                    </article>
                </div>

                {{-- Sidebar / Meta Column --}}
                <aside class="space-y-10">
                    {{-- Quick Action Card --}}
                    <article class="rounded-xl bg-[#16136a] p-8 text-white shadow-2xl shadow-[#16136a]/30">
                        <x-heroicon-o-information-circle class="text-4xl opacity-30 size-5" />
                        <h3 class="mt-6 text-xl font-semibold italic tracking-tight">Need Clarity?</h3>
                        <p class="mt-4 text-xs font-semibold text-white/60 leading-relaxed uppercase tracking-wider">
                            If you require more details regarding this notice, please contact the department office or your class representative.
                        </p>
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <a href="mailto:gesaumat24@gmail.com" class="flex items-center justify-between group">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.3em] opacity-60 group-hover:opacity-100 transition-opacity">Contact Admin</span>
                                <x-heroicon-o-paper-airplane class="size-6 transition-transform group-hover:translate-x-1" />
                            </a>
                        </div>
                    </article>

                    {{-- Related Entries --}}
                    @if ($related->isNotEmpty())
                        <div class="space-y-6">
                            <h3 class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 ml-2">Contextual Updates</h3>
                            <div class="space-y-4">
                                @foreach ($related as $item)
                                    <a href="{{ route('student.announcements.show', $item) }}" class="group block rounded-xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-[#16136a]/5">
                                        <div class="flex flex-col gap-3">
                                            <div class="flex items-center gap-2 text-[8px] font-semibold uppercase tracking-widest text-slate-400">
                                                <span class="rounded-xl bg-slate-50 px-2 py-0.5">{{ Str::headline($item->type) }}</span>
                                                <span>{{ $item->published_at?->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-2 leading-snug">{{ $item->title }}</h4>
                                            <div class="flex items-center gap-1 text-[9px] font-semibold text-[#16136a] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span>Read Entry</span>
                                                <x-heroicon-o-arrow-right class="size-5" />
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
