@php($title = 'Financial Records')
@php($statusLabels = [
    'owing' => 'Owing',
    'pending_verification' => 'Waiting',
    'paid' => 'Settled',
])

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Financial Records</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Revenue & Collection Management</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.dues.export', request()->query()) }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-white px-6 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-md">
                        <x-heroicon-o-arrow-down-tray class="size-5 transition-transform group-hover:-translate-y-0.5" />
                        Export Excel
                    </a>
                    <a href="{{ route('admin.dues.statistics', request()->query()) }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-white px-6 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-md">
                        <x-heroicon-o-chart-bar-square class="size-5 transition-transform group-hover:scale-110" />
                        Analytics
                    </a>
                    <a href="{{ route('admin.dues.create') }}" class="flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-8 text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                        <x-heroicon-o-plus class="size-6" />
                        Issue Dues
                    </a>
                </div>
            </header>

            {{-- Summary Grid --}}
            {{-- <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                ... existing grid code ...
            </div> --}}

            {{-- Hardcoded Breakdown Section --}}
            <div class="space-y-10">
                {{-- Breakdown Table --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40">
                    <h2 class="text-xl font-semibold text-[#16136a] mb-8">Breakdown Table</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Level</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Population</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Amount</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total (GHS)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Year 1</td>
                                    <td class="px-8 py-5 text-slate-600">292</td>
                                    <td class="px-8 py-5 text-slate-600">292 × 110</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">32,120.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Year 2</td>
                                    <td class="px-8 py-5 text-slate-600">300</td>
                                    <td class="px-8 py-5 text-slate-600">300 × 70</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">21,000.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900 align-top" rowspan="3">Year 3</td>
                                    <td class="px-8 py-5 text-slate-600 border-b border-slate-100">*300</td>
                                    <td class="px-8 py-5 text-slate-600 align-middle" rowspan="3">270 × 60</td>
                                    <td class="px-8 py-5 font-bold text-slate-900 align-middle" rowspan="3">16,200.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 text-slate-600 border-b border-slate-100">LA 3 = 156</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 text-slate-600">GM 3 = 114</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900 align-top" rowspan="3">Year 4</td>
                                    <td class="px-8 py-5 text-slate-600 border-b border-slate-100">219</td>
                                    <td class="px-8 py-5 text-slate-600 align-middle" rowspan="3">81 × 40</td>
                                    <td class="px-8 py-5 font-bold text-slate-900 align-middle" rowspan="3">3,240.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 text-slate-600 border-b border-slate-100">LA 4 = 42</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 text-slate-600">GM 4 = 39</td>
                                </tr>
                                <tr class="bg-slate-50/30">
                                    <td colspan="3" class="px-8 py-6 font-bold uppercase tracking-widest text-slate-900">Grand Total</td>
                                    <td class="px-8 py-6 font-black text-xl text-[#16136a]">72,560.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- Daily Amount Generated --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40">
                    <h2 class="text-xl font-semibold text-[#16136a] mb-8 uppercase tracking-wide">Total Amount Generated Within Each Day</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Day</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Date</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Amount Collected</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Monday</td>
                                    <td class="px-8 py-5 text-slate-600">12 Jan</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">8,100.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Tuesday</td>
                                    <td class="px-8 py-5 text-slate-600">13 Jan</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">9,700.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Wednesday</td>
                                    <td class="px-8 py-5 text-slate-600">14 Jan</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">21,000.00</td>
                                </tr>
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Thursday</td>
                                    <td class="px-8 py-5 text-slate-600">15 Jan</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">29,650.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- EYE OF THE ENGINEER --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40">
                    <h2 class="text-xl font-semibold text-[#16136a] mb-2 uppercase tracking-wide">'THE EYE OF THE ENGINEER'</h2>
                    <p class="mb-8 text-sm font-semibold text-slate-400 uppercase tracking-widest">GESA (Geomatics Engineering, Land Administration and Information System and Spatial Planning Students Association)</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Day</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Date</th>
                                    <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-8 py-5 font-semibold text-slate-900">Friday</td>
                                    <td class="px-8 py-5 text-slate-600">16 Jan</td>
                                    <td class="px-8 py-5 font-bold text-slate-900">4,090.00</td>
                                </tr>
                                <tr class="bg-slate-50/30">
                                    <td colspan="2" class="px-8 py-6 font-bold uppercase tracking-widest text-slate-900">TOTAL</td>
                                    <td class="px-8 py-6 font-black text-xl text-[#16136a]">72,560.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- Filter Bar (Disabled for now) --}}
            {{-- <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40">
                ... filter bar code ...
            </section> --}}

            {{-- Main List (Disabled for now) --}}
            {{-- <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                ... main list code ...
            </section> --}}
        </div>
    </div>
</x-layouts.admin>
