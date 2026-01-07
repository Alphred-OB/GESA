@php($title = $title ?? 'Due Details')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-6 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dues.maintenance.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
                        <i class="ri-arrow-left-line text-lg"></i>
                    </a>
                    <div>
                        <p class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">
                            <i class="ri-file-list-3-line text-base" aria-hidden="true"></i>
                            Due Details
                        </p>
                        <h1 class="text-xl font-semibold text-[#16136a] mt-1">{{ $description }}</h1>
                        <p class="text-sm text-slate-500">Academic Year: {{ $academicYear }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons Row --}}
            <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100">
                {{-- Edit Amounts Button --}}
                <a href="{{ route('admin.dues.maintenance.edit-amounts', ['academic_year' => $academicYear, 'description' => $description]) }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-700">
                    <i class="ri-price-tag-3-line"></i>
                    Edit Amounts by Class/Year
                </a>
                
                {{-- Merge Button --}}
                <a href="{{ route('admin.dues.maintenance.merge-form', ['source_academic_year' => $academicYear, 'source_description' => $description]) }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    <i class="ri-git-merge-line"></i>
                    Merge Into Another
                </a>
                
                @if (!$safeToDelete)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm text-amber-700">
                        <i class="ri-lock-line mr-1"></i>
                        {{ $stats['paid'] + $stats['pending'] }} payment(s) - use Merge
                    </div>
                @endif
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('status'))
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-green-600"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-error-warning-fill text-xl text-red-600"></i>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Assigned</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">Paid</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['paid']) }}</p>
                <p class="mt-1 text-sm text-emerald-600">GHS {{ number_format($stats['collected'], 2) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Pending</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600">Owing</p>
                <p class="mt-2 text-3xl font-bold text-rose-700">{{ number_format($stats['owing']) }}</p>
            </div>
        </div>

        {{-- Students Missing This Due --}}
        @if ($missingStudents->count() > 0)
            <section class="space-y-4 rounded-3xl border border-amber-200 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                            <i class="ri-user-unfollow-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Students Missing This Due</h2>
                            <p class="text-xs text-slate-500">{{ $missingStudents->count() }} students don't have this due assigned</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.sync-missing') }}" method="POST">
                        @csrf
                        <input type="hidden" name="academic_year" value="{{ $academicYear }}">
                        <input type="hidden" name="description" value="{{ $description }}">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
                            <i class="ri-add-circle-line"></i>
                            Assign to All Missing ({{ $missingStudents->count() }})
                        </button>
                    </form>
                </div>

                <details class="group">
                    <summary class="cursor-pointer text-sm font-medium text-amber-700 hover:text-amber-800">
                        <span class="group-open:hidden">Show missing students list...</span>
                        <span class="hidden group-open:inline">Hide list</span>
                    </summary>
                    <div class="mt-4 max-h-96 overflow-y-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500">Reference #</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500">Class</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500">Year</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($missingStudents as $student)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-2">
                                            <div class="font-medium text-slate-700">{{ $student->fullname ?? $student->username }}</div>
                                            <div class="text-xs text-slate-400">{{ $student->email }}</div>
                                        </td>
                                        <td class="px-4 py-2 text-slate-600">{{ $student->index_number ?? '—' }}</td>
                                        <td class="px-4 py-2 text-slate-600">{{ $student->class ?? '—' }}</td>
                                        <td class="px-4 py-2 text-slate-600">Year {{ $student->year ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            </section>
        @endif

        {{-- Students With This Due --}}
        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <i class="ri-user-line text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Students With This Due</h2>
                    <p class="text-xs text-slate-500">{{ $dues->count() }} students have this due assigned</p>
                </div>
            </div>

            {{-- Filter tabs --}}
            <div x-data="{ filter: 'all' }" class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600'" class="rounded-lg px-4 py-2 text-sm font-medium transition">
                        All ({{ $stats['total'] }})
                    </button>
                    <button @click="filter = 'paid'" :class="filter === 'paid' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-700'" class="rounded-lg px-4 py-2 text-sm font-medium transition">
                        Paid ({{ $stats['paid'] }})
                    </button>
                    <button @click="filter = 'pending_verification'" :class="filter === 'pending_verification' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-700'" class="rounded-lg px-4 py-2 text-sm font-medium transition">
                        Pending ({{ $stats['pending'] }})
                    </button>
                    <button @click="filter = 'owing'" :class="filter === 'owing' ? 'bg-rose-600 text-white' : 'bg-rose-100 text-rose-700'" class="rounded-lg px-4 py-2 text-sm font-medium transition">
                        Owing ({{ $stats['owing'] }})
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Reference #</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Class/Year</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Due Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Payment Ref</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($dues as $due)
                                <tr x-show="filter === 'all' || filter === '{{ $due->payment_status }}'" class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-700">{{ $due->student?->fullname ?? $due->student?->username ?? 'Unknown' }}</div>
                                        <div class="text-xs text-slate-400">{{ $due->student?->email ?? '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $due->student?->index_number ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $due->student?->class ?? '—' }} · Y{{ $due->student?->year ?? '?' }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-700">GHS {{ number_format($due->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $due->due_date ? \Carbon\Carbon::parse($due->due_date)->format('M j, Y') : '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($due->payment_status === 'paid')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                <i class="ri-check-line mr-1"></i> Paid
                                            </span>
                                        @elseif ($due->payment_status === 'pending_verification')
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                <i class="ri-time-line mr-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                                <i class="ri-error-warning-line mr-1"></i> Owing
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-500">
                                        {{ $due->payment_reference ?? $due->reference_number ?? '—' }}
                                        @if ($due->payment_date)
                                            <div class="text-slate-400">{{ \Carbon\Carbon::parse($due->payment_date)->format('M j, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($due->payment_status === 'pending_verification')
                                                <a href="{{ route('admin.dues.verify-payment', $due) }}" class="rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200" title="Verify Payment">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </a>
                                            @endif
                                            

                                            @if ($due->payment_status === 'paid')
                                                <a href="{{ route('admin.dues.receipt', $due) }}" class="rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-200" title="View Receipt">
                                                    <i class="ri-file-download-line"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-500">No dues found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- Back Button --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Maintenance
            </a>
        </div>
    </div>
</x-layouts.admin>
