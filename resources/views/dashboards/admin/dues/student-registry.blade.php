@php($title = 'Student Dues Registry')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                    <i class="ri-team-line text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-[#16136a]">Student Dues Registry</h1>
                    <p class="text-sm text-slate-500">Master list of students and their total outstanding balances.</p>
                </div>
            </div>
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Maintenance
            </a>
        </header>

        {{-- Filters --}}
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.dues.maintenance.registry') }}" method="GET" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Search Student</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID..." 
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Class</label>
                    <select name="class" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c }}" {{ request('class') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Year</label>
                    <select name="year" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Years</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.dues.maintenance.registry') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Student Table --}}
        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Class/Year</th>
                            <th class="px-4 py-3 text-center">Active Dues</th>
                            <th class="px-4 py-3 text-center">Owing</th>
                            <th class="px-4 py-3 text-right">Paid Total</th>
                            <th class="px-4 py-3 text-right">Net Balance</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($students as $student)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">{{ $student->fullname ?? $student->username }}</div>
                                    <div class="text-[10px] text-slate-400">ID: {{ $student->user_id }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-slate-600">{{ $student->class }}</span>
                                    <span class="text-slate-400 text-xs">· Year {{ $student->year }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-medium">{{ $student->dues_count }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($student->owing_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">
                                            {{ $student->owing_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-emerald-600 font-medium">
                                    GHS {{ number_format($student->paid_balance, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-lg font-semibold {{ $student->outstanding_balance > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                        GHS {{ number_format($student->outstanding_balance, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.dues.maintenance.trace', ['query' => $student->user_id]) }}" 
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                        <i class="ri-eye-line"></i>
                                        Trace Dues
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">No students found matching your filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t">
                {{ $students->links() }}
            </div>
        </section>

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-xs text-blue-800">
            <i class="ri-information-line mr-1 text-base align-middle"></i>
            <strong>Pro-Tip:</strong> The "Net Balance" is the sum of all individual dues that are in 'owing' or 'pending_verification' status for that student. This is exactly what the student sees on their dashboard.
        </div>
    </div>
</x-layouts.admin>
