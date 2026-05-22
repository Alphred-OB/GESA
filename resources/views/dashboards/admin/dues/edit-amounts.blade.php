@php($title = 'Edit Due Amounts')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-6xl space-y-6 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $academicYear, 'description' => $description]) }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
                    <x-heroicon-o-arrow-left class="size-5" />
                </a>
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-purple-700">
                        <x-heroicon-o-tag class="size-5" aria-hidden="true" />
                        Edit Amounts
                    </p>
                    <h1 class="text-xl font-semibold text-[#16136a] mt-1">{{ $description }}</h1>
                    <p class="text-sm text-slate-500">Academic Year: {{ $academicYear }} · Default: GHS {{ number_format($defaultAmount, 2) }}</p>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('status'))
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <div class="flex items-center gap-3">
                    <x-heroicon-s-check-circle class="size-6 text-green-600" />
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <x-heroicon-s-exclamation-triangle class="size-6 text-red-600" />
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Info --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
            <x-heroicon-o-information-circle class="mr-1 size-5" />
            <strong>Instructions:</strong> Enter the amounts for each class/year, then click the big <strong style="color: #7c3aed;">SAVE ALL CHANGES</strong> button at the bottom.
            <strong>ALL dues (including paid/pending)</strong> will be updated. Future students will also get these amounts.
        </div>

        {{-- Form wrapping the entire matrix --}}
        <form action="{{ route('admin.dues.maintenance.update-all-amounts') }}" method="POST" id="amounts-form">
            @csrf
            <input type="hidden" name="academic_year" value="{{ $academicYear }}">
            <input type="hidden" name="description" value="{{ $description }}">

            {{-- Class & Year Matrix --}}
            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Class & Year Amount Matrix</h2>
                        <p class="text-sm text-slate-600">Set amounts for each class/year. Click "Save All" when done.</p>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 sm:mx-0">
                    <div class="inline-block min-w-full align-middle px-6 sm:px-0">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                            <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">
                                <tr>
                                    <th class="px-3 py-4 first:rounded-tl-2xl">Class / Year</th>
                                    @foreach ($allYears as $year)
                                        <th class="px-3 py-4 text-center">Year {{ $year }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($allClasses as $class)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <th scope="row" class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-[#16136a] uppercase tracking-wide align-middle">
                                            {{ $class }}
                                        </th>
                                        @foreach ($allYears as $year)
                                            @php($cellData = $classYearMatrix[$class][$year] ?? null)
                                            <td class="px-2 py-3 align-middle">
                                                <div class="relative">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0" 
                                                           name="amounts[{{ $class }}][{{ $year }}]"
                                                           value="{{ $cellData['amount'] ?? $defaultAmount }}"
                                                           class="h-10 w-full min-w-[90px] rounded-lg border border-slate-200 bg-white px-2 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/30">
                                                    <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-[9px] font-semibold text-slate-300">GHS</span>
                                                </div>
                                                
                                                {{-- Stats --}}
                                                <div class="mt-1 text-[10px]">
                                                    @if (($cellData['student_count'] ?? 0) > 0)
                                                        <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $academicYear, 'description' => $description, 'class' => $class, 'year' => $year]) }}" 
                                                           class="hover:underline">
                                                            <span class="text-blue-600 font-medium">{{ $cellData['student_count'] }} students</span>
                                                            @if (($cellData['paid_count'] ?? 0) > 0)
                                                                <span class="text-emerald-600 ml-1">({{ $cellData['paid_count'] }} paid)</span>
                                                            @endif
                                                        </a>
                                                    @else
                                                        <span class="italic text-slate-400">no students yet</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- MEGA SAVE BUTTON --}}
            <div class="sticky bottom-4 z-10 flex justify-center pt-4">
                <button type="submit" 
                        onclick="return confirm('Save all amount changes?\n\nThis will update ALL dues (including paid and pending) with the amounts shown in the matrix.\n\nFuture students will also get these amounts.')"
                        class="inline-flex items-center gap-3 rounded-2xl bg-purple-600 px-10 py-4 text-lg font-semibold text-white shadow-xl shadow-purple-500/30 transition hover:bg-purple-700 hover:shadow-2xl hover:shadow-purple-500/40 active:scale-[0.98]">
                    <x-heroicon-s-arrow-down-on-square class="size-7" />
                    SAVE ALL CHANGES
                </button>
            </div>
        </form>

        {{-- Legend --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
            <p class="font-semibold mb-2">Legend:</p>
            <div class="flex flex-wrap gap-4">
                <span><span class="text-blue-600 font-medium">X students</span> = Total with this due</span>
                <span><span class="text-emerald-600">(X paid)</span> = Already paid (will also be updated)</span>
                <span><span class="italic text-slate-400">no students yet</span> = For future registrations</span>
            </div>
        </div>

        {{-- Back Buttons --}}
        <div class="flex justify-center gap-3">
            <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $academicYear, 'description' => $description]) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <x-heroicon-o-arrow-left class="size-5" />
                Back to Details
            </a>
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <x-heroicon-o-squares-2x2 class="size-5" />
                Maintenance Home
            </a>
        </div>
    </div>
</x-layouts.admin>
