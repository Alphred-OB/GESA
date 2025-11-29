@php
    use Illuminate\Support\Str;
    $title = 'Student suggestions';
@endphp

<x-layouts.admin :title="$title">
	@include('components.dashboard.skeleton-styles')
	<div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="relative">
	    <div x-show="loading" x-transition.opacity.duration.200ms class="pointer-events-none absolute inset-0 z-10 flex justify-center bg-slate-50/80 backdrop-blur-sm" role="status" aria-live="polite">
	        <div class="mx-auto w-full max-w-6xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
	            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/90 p-6 shadow-lg shadow-[#16136a]/10">
	                <div class="space-y-2">
	                    <div class="skeleton inline-flex h-7 w-48 items-center rounded-full bg-[#16136a]/10"></div>
	                    <div class="skeleton h-8 w-64 rounded-2xl bg-slate-200"></div>
	                    <div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
	                </div>
	            </header>

	            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
	                <div class="grid gap-4 sm:grid-cols-3">
	                    @for ($i = 0; $i < 3; $i++)
	                        <article class="rounded-2xl border border-slate-200/80 bg-slate-50 px-5 py-4 shadow-sm">
	                            <div class="space-y-3">
	                                <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
	                                <div class="skeleton h-8 w-16 rounded-2xl bg-slate-200"></div>
	                                <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
	                            </div>
	                        </article>
	                    @endfor
	                </div>

	                <div class="grid gap-3 md:grid-cols-4">
	                    <div class="space-y-2 md:col-span-2">
	                        <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
	                        <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
	                    </div>
	                    <div class="space-y-2">
	                        <div class="skeleton h-3 w-20 rounded-full bg-slate-200"></div>
	                        <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
	                    </div>
	                    <div class="space-y-2">
	                        <div class="skeleton h-3 w-20 rounded-full bg-slate-200"></div>
	                        <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
	                    </div>
	                    <div class="space-y-2">
	                        <div class="skeleton h-3 w-16 rounded-full bg-slate-200"></div>
	                        <div class="skeleton h-11 w-full rounded-2xl bg-[#16136a]/10"></div>
	                    </div>
	                </div>

	                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
	                    <div class="skeleton h-3 w-56 rounded-full bg-slate-100"></div>
	                    <div class="flex items-center gap-2">
	                        <div class="skeleton h-3 w-32 rounded-full bg-slate-100"></div>
	                        <div class="skeleton h-9 w-20 rounded-xl bg-slate-100"></div>
	                    </div>
	                </div>

	                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
	                    <div class="hidden md:block">
	                        <div class="skeleton h-10 w-full bg-slate-50"></div>
	                        <div class="space-y-2 bg-white p-4">
	                            @for ($i = 0; $i < 5; $i++)
	                                <div class="skeleton h-11 w-full rounded-xl bg-slate-50"></div>
	                            @endfor
	                        </div>
	                    </div>

	                    <div class="grid gap-4 bg-white p-4 md:hidden">
	                        @for ($i = 0; $i < 3; $i++)
	                            <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
	                                <div class="space-y-3">
	                                    <div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
	                                    <div class="skeleton h-3 w-32 rounded-full bg-slate-100"></div>
	                                    <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
	                                </div>
	                                <div class="mt-4 flex items-center justify-between">
	                                    <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
	                                    <div class="skeleton h-8 w-24 rounded-xl bg-slate-100"></div>
	                                </div>
	                            </article>
	                        @endfor
	                    </div>
	                </div>

	                <div class="mt-4 flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
	                    <div class="skeleton h-3 w-48 rounded-full bg-slate-100"></div>
	                    <div class="flex justify-center gap-2 sm:ml-auto sm:justify-end">
	                        @for ($i = 0; $i < 4; $i++)
	                            <div class="skeleton h-8 w-8 rounded-full bg-slate-100"></div>
	                        @endfor
	                    </div>
	                </div>
	            </section>
	        </div>
	    </div>

	    <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="mx-auto w-full max-w-6xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
	        <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/5">
	            <div class="space-y-2">
	                <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
	                    <i class="ri-chat-1-line text-base" aria-hidden="true"></i>
	                    {{ $title }}
	                </p>
	                <h1 class="text-2xl font-semibold text-[#16136a] sm:text-3xl">Review student suggestions</h1>
	                <p class="text-sm text-slate-600">See what students are asking for and track progress as you resolve their feedback.</p>
	            </div>
	        </header>

	        <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
	            <div class="grid gap-4 sm:grid-cols-3">
	                <article class="rounded-2xl border border-slate-200/80 bg-slate-50 px-5 py-4 shadow-sm">
	                    <div class="flex items-center justify-between">
	                        <div>
	                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Total suggestions</p>
	                            <p class="mt-2 text-3xl font-semibold text-[#16136a]">{{ number_format($metrics['total']) }}</p>
	                        </div>
	                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
	                            <i class="ri-chat-1-line text-xl" aria-hidden="true"></i>
	                        </span>
	                    </div>
	                    <p class="mt-3 text-xs text-slate-500">Since launch.</p>
	                </article>
	                <article class="rounded-2xl border border-slate-200/80 bg-slate-50 px-5 py-4 shadow-sm">
	                    <div class="flex items-center justify-between">
	                        <div>
	                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Awaiting review</p>
	                            <p class="mt-2 text-2xl font-semibold text-[#16136a]">{{ number_format($metrics['pending']) }}</p>
	                        </div>
	                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-[#16136a]">
	                            <i class="ri-timer-line text-lg" aria-hidden="true"></i>
	                        </span>
	                    </div>
	                    <p class="mt-2 text-xs text-slate-500">Marked as pending.</p>
	                </article>
	                <article class="rounded-2xl border border-slate-200/80 bg-slate-50 px-5 py-4 shadow-sm">
	                    <div class="flex items-center justify-between">
	                        <div>
	                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Resolved this week</p>
	                            <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ number_format($metrics['resolvedThisWeek']) }}</p>
	                        </div>
	                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-emerald-600">
	                            <i class="ri-checkbox-circle-line text-lg" aria-hidden="true"></i>
	                        </span>
	                    </div>
	                    <p class="mt-2 text-xs text-slate-500">Handled since Monday.</p>
	                </article>
	            </div>

                <div class="flex w-full flex-col gap-2 md:w-64">
                    <label for="filter_search" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</label>
                    <div class="relative">
                        <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="filter_search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Subject, message, student" class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-52">
                    <label for="filter_category" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Category</label>
                    <div class="relative">
                        <select id="filter_category" name="category" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All</option>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['category'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-40">
                    <label for="filter_status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <div class="relative">
                        <select id="filter_status" name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end md:ml-auto md:w-auto">
                    <button type="submit" class="inline-flex h-11 min-w-[140px] items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-equalizer-line text-base"></i>
                        Apply
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                <p class="font-semibold">Showing {{ $suggestions->firstItem() ?? 0 }}–{{ $suggestions->lastItem() ?? 0 }} of {{ $suggestions->total() }} suggestions</p>
                <form method="GET" class="flex items-center gap-2">
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="suggestions_per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                    <select id="suggestions_per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <section class="space-y-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4" x-data="adminSuggestionBulk()" x-init="initialize(@js($suggestions->pluck('id')))" x-cloak>
                <form method="POST" action="{{ route('admin.suggestions.bulk') }}" x-ref="bulkForm">
                    @csrf
                    <input type="hidden" name="action" x-ref="actionInput">
                    <input type="hidden" name="status" x-ref="statusInput">
                    <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">

                    <div class="flex flex-col gap-4 rounded-xl border border-slate-200/80 bg-slate-50/60 px-4 py-3 md:flex-row md:items-center md:justify-between">
                        <p class="text-sm font-medium text-slate-600" x-text="bulkSummary"></p>
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4" x-show="selectedIds.length" x-cloak x-transition.opacity>
                            <div class="flex flex-col gap-1">
                                <label for="suggestion_bulk_status" class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Change status</label>
                                <select id="suggestion_bulk_status" x-model="statusValue" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#16136a] focus:ring-[#16136a]/40 md:min-w-[12rem]">
                                    <option value="">Select status</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-[#16136a] px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#16136a]/90 disabled:opacity-60" @click="submit('update_status')" :disabled="!canApplyStatus">
                                <i class="ri-check-double-line text-sm"></i>
                                Apply status
                            </button>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                        <div class="hidden md:block">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-[13px] text-slate-600">
                                <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                                    <tr>
                                        <th class="w-12 px-5 py-2.5">
                                            <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggleAll($event.target.checked)" :checked="allSelected">
                                        </th>
                                        <th class="px-5 py-2.5">Student</th>
                                        <th class="px-5 py-2.5">Category</th>
                                        <th class="px-5 py-2.5">Subject</th>
                                        <th class="px-5 py-2.5">Status</th>
                                        <th class="px-5 py-2.5">Submitted</th>
                                        <th class="px-5 py-2.5 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @forelse ($suggestions as $suggestion)
                                        <tr>
                                            <td class="px-5 py-3">
                                                <input type="checkbox" name="ids[]" value="{{ $suggestion->id }}" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggle({{ $suggestion->id }}, $event.target.checked)" :checked="selectedIds.includes({{ $suggestion->id }})">
                                            </td>
                                            <td class="px-5 py-3">
                                                <div class="flex flex-col gap-0.5">
                                                    <span class="text-[15px] font-semibold text-slate-900">{{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Unknown student' }}</span>
                                                    <span class="text-[12px] text-slate-500">{{ $suggestion->user?->email }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 text-sm text-slate-500">{{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}</td>
                                            <td class="px-5 py-3 text-sm text-slate-600">
                                                <div class="font-semibold text-slate-900">{{ $suggestion->subject }}</div>
                                                <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ Str::limit(strip_tags($suggestion->message), 120) }}</p>
                                            </td>
                                            <td class="px-5 py-3">
                                                @php
                                                    $status = strtolower($suggestion->status ?? 'pending');
                                                    $badgeMap = [
                                                        'pending' => 'bg-amber-50 text-amber-700',
                                                        'in_review' => 'bg-blue-50 text-blue-700',
                                                        'resolved' => 'bg-emerald-50 text-emerald-700',
                                                        'dismissed' => 'bg-rose-50 text-rose-600',
                                                    ];
                                                    $badgeClass = $badgeMap[$status] ?? 'bg-slate-100 text-slate-600';
                                                @endphp
                                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    <i class="ri-checkbox-circle-line text-sm"></i>
                                                    {{ $statuses[$suggestion->status] ?? Str::headline($suggestion->status) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-[12px] text-slate-500">{{ $suggestion->created_at?->format('M j, Y · g:i A') ?? '—' }}</td>
                                            <td class="px-5 py-3">
                                                <div class="flex items-center justify-end">
                                                    <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                        <i class="ri-eye-line text-sm"></i>
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
                                                <div class="flex flex-col items-center gap-3">
                                                    <i class="ri-chat-off-line text-3xl text-slate-300"></i>
                                                    <p class="font-semibold text-slate-600">No suggestions found</p>
                                                    <p class="text-sm text-slate-500">Adjust filters or encourage students to share their thoughts.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="md:hidden">
                            <div class="grid gap-4">
                                @forelse ($suggestions as $suggestion)
                                    <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                                        <header class="flex items-start justify-between gap-3">
                                            <div>
                                                <h2 class="text-base font-semibold text-slate-900">{{ $suggestion->subject }}</h2>
                                                <p class="text-xs text-slate-500">{{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Unknown student' }}</p>
                                            </div>
                                            @php
                                                $status = strtolower($suggestion->status ?? 'pending');
                                                $badgeMap = [
                                                    'pending' => 'bg-amber-50 text-amber-700',
                                                    'in_review' => 'bg-blue-50 text-blue-700',
                                                    'resolved' => 'bg-emerald-50 text-emerald-700',
                                                    'dismissed' => 'bg-rose-50 text-rose-600',
                                                ];
                                                $badgeClass = $badgeMap[$status] ?? 'bg-slate-100 text-slate-600';
                                            @endphp
                                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $statuses[$suggestion->status] ?? Str::headline($suggestion->status) }}
                                            </span>
                                        </header>
                                        <p class="mt-3 text-sm text-slate-600">{{ Str::limit(strip_tags($suggestion->message), 160) }}</p>
                                        <dl class="mt-4 space-y-2 text-xs text-slate-500">
                                            <div class="flex items-center justify-between">
                                                <dt>Category</dt>
                                                <dd>{{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}</dd>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <dt>Submitted</dt>
                                                <dd>{{ $suggestion->created_at?->format('M j, Y · g:i A') ?? '—' }}</dd>
                                            </div>
                                        </dl>
                                        <footer class="mt-4 flex items-center justify-between">
                                            <input type="checkbox" name="ids[]" value="{{ $suggestion->id }}" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggle({{ $suggestion->id }}, $event.target.checked)" :checked="selectedIds.includes({{ $suggestion->id }})">
                                            <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                <i class="ri-eye-line text-sm"></i>
                                                View
                                            </a>
                                        </footer>
                                    </article>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                                        <i class="ri-chat-off-line text-3xl text-slate-300"></i>
                                        <p class="mt-3 font-semibold text-slate-600">No suggestions available.</p>
                                        <p class="text-sm text-slate-500">Try changing your filters.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </form>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <p class="text-xs text-slate-500">Page {{ $suggestions->currentPage() }} of {{ $suggestions->lastPage() }}</p>
                    <div class="flex justify-center sm:ml-auto sm:justify-end">
                        {{ $suggestions->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </section>
        </section>
    </div>
</x-layouts.admin>
