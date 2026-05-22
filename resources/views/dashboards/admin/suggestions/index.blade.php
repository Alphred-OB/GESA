@php
    $title = $title ?? 'Student Feedback';
    $now = now();
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header Section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Suggestions Hub</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Review and respond to the student voice</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total Inbox</span>
                        <span class="text-2xl font-semibold text-[#16136a]">{{ number_format($metrics['total'] ?? 0) }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 mx-2"></div>
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-500">Awaiting Action</span>
                        <span class="text-2xl font-semibold text-amber-600">{{ number_format($metrics['pending'] ?? 0) }}</span>
                    </div>
                </div>
            </header>

            {{-- Summary Cards (Bento Style) --}}
            @php
                $pending = $metrics['pending'] ?? 0;
                $resolved = $metrics['resolved'] ?? 0;
                $total = $metrics['total'] ?? 0;
                $velocity = $metrics['resolvedThisWeek'] ?? 0;
                $rate = $total > 0 ? round(($resolved / $total) * 100) : 100;
            @endphp
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div @class([
                    'relative overflow-hidden rounded-[2.5rem] p-8 text-white shadow-xl',
                    'bg-[#16136a] shadow-[#16136a]/20' => $pending > 0,
                    'bg-emerald-600 shadow-emerald-600/20' => $pending === 0,
                ])>
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Inbox Status</p>
                        @if($pending > 0)
                            <p class="mt-4 text-4xl font-semibold text-amber-400">{{ $pending }} <span class="text-lg text-white/40 font-semibold uppercase tracking-widest">Pending</span></p>
                            <p class="mt-2 text-xs font-semibold text-white/40 italic">Feedback requiring immediate review</p>
                        @else
                            <p class="mt-4 text-4xl font-semibold text-white">Inbox <span class="text-lg text-white/40 font-semibold uppercase tracking-widest">Clear</span></p>
                            <p class="mt-2 text-xs font-semibold text-white/40 italic">All suggestions have been reviewed</p>
                        @endif
                    </div>
                    <x-heroicon-o-envelope class="absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12 size-5" />
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Response Rate</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $rate }}% <span class="text-lg text-slate-300 uppercase tracking-widest">Resolved</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">{{ $resolved }} cases handled successfully</p>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Activity Velocity</p>
                    <p class="mt-4 text-4xl font-semibold text-emerald-600">+{{ $velocity }} <span class="text-lg text-slate-300 uppercase tracking-widest">Weekly</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Resolved since Monday</p>
                </div>
            </div>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-check-circle class="size-6" />
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Filter Bar --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40 lg:p-8">
                <form method="GET" class="grid gap-6 md:grid-cols-4 lg:grid-cols-5">
                    @php
                        $extraFilters = request()->except(['search', 'category', 'status', 'page']);
                    @endphp
                    @foreach ($extraFilters as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="md:col-span-2 space-y-2">
                        <label for="search" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Search Content</label>
                        <div class="relative">
                            <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                            <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" 
                                class="h-12 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" 
                                placeholder="Keywords, student names, subjects...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Category</label>
                        <select id="category" name="category" class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Categories</option>
                            @foreach ($categories as $catVal => $catLabel)
                                <option value="{{ $catVal }}" @if(($filters['category'] ?? '') == (string)$catVal) selected @endif>{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Status</label>
                        <select id="status" name="status" class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">Any Status</option>
                            @foreach ($statuses as $statVal => $statLabel)
                                <option value="{{ $statVal }}" @if(($filters['status'] ?? '') == (string)$statVal) selected @endif>{{ $statLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="flex h-12 w-full items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                            <x-heroicon-o-funnel class="size-5" /> Filter Inbox
                        </button>
                    </div>
                </form>
            </section>

            {{-- Bulk Actions & List --}}
            <section class="space-y-6" x-data="bulkActions(@js($suggestions->pluck('id')))">
                <div class="flex items-center justify-between px-4">
                    <div class="flex items-center gap-4">
                        <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Feedback Inbox</h2>
                        <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 animate-in slide-in-from-left-4">
                            <span class="text-[10px] font-semibold text-amber-600 uppercase tracking-widest"><span x-text="selectedIds.length"></span> Selected</span>
                            <button x-on:click="clearSelection()" class="text-amber-400 hover:text-amber-600 transition-colors">
                                <x-heroicon-s-x-circle class="size-5" />
                            </button>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.suggestions.bulk') }}" x-ref="bulkForm" class="flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>

                        <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-3 animate-in slide-in-from-right-4">
                            <select name="status" required class="h-10 rounded-xl border-none bg-amber-100/50 px-3 text-[10px] font-semibold uppercase tracking-widest text-amber-700 outline-none focus:ring-2 focus:ring-amber-500/20">
                                <option value="">Bulk Status</option>
                                @foreach ($statuses as $statVal => $statLabel)
                                    <option value="{{ $statVal }}">{{ $statLabel }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="flex h-10 items-center gap-2 rounded-xl bg-amber-500 px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-amber-500/20 transition-all hover:-translate-y-0.5">
                                Apply
                            </button>
                        </div>

                        <select name="per_page" x-on:change="window.location.href = updateQueryStringParameter(window.location.href, 'per_page', $el.value)" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-[10px] font-semibold text-slate-600 outline-none">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @if($option === $currentPerPage) selected @endif>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid gap-6">
                    @forelse ($suggestions as $suggestion)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white p-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60">
                            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <div class="flex items-start gap-5 min-w-0">
                                    <label class="mt-1 shrink-0 cursor-pointer">
                                        <input type="checkbox" class="h-5 w-5 rounded-lg border-2 border-slate-200 text-[#16136a] transition-all focus:ring-[#16136a]/10" 
                                            x-on:change="toggleId(@js($suggestion->id))" :checked="selectedIds.includes(@js($suggestion->id))">
                                    </label>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            @php
                                                $status = strtolower($suggestion->status ?? 'pending');
                                                $statusClass = match($status) {
                                                    'pending' => 'bg-amber-100 text-amber-700',
                                                    'in_review' => 'bg-blue-100 text-blue-700',
                                                    'resolved' => 'bg-emerald-100 text-emerald-700',
                                                    'dismissed' => 'bg-rose-100 text-rose-700',
                                                    default => 'bg-slate-100 text-slate-500'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 rounded-full {{ $statusClass }} px-3 py-1 text-[9px] font-semibold uppercase tracking-widest">
                                                {{ $statuses[$suggestion->status] ?? Str::headline($suggestion->status) }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-slate-300 uppercase tracking-widest">
                                                {{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $suggestion->subject }}</h3>
                                        <p class="mt-1 text-sm font-semibold text-slate-400 line-clamp-2 leading-relaxed">
                                            {{ Str::limit(strip_tags($suggestion->message), 200) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col lg:items-end gap-4 shrink-0 border-t lg:border-t-0 border-slate-50 pt-4 lg:pt-0">
                                    <div class="flex items-center gap-3 lg:justify-end">
                                        <div class="text-right">
                                            <p class="text-xs font-semibold text-slate-900">{{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Anonymous Student' }}</p>
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-tight">{{ $suggestion->created_at?->diffForHumans() }}</p>
                                        </div>
                                        <div class="h-10 w-10 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400 font-semibold text-xs">
                                            {{ strtoupper(substr($suggestion->user?->fullname ?? 'S', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="flex h-10 items-center gap-2 rounded-xl bg-slate-50 px-4 text-[10px] font-semibold uppercase tracking-widest text-slate-500 transition-all hover:bg-[#16136a] hover:text-white">
                                            <x-heroicon-o-eye class="size-5" /> Details
                                        </a>
                                        @if($suggestion->attachment_path)
                                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-500" title="Has Attachment">
                                                <x-heroicon-o-paper-clip class="size-5" />
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[2.5rem] border border-dashed border-slate-300 p-20 text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                <x-heroicon-o-chat-bubble-left class="text-5xl size-5" />
                            </div>
                            <h3 class="mt-6 text-lg font-semibold text-slate-900">No Feedback Found</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-400">Student suggestions will appear here once they start sharing their thoughts.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($suggestions->hasPages())
                    <div class="mt-8 rounded-[2rem] bg-slate-50 p-4 text-center">
                        {{ $suggestions->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script>
        function bulkActions(allIds) {
            return {
                selectedIds: [],
                toggleId(id) {
                    const index = this.selectedIds.indexOf(id);
                    if (index === -1) this.selectedIds.push(id);
                    else this.selectedIds.splice(index, 1);
                },
                clearSelection() {
                    this.selectedIds = [];
                },
                allSelected: false,
                toggleAll(checked) {
                    this.selectedIds = checked ? [...allIds] : [];
                }
            }
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>
</x-layouts.admin>
