@php($title = 'Student dues')
@php($statusLabels = [
    'owing' => 'Owing',
    'pending_verification' => 'Pending verification',
    'paid' => 'Paid',
])

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10">
                <div class="space-y-2">
                    <div class="skeleton inline-flex h-7 w-44 items-center rounded-full bg-[#16136a]/10"></div>
                    <div class="skeleton h-8 w-72 rounded-2xl bg-slate-200"></div>
                    <div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
                </div>
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="skeleton h-10 w-36 rounded-2xl bg-slate-100"></div>
                    @endfor
                </div>
            </header>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="grid gap-4 md:grid-cols-3">
                    <article class="rounded-2xl border border-[#16136a]/20 bg-[#16136a] px-6 py-5 text-white shadow-lg shadow-[#16136a]/20">
                        <div class="space-y-3">
                            <div class="skeleton h-3 w-32 rounded-full bg-white/40"></div>
                            <div class="skeleton h-8 w-24 rounded-2xl bg-white/30"></div>
                        </div>
                        <div class="mt-4 skeleton h-3 w-40 rounded-full bg-white/25"></div>
                    </article>
                    @for ($i = 0; $i < 2; $i++)
                        <article class="rounded-2xl border border-slate-200/70 bg-slate-50/80 px-6 py-5 shadow-sm shadow-[#16136a]/5">
                            <div class="space-y-3">
                                <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                <div class="skeleton h-7 w-28 rounded-2xl bg-slate-200"></div>
                                <div class="skeleton h-3 w-40 rounded-full bg-slate-100"></div>
                            </div>
                        </article>
                    @endfor
                </div>

                <div class="mt-4 grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 md:grid-cols-6">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="space-y-2">
                            <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-10 w-full rounded-2xl bg-white"></div>
                        </div>
                    @endfor
                    <div class="md:col-span-2 flex items-end justify-end gap-3">
                        <div class="skeleton h-11 w-24 rounded-2xl bg-white"></div>
                        <div class="skeleton h-11 w-28 rounded-2xl bg-[#16136a]/10"></div>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 md:flex-row md:items-center md:justify-between">
                    <div class="skeleton h-3 w-56 rounded-full bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-9 w-20 rounded-xl bg-slate-100"></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="hidden md:block">
                        <div class="skeleton h-10 w-full bg-slate-50/80"></div>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="skeleton h-12 w-full bg-white"></div>
                        @endfor
                    </div>
                    <div class="grid gap-4 p-4 md:hidden">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="skeleton h-24 w-full rounded-2xl bg-white"></div>
                        @endfor
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <div class="skeleton h-3 w-40 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-8 w-32 rounded-2xl bg-slate-100 sm:ml-auto"></div>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-6 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 lg:flex-row lg:items-center lg:justify-between lg:p-8">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.25em] text-[#16136a] sm:text-xs">
                        <i class="ri-money-dollar-circle-line text-base" aria-hidden="true"></i>
                        Student dues
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Manage academic year dues</h1>
                    <p class="text-sm text-slate-600">Review issued dues, monitor collections, and configure amounts for each programme cohort.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="grid w-full grid-cols-1 gap-2 sm:flex sm:w-auto sm:items-center sm:gap-3">
                        <a href="{{ route('admin.dues.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#16136a]/20 bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                            <i class="ri-download-2-line text-base" aria-hidden="true"></i>
                            Export Excel
                        </a>
                        <a href="{{ route('admin.dues.statistics', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#16136a]/20 bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                            <i class="ri-pie-chart-2-line text-base" aria-hidden="true"></i>
                            Analytics
                        </a>
                        <a href="{{ route('admin.payment-settings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#16136a]/20 bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                            <i class="ri-settings-4-line text-base" aria-hidden="true"></i>
                            Settings
                        </a>
                    </div>
                    <a href="{{ route('admin.dues.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 py-3.5 text-sm font-bold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90 sm:w-auto">
                        <i class="ri-add-line text-lg"></i>
                        New due
                    </a>
                </div>
            </header>

            <section x-data="{ showStats: true }" class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Summary metrics</p>
                    <button
                        type="button"
                        @click="showStats = !showStats"
                        :aria-pressed="showStats ? 'true' : 'false'"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50"
                    >
                        <i class="ri-bar-chart-2-line text-base" aria-hidden="true"></i>
                        <span x-text="showStats ? 'Hide summary' : 'Show summary'"></span>
                    </button>
                </div>

                <div x-show="showStats" x-transition.opacity.duration.150ms class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <article class="relative overflow-hidden rounded-2xl border border-[#16136a]/20 bg-[#16136a] px-6 py-5 text-white shadow-lg shadow-[#16136a]/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-white/70">Total invoices</p>
                        <p class="mt-2 text-3xl font-bold sm:text-4xl">{{ number_format($totals['count'] ?? 0) }}</p>
                        <p class="mt-2 text-[10px] uppercase tracking-[0.2em] text-white/50">Across selected filters</p>
                        <i class="ri-file-list-3-line absolute -bottom-2 -right-2 text-6xl text-white/5 opacity-20" aria-hidden="true"></i>
                    </article>

                    <article class="rounded-2xl border border-slate-200/70 bg-slate-50/80 px-6 py-5 shadow-sm shadow-[#16136a]/5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-slate-500">Outstanding balance</p>
                        <p class="mt-2 text-2xl font-bold text-[#16136a] sm:text-3xl">GHS {{ number_format((float) ($totals['outstanding'] ?? 0), 2) }}</p>
                        <p class="mt-1 text-[10px] leading-relaxed text-slate-400">Sum of owing & pending verification.</p>
                    </article>

                    <article class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-5 text-emerald-900 shadow-sm shadow-emerald-100/70">
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-emerald-700">Total collected</p>
                        <p class="mt-2 text-2xl font-bold sm:text-3xl">GHS {{ number_format((float) ($totals['collected'] ?? 0), 2) }}</p>
                        <p class="mt-1 text-[10px] leading-relaxed text-emerald-600/70">Verified & active payments.</p>
                    </article>

                    <article class="rounded-2xl border border-slate-200/70 bg-white px-6 py-5 shadow-sm shadow-[#16136a]/5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-slate-500">Total billed</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900 sm:text-3xl">GHS {{ number_format((float) ($totals['total'] ?? 0), 2) }}</p>
                        <p class="mt-1 text-[10px] leading-relaxed text-slate-400">Total value of all invoices.</p>
                    </article>
                </div>

                <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 md:grid-cols-6">
                    @foreach (request()->except(['search', 'academic_year', 'status', 'class', 'year', 'per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="md:col-span-2 flex flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search student</label>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, username, email, index number" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</label>
                        <div class="relative">
                            <select name="academic_year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All years</option>
                                @foreach ($filtersMeta['academic_years'] as $yearOption)
                                    <option value="{{ $yearOption }}" @selected(($filters['academic_year'] ?? '') === $yearOption)>{{ $yearOption }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                        <div class="relative">
                            <select name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All statuses</option>
                                @foreach ($filtersMeta['statuses'] as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}" @selected(($filters['status'] ?? '') === $statusValue)>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Class</label>
                        <div class="relative">
                            <select name="class" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All classes</option>
                                @foreach ($filtersMeta['classes'] as $classOption)
                                    <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year level</label>
                        <div class="relative">
                            <select name="year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All years</option>
                                @foreach ($filtersMeta['years'] as $yearOption)
                                    <option value="{{ $yearOption }}" @selected(($filters['year'] ?? '') == $yearOption)>Year {{ $yearOption }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="md:col-span-2 md:col-start-5 md:row-start-2 flex items-end justify-end gap-3 sm:items-center sm:justify-end">
                        <a href="{{ route('admin.dues.index') }}" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 sm:w-auto">Reset</a>
                        <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90 sm:w-auto">
                            <i class="ri-filter-3-line text-base"></i>
                            Apply
                        </button>
                    </div>
                </form>

                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <p class="font-semibold">Showing {{ $dues->firstItem() ?? 0 }}–{{ $dues->lastItem() ?? 0 }} of {{ $dues->total() }} dues</p>
                    <form method="GET" class="flex items-center gap-2">
                        @foreach (request()->except(['per_page', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label for="per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                        <select id="per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <table class="hidden min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600 md:table">
                        <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-5 py-2.5">Student</th>
                                <th class="px-5 py-2.5">Reference ID</th>
                                <th class="px-5 py-2.5">Academic year</th>
                                <th class="px-5 py-2.5">Amount</th>
                                <th class="px-5 py-2.5">Due date</th>
                                <th class="px-5 py-2.5">Status</th>
                                <th class="px-5 py-2.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($dues as $due)
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-semibold text-slate-900">{{ $due->student?->fullname ?? $due->student?->username ?? 'Student #' . $due->student_id }}</span>
                                            <span class="text-xs text-slate-500">{{ $due->student?->email ?? 'No email' }}</span>
                                            <span class="text-xs text-slate-400">{{ $due->student?->class ?? '—' }} · {{ $due->student?->year ? 'Year ' . $due->student?->year : '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-slate-600">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</td>
                                    <td class="px-5 py-3 text-xs text-slate-500">{{ $due->academic_year }}</td>
                                    <td class="px-5 py-3 text-sm font-semibold text-slate-900">GHS {{ number_format((float) $due->amount, 2) }}</td>
                                    <td class="px-5 py-3 text-xs text-slate-500">{{ optional($due->due_date)->format('M j, Y') ?? $due->due_date }}</td>
                                    <td class="px-5 py-3">
                                        @php($status = $due->payment_status)
                                        <span @class([
                                            'inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]',
                                            'bg-rose-50 text-rose-600' => $status === 'owing',
                                            'bg-amber-50 text-amber-600' => $status === 'pending_verification',
                                            'bg-emerald-50 text-emerald-700' => $status === 'paid',
                                            'bg-slate-100 text-slate-600' => ! in_array($status, array_keys($statusLabels), true),
                                        ])>
                                            {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        @if ($status === 'pending_verification')
                                            <a href="{{ route('admin.dues.verify', $due) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#16136a] px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                                                <i class="ri-checkbox-circle-line text-sm"></i>
                                                Verify
                                            </a>
                                        @elseif ($status === 'paid')
                                            <a href="{{ route('admin.dues.receipt', $due) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-100" title="Download Receipt">
                                                <i class="ri-file-download-line text-lg"></i>
                                            </a>
                                        @else
                                            <div class="text-xs text-slate-400">—</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-archive-drawer-line text-3xl text-slate-300"></i>
                                            <p class="font-semibold text-slate-600">No dues found</p>
                                            <p class="text-sm text-slate-500">Adjust your filters or issue a new due to populate this list.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="grid gap-4 md:hidden">
                        @forelse ($dues as $due)
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-900">{{ $due->student?->fullname ?? $due->student?->username ?? 'Student #' . $due->student_id }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-500">{{ $due->student?->class ?? '—' }} · Year {{ $due->student?->year ?? '—' }}</p>
                                    </div>
                                    @php($status = $due->payment_status)
                                    <span @class([
                                        'rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                        'bg-rose-50 text-rose-600' => $status === 'owing',
                                        'bg-amber-50 text-amber-600' => $status === 'pending_verification',
                                        'bg-emerald-50 text-emerald-700' => $status === 'paid',
                                    ])>
                                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-4 rounded-xl bg-slate-50/50 p-3">
                                    <div class="space-y-0.5">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Amount</p>
                                        <p class="text-sm font-bold text-[#16136a]">GHS {{ number_format((float) $due->amount, 2) }}</p>
                                    </div>
                                    <div class="space-y-0.5 text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Due Date</p>
                                        <p class="text-[11px] font-semibold text-slate-700">{{ optional($due->due_date)->format('M d, Y') ?? '—' }}</p>
                                    </div>
                                </div>

                                <footer class="mt-4 flex flex-col gap-3">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span class="font-medium uppercase tracking-widest">Ref: {{ $due->payment_reference ?? $due->reference_number ?? '—' }}</span>
                                        <span class="font-medium">{{ $due->academic_year }}</span>
                                    </div>
                                    @if ($status === 'pending_verification')
                                        <a href="{{ route('admin.dues.verify', $due) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#16136a] py-3 text-xs font-bold uppercase tracking-widest text-white shadow-md transition-transform active:scale-95">
                                            <i class="ri-checkbox-circle-line text-sm"></i>
                                            Verify Payment
                                        </a>
                                    @elseif ($status === 'paid')
                                        <a href="{{ route('admin.dues.receipt', $due) }}" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 text-[11px] font-bold uppercase tracking-widest text-emerald-700 shadow-sm transition hover:bg-emerald-100/50">
                                            <i class="ri-file-download-line text-lg"></i>
                                            Download Receipt
                                        </a>
                                    @endif
                                </footer>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/60 p-10 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <i class="ri-archive-drawer-line text-3xl"></i>
                                </div>
                                <p class="mt-4 font-bold text-slate-600">No dues found</p>
                                <p class="mt-1 text-xs text-slate-400">Adjust filters or issue a new due.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <p class="text-xs text-slate-500">Page {{ $dues->currentPage() }} of {{ $dues->lastPage() }}</p>
                    <div class="flex justify-center sm:ml-auto sm:justify-end">
                        {{ $dues->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layouts.admin>
