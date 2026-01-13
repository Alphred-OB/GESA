@php($title = 'Student Due Trace')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                    <i class="ri-user-search-line text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-500">Student Tracer</p>
                    <h1 class="text-2xl font-semibold text-[#16136a] mt-1">{{ $student->fullname ?? $student->username }}</h1>
                </div>
            </div>
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Maintenance
            </a>
        </header>

        {{-- Student Info Card --}}
        <section class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-1 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <h2 class="text-base font-bold text-slate-800 border-b pb-2">Student Profile</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Index Number</span>
                        <span class="font-semibold text-slate-800">{{ $student->index_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Class / Year</span>
                        <span class="font-semibold text-slate-800">{{ $student->class }} · Year {{ $student->year }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Email</span>
                        <span class="font-semibold text-slate-800">{{ $student->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Active</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 grid gap-4 grid-cols-2">
                <div class="rounded-3xl border border-[#16136a]/15 bg-[#16136a] p-6 text-white shadow-lg">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/60">Outstanding Balance</p>
                    <p class="text-3xl font-bold mt-2">GHS {{ number_format($stats['total_active'], 2) }}</p>
                    <p class="text-xs text-white/40 mt-1">From {{ $stats['count_active'] }} active records</p>
                </div>
                <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-sm">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">Total Paid</p>
                    <p class="text-3xl font-bold mt-2 text-emerald-800">GHS {{ number_format($stats['total_paid'], 2) }}</p>
                    <p class="text-xs text-emerald-500 mt-1">History records</p>
                </div>
            </div>
        </section>

        {{-- All Dues Table --}}
        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
            <h2 class="text-lg font-semibold text-slate-800">Full Dues History (Including Inactive)</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-center">Active?</th>
                            <th class="px-4 py-3">Academic Year</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date Assigned</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($dues as $due)
                            <tr class="{{ $due->is_active ? '' : 'bg-slate-50 text-slate-400' }} hover:bg-blue-50/30 transition">
                                <td class="px-4 py-3 text-center">
                                    @if($due->is_active)
                                        <span class="inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                                    @else
                                        <span class="inline-flex h-2 w-2 rounded-full bg-slate-300"></span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $due->academic_year }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium {{ $due->is_active ? 'text-slate-800' : 'text-slate-400' }}">{{ $due->description }}</div>
                                    @if($due->payment_notes)
                                        <div class="text-[10px] text-slate-400 italic">{{ $due->payment_notes }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">GHS {{ number_format($due->amount, 2) }}</td>
                                <td class="px-4 py-3 uppercase text-[10px] font-bold">
                                    @php($status = $due->payment_status)
                                    <span class="px-2 py-0.5 rounded-full border
                                        {{ $status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                           ($status === 'pending_verification' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-rose-50 text-rose-600 border-rose-100') }}">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">{{ $due->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.dues.edit', $due) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">No dues records found for this student.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <i class="ri-information-line mr-1"></i>
            <strong>Diagnosis:</strong> If a student has an incorrect balance, check if they have multiple <strong>Active</strong> dues for the same academic year, or if they have unpaid dues from <strong>Previous</strong> academic years that are still marked as active.
        </div>
    </div>
</x-layouts.admin>
