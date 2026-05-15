@php($title = $title ?? 'Academic Timeline')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header Section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Academic Timeline</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Orchestrate key academic milestones</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total Milestones</span>
                        <span class="text-2xl font-semibold text-[#16136a]">{{ number_format($entries->total()) }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 mx-2"></div>
                    <a href="{{ route('admin.timeline.create') }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <i class="ri-add-line text-lg transition-transform group-hover:rotate-90"></i>
                        New Milestone
                    </a>
                </div>
            </header>

            {{-- Summary Cards (Bento Style) --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Active Milestones</p>
                        @php($activeCount = $entries->where('is_published', true)->count())
                        <p class="mt-4 text-4xl font-semibold text-emerald-400">{{ $activeCount }}</p>
                        <p class="mt-2 text-xs font-semibold text-white/40 italic">Live for student visibility</p>
                    </div>
                    <i class="ri-flag-2-line absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12"></i>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Next Upcoming</p>
                    @php($nextEntry = $entries->where('starts_at', '>', now())->sortBy('starts_at')->first())
                    @if($nextEntry)
                        <p class="mt-4 text-lg font-semibold text-[#16136a] truncate">{{ $nextEntry->title }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-400">{{ $nextEntry->starts_at->format('M d, Y') }} ({{ $nextEntry->starts_at->diffForHumans() }})</p>
                    @else
                        <p class="mt-4 text-lg font-semibold text-slate-300 italic">No upcoming events</p>
                        <p class="mt-1 text-xs font-semibold text-slate-400">Schedule a new entry</p>
                    @endif
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Academic Coverage</p>
                    @php($years = $entries->pluck('academic_year')->unique()->count())
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $years }} <span class="text-lg text-slate-300 uppercase tracking-widest">Sessions</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Recorded academic years</p>
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

            {{-- Timeline List --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Published Milestones</h2>
                    <form method="GET" class="flex items-center gap-3">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Display</label>
                        <select name="per_page" onchange="this.form.submit()" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-xs font-semibold text-slate-600 outline-none focus:ring-2 focus:ring-[#16136a]/10">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid gap-6">
                    @forelse ($entries as $entry)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white p-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60 lg:p-8">
                            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-start gap-6 lg:items-center">
                                    <div @class([
                                        'flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl font-semibold transition-transform group-hover:scale-110',
                                        'bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20' => $entry->is_published && !$entry->isPast(),
                                        'bg-emerald-100 text-emerald-600' => $entry->is_published && $entry->isPast(),
                                        'bg-slate-100 text-slate-400' => !$entry->is_published
                                    ])>
                                        @if($entry->isPast())
                                            <i class="ri-checkbox-circle-line text-2xl"></i>
                                        @else
                                            <i class="ri-flag-2-line text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="truncate text-xl font-semibold text-slate-900">{{ $entry->title }}</h3>
                                            @if ($entry->is_published)
                                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Live
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                                                    Hidden
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-2">
                                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                                                <i class="ri-calendar-event-line text-[#16136a]"></i>
                                                {{ $entry->starts_at?->format('F d, Y') }}
                                            </div>
                                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                                                <i class="ri-government-line text-[#16136a]"></i>
                                                {{ $entry->academic_year ?? 'Not Assigned' }}
                                            </div>
                                            @if($entry->isPast())
                                                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-500 italic">
                                                    <i class="ri-history-line"></i>
                                                    Archived (Past Event)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 lg:justify-end">
                                    <a href="{{ route('admin.timeline.edit', $entry) }}" class="flex h-12 flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-50 px-6 text-[10px] font-semibold uppercase tracking-widest text-slate-600 transition-all hover:bg-[#16136a] hover:text-white sm:flex-initial">
                                        <i class="ri-edit-line text-lg"></i>
                                        Edit Entry
                                    </a>
                                    <form method="POST" action="{{ route('admin.timeline.destroy', $entry) }}" onsubmit="return confirm('Delete this milestone permanentely?');" class="flex flex-1 sm:flex-initial">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-rose-50 px-6 text-[10px] font-semibold uppercase tracking-widest text-rose-500 transition-all hover:bg-rose-500 hover:text-white sm:w-auto">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            {{-- Visual Timeline Line --}}
                            <div class="absolute left-0 top-0 h-full w-1.5 bg-[#16136a]/5 transition-all group-hover:bg-[#16136a]/20"></div>
                        </article>
                    @empty
                        <div class="rounded-[2.5rem] border border-dashed border-slate-300 p-20 text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                <i class="ri-time-line text-5xl"></i>
                            </div>
                            <h3 class="mt-6 text-lg font-semibold text-slate-900">No Milestones Published</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-400">Plan and publish your academic sessions to guide students.</p>
                            <a href="{{ route('admin.timeline.create') }}" class="mt-8 inline-flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-8 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20">
                                <i class="ri-add-line text-lg"></i>
                                Add First Entry
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($entries->hasPages())
                    <div class="mt-8 rounded-[2rem] bg-slate-50 p-4">
                        {{ $entries->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
