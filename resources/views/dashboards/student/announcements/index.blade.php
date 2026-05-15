<x-layouts.dashboard :title="$title">
    <div x-data="{ view: $persist('grid') }" class="mx-auto w-full max-w-full px-8 py-10 space-y-10">
        <div class="space-y-10">
            {{-- Simplified Bento Hero --}}
            <section class="relative isolate overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur-md">
                                <i class="ri-megaphone-line"></i> Global Broadcast
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-none text-white">Campus Pulse</h1>
                            <p class="text-sm font-medium text-white/70 leading-relaxed max-w-xl">
                                Stay informed about academic deadlines, workshops, security advisories, and key GESA portal changes.
                            </p>
                        </div>
                    </div>
                    
                    <div class="shrink-0 rounded-xl border border-white/10 bg-white/5 px-6 py-4 text-sm shadow-inner backdrop-blur-md">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-white/50">Feed Status</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-3xl font-semibold tabular-nums tracking-tighter text-white">Live</span>
                        </div>
                    </div>
                </div>

                <!-- Subtle background depth -->
                <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-white/5 blur-3xl"></div>
                <i class="ri-megaphone-line absolute -right-10 -bottom-10 text-[240px] text-white/[0.03] -rotate-12 select-none pointer-events-none"></i>
            </section>

            <form method="GET" action="{{ route('student.announcements.index') }}" class="grid gap-4 rounded-xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:grid-cols-2 lg:flex lg:flex-wrap lg:items-end">
                <div class="flex flex-col gap-2 lg:flex-1">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400 ml-1">Search Feed</span>
                    <div class="relative">
                        <input type="search" name="search" value="{{ $filters['search'] }}" placeholder="Keyword, department..." class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40 transition-all">
                    </div>
                </div>

                <div class="flex flex-col gap-2 lg:w-44">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400 ml-1">Type</span>
                    <div class="relative">
                        <select name="type" class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-4 pr-12 text-sm font-semibold text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            <option value="">All Types</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" @selected($filters['type'] === $value)> {{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2 lg:w-44">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400 ml-1">Priority</span>
                    <div class="relative">
                        <select name="priority" class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-12 text-sm font-semibold text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            <option value="">All Priorities</option>
                            @foreach ($priorities as $value => $label)
                                <option value="{{ $value }}" @selected($filters['priority'] === $value)> {{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end gap-2 sm:col-span-2 lg:w-auto">
                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#16136a] px-6 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg transition hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                        <span>Filter Feed</span>
                    </button>
                    <a href="{{ route('student.announcements.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-[10px] font-semibold uppercase tracking-widest text-slate-600 transition hover:bg-slate-50 active:scale-95">Reset</a>
                </div>
            </form>

            <section class="space-y-6">
                <header class="flex flex-col gap-4 border-b border-[#16136a]/10 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-[#16136a]">Departmental Notices</h2>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Real-time update stream</p>
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

                        @if ($announcements->hasPages())
                            <div class="flex items-center gap-4 text-[11px] font-semibold text-slate-400">
                                <span class="uppercase tracking-widest hidden sm:inline">Page {{ $announcements->currentPage() }} of {{ $announcements->lastPage() }}</span>
                                {{ $announcements->onEachSide(1)->links('vendor.pagination.simple-tailwind') }}
                            </div>
                        @endif
                    </div>
                </header>

                @if ($announcements->isEmpty())
                    <article class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 py-20 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm ring-1 ring-slate-100">
                            <i class="ri-notification-off-line text-3xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-[#16136a]">No notices found.</p>
                        <p class="mt-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Check back later or adjust filters</p>
                    </article>
                @else
                    {{-- Grid View --}}
                    <div x-show="view === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid gap-6 md:grid-cols-2">
                        @foreach ($announcements as $announcement)
                            <article class="group relative flex flex-col rounded-xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-[#16136a]/5">
                                <div class="space-y-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex gap-2">
                                            <span class="rounded-xl bg-slate-50 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">{{ $types[$announcement->type] ?? Str::headline($announcement->type) }}</span>
                                            <span @class([
                                                'rounded-xl px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest ring-1',
                                                'bg-red-50 text-red-600 ring-red-100' => $announcement->priority === 'high',
                                                'bg-amber-50 text-amber-600 ring-amber-100' => $announcement->priority === 'medium',
                                                'bg-emerald-50 text-emerald-600 ring-emerald-100' => $announcement->priority === 'low',
                                            ])>{{ $priorities[$announcement->priority] ?? Str::headline($announcement->priority) }}</span>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $announcement->published_at?->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="text-xl font-semibold tracking-tight text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">
                                        <a href="{{ route('student.announcements.show', $announcement) }}" class="after:absolute after:inset-0 after:rounded-xl">{{ $announcement->title }}</a>
                                    </h3>
                                    <p class="text-sm font-medium leading-relaxed text-slate-500 line-clamp-3">{{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content), 160) }}</p>
                                </div>
                                <div class="mt-8 flex items-center justify-between pt-6 border-t border-slate-50">
                                    <div class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                        <i class="ri-calendar-line text-[#16136a]"></i>
                                        <span>{{ $announcement->published_at?->format('M j, Y') }}</span>
                                    </div>
                                    <div class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-[#16136a] transition group-hover:gap-3">
                                        <span>Read Entry</span>
                                        <i class="ri-arrow-right-line"></i>
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
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Notice Details</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 hidden lg:table-cell">Type</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Priority</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 hidden sm:table-cell">Published</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($announcements as $announcement)
                                    <tr 
                                        @click="window.location.href = '{{ route('student.announcements.show', $announcement) }}'"
                                        class="group cursor-pointer hover:bg-slate-50/30 transition-colors"
                                    >
                                        <td class="px-8 py-6">
                                            <div class="space-y-1">
                                                <h4 class="text-sm font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $announcement->title }}</h4>
                                                <p class="text-xs font-medium text-slate-400 line-clamp-1">{{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content), 80) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 hidden lg:table-cell">
                                            <span class="rounded-xl bg-slate-50 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-slate-100">{{ $types[$announcement->type] ?? Str::headline($announcement->type) }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span @class([
                                                'rounded-xl px-2.5 py-1 text-[9px] font-semibold uppercase tracking-widest ring-1',
                                                'bg-red-50 text-red-600 ring-red-100' => $announcement->priority === 'high',
                                                'bg-amber-50 text-amber-600 ring-amber-100' => $announcement->priority === 'medium',
                                                'bg-emerald-50 text-emerald-600 ring-emerald-100' => $announcement->priority === 'low',
                                            ])>{{ $priorities[$announcement->priority] ?? Str::headline($announcement->priority) }}</span>
                                        </td>
                                        <td class="px-8 py-6 hidden sm:table-cell">
                                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $announcement->published_at?->format('M j, Y') }}</span>
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

                    <div class="pt-10">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.dashboard>
