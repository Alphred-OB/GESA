@php
    $title = 'Financial Analytics';
    $stats = $stats ?? [];

    // Chart Data Preparation
    $chartStatusLabels = ['Revenue Collected', 'Awaiting Action'];
    $chartStatusData = [
        (float) data_get($stats, 'totals.paid_amount', 0),
        (float) data_get($stats, 'totals.outstanding_amount', 0),
    ];

    $classBreakdownForChart = collect(data_get($stats, 'breakdowns.classes', []))->take(10);
    $chartClassLabels = $classBreakdownForChart->pluck('label')->values();
    $chartClassData = $classBreakdownForChart->pluck('amount')->map(fn ($v) => (float) $v)->values();

    // Lead Metrics
    $bestClass = data_get($stats, 'leaders.best_class');
    $rate = (float) data_get($stats, 'totals.collection_rate', 0);
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Financial Analytics</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Revenue Insights & Collection Trends</p>
                </div>
                <a href="{{ route('admin.dues.index') }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-white px-6 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-md">
                    <x-heroicon-o-arrow-left class="size-5 transition-transform group-hover:-translate-x-1" />
                    Back to Records
                </a>
            </header>

            {{-- Filters --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40">
                <form method="GET" class="grid gap-6 md:grid-cols-4 lg:grid-cols-5">
                    <div>
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Academic Year</label>
                        <select name="academic_year" class="h-14 w-full rounded-2xl border-none bg-slate-50 px-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Years</option>
                            @foreach ($filtersMeta['academic_years'] as $yearOption)
                                <option value="{{ $yearOption }}" @selected(($filters['academic_year'] ?? '') === $yearOption)>{{ $yearOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Programme</label>
                        <select name="class" class="h-14 w-full rounded-2xl border-none bg-slate-50 px-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Programmes</option>
                            @foreach ($filtersMeta['classes'] as $classOption)
                                <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Year level</label>
                        <select name="year" class="h-14 w-full rounded-2xl border-none bg-slate-50 px-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Years</option>
                            @foreach ($filtersMeta['years'] as $yearOption)
                                <option value="{{ $yearOption }}" @selected(($filters['year'] ?? '') == $yearOption)>Year {{ $yearOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2 md:col-span-2 lg:col-span-2">
                        <button type="submit" class="h-14 flex-1 rounded-2xl bg-[#16136a] text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                            Apply Analysis
                        </button>
                        <a href="{{ route('admin.dues.statistics') }}" class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                            <x-heroicon-o-arrow-path class="size-6" />
                        </a>
                    </div>
                </form>
            </section>

            {{-- Metric Grid --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Total Revenue</p>
                        <p class="mt-4 text-4xl font-semibold text-emerald-400">GHS {{ number_format((float) data_get($stats, 'totals.paid_amount', 0), 2) }}</p>
                        <p class="mt-2 text-xs font-semibold text-white/40 italic">From {{ number_format(data_get($stats, 'totals.paid_count', 0)) }} verified transactions</p>
                    </div>
                    <x-heroicon-o-currency-dollar class="absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12 size-5" />
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Awaiting Collection</p>
                    <p class="mt-4 text-4xl font-semibold text-rose-500">GHS {{ number_format((float) data_get($stats, 'totals.outstanding_amount', 0), 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Across unsettled invoices</p>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Collection Rate</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $rate }}%</p>
                    <div class="mt-3 h-3 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full bg-[#16136a] transition-all duration-1000 shadow-[0_0_20px_rgba(22,19,106,0.2)]" style="width: {{ $rate }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                {{-- Collection Mix Chart --}}
                <article class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a] mb-8">Revenue Mix</h2>
                    <div class="relative h-[300px]">
                        <canvas id="dues-status-pie"></canvas>
                    </div>
                </article>

                {{-- Programme Breakdown --}}
                <article class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a] mb-8">Top Programmes</h2>
                    <div class="relative h-[300px]">
                        <canvas id="dues-class-bar"></canvas>
                    </div>
                </article>
            </div>

            {{-- Leaders & Deep Dive --}}
            <div class="grid gap-8 lg:grid-cols-3">
                <article class="lg:col-span-2 rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a] mb-8">Performance Leaderboard</h2>
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach (collect(data_get($stats, 'breakdowns.classes', []))->take(6) as $class)
                            <div class="group relative overflow-hidden rounded-3xl border border-slate-50 bg-slate-50/50 p-6 transition-all hover:bg-white hover:shadow-lg hover:shadow-slate-200/20">
                                <div class="relative z-10 flex items-center justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-semibold text-[#16136a] uppercase tracking-tight">{{ $class['label'] }}</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-400">GHS {{ number_format((float) $class['amount'], 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-emerald-500">{{ $class['share'] }}%</p>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Share</p>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500/20 transition-all group-hover:h-full group-hover:bg-emerald-500/5" style="width: {{ $class['share'] }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <div class="space-y-6">
                    <article class="relative overflow-hidden rounded-[2.5rem] bg-emerald-500 p-8 text-white shadow-xl shadow-emerald-500/20 h-full flex flex-col justify-center">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-white/50 mb-6 italic">Collection Leader</p>
                        @if ($bestClass)
                            <p class="text-3xl font-semibold leading-tight">{{ $bestClass['label'] }}</p>
                            <p class="mt-2 text-sm font-semibold text-white/60">Contributed GHS {{ number_format((float) $bestClass['amount'], 2) }}</p>
                        @else
                            <p class="text-xl font-semibold italic text-white/40">Analysis pending...</p>
                        @endif
                        <x-heroicon-o-star class="absolute -right-2 -bottom-2 text-8xl text-white/10 size-5" />
                    </article>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js failed to load');
                return;
            }

            const pieCtx = document.getElementById('dues-status-pie');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartStatusLabels) !!},
                        datasets: [{
                            data: {!! json_encode($chartStatusData) !!},
                            backgroundColor: ['#10b981', '#f43f5e'],
                            hoverOffset: 15,
                            borderWidth: 0,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: { size: 10, weight: 'bold' }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            const barCtx = document.getElementById('dues-class-bar');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartClassLabels) !!},
                        datasets: [{
                            label: 'Revenue',
                            data: {!! json_encode($chartClassData) !!},
                            backgroundColor: '#16136a',
                            borderRadius: 10,
                            barThickness: 20
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' } } },
                            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 9, weight: 'bold' } } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
