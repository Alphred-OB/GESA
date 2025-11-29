@php
    $title = 'Dues performance analytics';
    $stats = $stats ?? [];
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-pie-chart-2-line text-base" aria-hidden="true"></i>
                        Dues analytics
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-[#16136a]">Revenue &amp; collection insights</h1>
                    <p class="text-sm text-slate-600">Track dues inflows, top-performing cohorts, and outstanding balances.</p>
                </div>
                <a href="{{ route('admin.dues.index', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#16136a]/20 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:bg-white/90">
                    <i class="ri-arrow-left-line text-base"></i>
                    Back to dues
                </a>
            </div>
        </header>

        <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 md:grid-cols-5">
                @foreach (request()->except(['search', 'academic_year', 'status', 'class', 'year']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</label>
                    <select name="academic_year" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        <option value="">All years</option>
                        @foreach ($filtersMeta['academic_years'] as $yearOption)
                            <option value="{{ $yearOption }}" @selected(($filters['academic_year'] ?? '') === $yearOption)>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <select name="status" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        <option value="">All statuses</option>
                        @foreach ($filtersMeta['statuses'] as $statusValue => $statusLabel)
                            <option value="{{ $statusValue }}" @selected(($filters['status'] ?? '') === $statusValue)>{{ $statusLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Class</label>
                    <select name="class" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        <option value="">All classes</option>
                        @foreach ($filtersMeta['classes'] as $classOption)
                            <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year level</label>
                    <select name="year" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        <option value="">All years</option>
                        @foreach ($filtersMeta['years'] as $yearOption)
                            <option value="{{ $yearOption }}" @selected(($filters['year'] ?? '') == $yearOption)>Year {{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end justify-end gap-3 md:col-span-5">
                    <a href="{{ route('admin.dues.statistics') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 transition hover:bg-slate-50">Reset</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-filter-3-line text-base"></i>
                        Apply
                    </button>
                </div>
            </form>

            <div class="grid gap-4 md:grid-cols-2">
                <article class="space-y-4 rounded-3xl border border-[#16136a]/15 bg-[#16136a] p-6 text-white shadow-lg shadow-[#16136a]/20">
                    <header class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Total paid</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) data_get($stats, 'totals.paid_amount', 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                            <i class="ri-coins-line text-2xl"></i>
                        </span>
                    </header>
                    <p class="text-xs uppercase tracking-[0.3em] text-white/70">Collected invoices: {{ number_format((int) data_get($stats, 'totals.paid_count', 0)) }}</p>
                </article>

                <article class="space-y-4 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-900 shadow-lg shadow-amber-200/50">
                    <header class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-600">Outstanding</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) data_get($stats, 'totals.outstanding_amount', 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100">
                            <i class="ri-alert-line text-2xl"></i>
                        </span>
                    </header>
                    <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Invoices pending / owing: {{ number_format((int) data_get($stats, 'totals.invoice_count', 0) - (int) data_get($stats, 'totals.paid_count', 0)) }}</p>
                </article>
            </div>

            @php
                $paidAmount = (float) data_get($stats, 'totals.paid_amount', 0);
                $outstandingAmount = (float) data_get($stats, 'totals.outstanding_amount', 0);
                $totalAmount = $paidAmount + $outstandingAmount;
                $paidShare = $totalAmount > 0 ? round(($paidAmount / $totalAmount) * 100, 1) : 0;
                $outstandingShare = $totalAmount > 0 ? round(($outstandingAmount / $totalAmount) * 100, 1) : 0;
            @endphp

            <div
                x-data="{ paid: {{ $paidShare }}, outstanding: {{ $outstandingShare }} }"
                class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">
                    <span>Paid vs outstanding (amount)</span>
                    <span class="flex items-center gap-3 text-[11px] font-normal normal-case tracking-normal text-slate-600">
                        <span class="inline-flex items-center gap-1">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            Paid {{ $paidShare }}%
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <span class="inline-block h-2 w-2 rounded-full bg-amber-400"></span>
                            Outstanding {{ $outstandingShare }}%
                        </span>
                    </span>
                </div>
                <div class="mt-3 flex h-2 w-full overflow-hidden rounded-full bg-white">
                    <div class="h-full bg-emerald-500" :style="{ width: (paid > 0 ? paid : 0) + '%' }"></div>
                    <div class="h-full bg-amber-400" :style="{ width: (100 - (paid > 0 ? paid : 0)) + '%' }"></div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Collections mix</h2>
                    <p class="mt-1 text-xs text-slate-500">Visual split between paid and outstanding amounts.</p>
                    <div class="mt-4">
                        <canvas id="dues-status-pie" class="h-48 w-full"></canvas>
                    </div>
                </article>
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Top classes by paid amount</h2>
                    <p class="mt-1 text-xs text-slate-500">Bar chart of classes contributing most to collections.</p>
                    <div class="mt-4">
                        <canvas id="dues-class-bar" class="h-48 w-full"></canvas>
                    </div>
                </article>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Best class</h2>
                    @if (data_get($stats, 'leaders.best_class'))
                        <p class="mt-3 text-xl font-semibold text-[#16136a]">{{ data_get($stats, 'leaders.best_class.label') }}</p>
                        <p class="text-sm text-slate-500">Collected GHS {{ number_format((float) data_get($stats, 'leaders.best_class.amount'), 2) }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500">No data yet.</p>
                    @endif
                </article>
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Best year</h2>
                    @if (data_get($stats, 'leaders.best_year'))
                        <p class="mt-3 text-xl font-semibold text-[#16136a]">{{ data_get($stats, 'leaders.best_year.label') }}</p>
                        <p class="text-sm text-slate-500">Collected GHS {{ number_format((float) data_get($stats, 'leaders.best_year.amount'), 2) }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500">No data yet.</p>
                    @endif
                </article>
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Best class &amp; year</h2>
                    @if (data_get($stats, 'leaders.best_class_year'))
                        <p class="mt-3 text-xl font-semibold text-[#16136a]">{{ data_get($stats, 'leaders.best_class_year.label') }}</p>
                        <p class="text-sm text-slate-500">Collected GHS {{ number_format((float) data_get($stats, 'leaders.best_class_year.amount'), 2) }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500">No data yet.</p>
                    @endif
                </article>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Class performance</h2>
                    <ul class="mt-4 space-y-3">
                        @forelse (data_get($stats, 'breakdowns.classes', []) as $class)
                            <li
                                x-data="{ share: {{ (float) ($class['share'] ?? 0) }} }"
                                class="relative overflow-hidden rounded-2xl border border-slate-200/70 bg-slate-50/70 px-3 py-2 text-sm"
                            >
                                <div class="pointer-events-none absolute inset-0">
                                    <div class="h-full bg-emerald-50" :style="{ width: (share > 0 ? share : 0) + '%' }"></div>
                                </div>
                                <div class="relative flex items-center justify-between">
                                    <span class="font-semibold text-slate-700">{{ $class['label'] }}</span>
                                    <span class="text-xs text-slate-600">GHS {{ number_format((float) $class['amount'], 2) }} · {{ $class['share'] }}%</span>
                                </div>
                            </li>
                        @empty
                            <li class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-3 py-6 text-center text-sm text-slate-500">No paid dues yet.</li>
                        @endforelse
                    </ul>
                </article>
                <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Year performance</h2>
                    <ul class="mt-4 space-y-3">
                        @forelse (data_get($stats, 'breakdowns.years', []) as $year)
                            <li
                                x-data="{ share: {{ (float) ($year['share'] ?? 0) }} }"
                                class="relative overflow-hidden rounded-2xl border border-slate-200/70 bg-slate-50/70 px-3 py-2 text-sm"
                            >
                                <div class="pointer-events-none absolute inset-0">
                                    <div class="h-full bg-emerald-50" :style="{ width: (share > 0 ? share : 0) + '%' }"></div>
                                </div>
                                <div class="relative flex items-center justify-between">
                                    <span class="font-semibold text-slate-700">{{ $year['label'] }}</span>
                                    <span class="text-xs text-slate-600">GHS {{ number_format((float) $year['amount'], 2) }} · {{ $year['share'] }}%</span>
                                </div>
                            </li>
                        @empty
                            <li class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-3 py-6 text-center text-sm text-slate-500">No paid dues yet.</li>
                        @endforelse
                    </ul>
                </article>
            </div>

            @php
                $collectionRate = (float) data_get($stats, 'totals.collection_rate', 0);
            @endphp
            <div class="rounded-3xl border border-slate-200/80 bg-slate-50/80 p-6 text-sm text-slate-600">
                <p class="font-semibold text-[#16136a]">Collection rate</p>
                <p class="mt-2 text-3xl font-semibold text-[#16136a]">{{ number_format($collectionRate, 2) }}%</p>
                <p class="mt-2 text-xs text-slate-500">Share of invoices marked as paid across current filters.</p>

                <div
                    x-data="{ rate: {{ $collectionRate }} }"
                    class="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/70"
                    aria-hidden="true"
                >
                    <div class="h-full rounded-full bg-[#16136a]" :style="{ width: (rate > 0 ? rate : 0) + '%' }"></div>
                </div>

                <p class="mt-4 text-xs text-slate-500">Class with lowest paid amount: <strong>{{ data_get($stats, 'leaders.lowest_class.label', '—') }}</strong> (GHS {{ number_format((float) data_get($stats, 'leaders.lowest_class.amount', 0), 2) }})</p>
            </div>
        </section>
    </div>

    @php
        $chartStatusLabels = ['Paid', 'Outstanding'];
        $chartStatusData = [
            (float) data_get($stats, 'totals.paid_amount', 0),
            (float) data_get($stats, 'totals.outstanding_amount', 0),
        ];

        $classBreakdownForChart = collect(data_get($stats, 'breakdowns.classes', []))->take(8);
        $chartClassLabels = $classBreakdownForChart->pluck('label')->values();
        $chartClassData = $classBreakdownForChart->pluck('amount')->map(fn ($v) => (float) $v)->values();
    @endphp

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pieEl = document.getElementById('dues-status-pie');
                const barEl = document.getElementById('dues-class-bar');

                const statusLabels = @json($chartStatusLabels);
                const statusData = @json($chartStatusData);
                const classLabels = @json($chartClassLabels);
                const classData = @json($chartClassData);

                if (pieEl && window.Chart && statusData.some(function (v) { return v > 0; })) {
                    new Chart(pieEl.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusData,
                                backgroundColor: ['#16136a', '#f59e0b'],
                                borderColor: ['#16136a', '#f59e0b'],
                                borderWidth: 1,
                            }],
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: { size: 11 },
                                    },
                                },
                            },
                            cutout: '60%',
                        },
                    });
                }

                if (barEl && window.Chart && classLabels.length > 0) {
                    new Chart(barEl.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: classLabels,
                            datasets: [{
                                data: classData,
                                backgroundColor: '#16136a',
                                borderRadius: 6,
                            }],
                        },
                        options: {
                            plugins: {
                                legend: { display: false },
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        font: { size: 10 },
                                    },
                                },
                                y: {
                                    ticks: {
                                        font: { size: 10 },
                                    },
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                }
            });
        </script>
    @endpush
</x-layouts.admin>
