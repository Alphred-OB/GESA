@php($title = 'Create academic year due')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-money-dollar-circle-line text-base" aria-hidden="true"></i>
                Issue new due
            </p>
            <h1 class="text-3xl font-semibold text-[#16136a]">Configure dues for the academic year</h1>
            <p class="text-sm text-slate-600">Set the base amount and fine-tune class/year variations. All existing and future students will inherit these dues automatically.</p>
        </header>

        <form method="POST" action="{{ route('admin.dues.store') }}" 
            x-data="{ 
                baseAmount: '{{ old('base_amount', '0.00') }}', 
                amounts: @js(old('amounts', $matrix['values'])),
                applyToAll() {
                    if(!this.baseAmount || this.baseAmount === '') return;
                    Object.keys(this.amounts).forEach(cls => {
                        Object.keys(this.amounts[cls]).forEach(yr => {
                            this.amounts[cls][yr] = this.baseAmount;
                        });
                    });
                }
            }" 
            class="space-y-8"
        >
            @csrf

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Due details</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Description</span>
                        <input type="text" name="description" value="{{ old('description') }}" required maxlength="255" placeholder="Departmental dues 2025" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @error('description')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</span>
                        <div class="relative">
                            <input type="text" name="academic_year" value="{{ old('academic_year') }}" required placeholder="2025/2026" pattern="^\d{4}/\d{4}$" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400">YYYY/YYYY</span>
                        </div>
                        @error('academic_year')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <label class="flex flex-col gap-2 md:col-span-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Due date</span>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @error('due_date')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Base amount (GHS)</span>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="0" name="base_amount" x-model="baseAmount" placeholder="0.00" class="h-11 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <button type="button" @click="applyToAll()" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl border border-[#16136a]/20 bg-[#16136a]/5 px-4 text-xs font-bold text-[#16136a] transition hover:bg-[#16136a] hover:text-white" title="Apply this amount to all classes below">
                                <i class="ri-refresh-line"></i>
                                <span class="hidden sm:inline">Apply to all</span>
                            </button>
                        </div>
                        <span class="text-[10px] italic text-slate-400 leading-tight">Applied wherever matrix overrides are blank.</span>
                        @error('base_amount')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Class &amp; year matrix</h2>
                        <p class="text-sm text-slate-600">Override the base amount for specific cohorts. Blank cells will inherit the base amount.</p>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 sm:mx-0">
                    <div class="inline-block min-w-full align-middle px-6 sm:px-0">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                            <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                <tr>
                                    <th class="px-3 py-4 first:rounded-tl-2xl">Class / Year</th>
                                    @foreach ($matrix['years'] as $year)
                                        <th class="px-3 py-4 text-center">Year {{ $year }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($matrix['classes'] as $class)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <th scope="row" class="whitespace-nowrap px-3 py-4 text-xs font-bold text-[#16136a] uppercase tracking-wide">{{ $class }}</th>
                                        @foreach ($matrix['years'] as $year)
                                            <td class="px-2 py-3">
                                                <div class="relative group">
                                                    <input type="number" step="0.01" min="0" 
                                                        name="amounts[{{ $class }}][{{ $year }}]" 
                                                        x-model="amounts['{{ $class }}']['{{ $year }}']"
                                                        placeholder="Base" 
                                                        class="h-10 w-full min-w-[100px] rounded-xl border border-slate-200 bg-white pl-3 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                                    <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[9px] font-bold text-slate-300 group-focus-within:text-[#16136a]/40 transition-colors">GHS</span>
                                                </div>
                                                @error("amounts.$class.$year")
                                                    <span class="text-xs text-rose-600">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <footer class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500 max-w-md">Students are automatically invoiced based on these values. New accounts inherit active dues by default.</p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-8 py-2.5 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90 active:translate-y-0">
                        <i class="ri-send-plane-line text-base"></i>
                        Issue due
                    </button>
                </div>
            </footer>
        </form>
    </div>
</x-layouts.admin>
