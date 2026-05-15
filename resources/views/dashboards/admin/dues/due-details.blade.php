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
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Total Assigned</p>
                <p class="mt-2 text-3xl font-semibold text-slate-800">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Paid</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-700">{{ number_format($stats['paid']) }}</p>
                <p class="mt-1 text-sm text-emerald-600">GHS {{ number_format($stats['collected'], 2) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-600">Pending</p>
                <p class="mt-2 text-3xl font-semibold text-amber-700">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-rose-600">Owing</p>
                <p class="mt-2 text-3xl font-semibold text-rose-700">{{ number_format($stats['owing']) }}</p>
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
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Students With This Due</h2>
                    <p class="text-xs text-slate-500">{{ $dues->count() }} students match current filters</p>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                <form action="{{ route('admin.dues.maintenance.details') }}" method="GET" class="grid gap-4 sm:grid-cols-4 lg:grid-cols-5">
                    <input type="hidden" name="academic_year" value="{{ $academicYear }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    
                    {{-- Search --}}
                    <div class="relative items-center">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ref..." 
                            class="w-full rounded-xl border-slate-200 pl-10 text-sm focus:ring-[#16136a]/20 focus:border-[#16136a]">
                    </div>

                    {{-- Class --}}
                    <select name="class" class="rounded-xl border-slate-200 text-sm focus:ring-[#16136a]/20 focus:border-[#16136a]">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c }}" {{ request('class') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>

                    {{-- Year --}}
                    <select name="year" class="rounded-xl border-slate-200 text-sm focus:ring-[#16136a]/20 focus:border-[#16136a]">
                        <option value="">All Years</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                        @endforeach
                    </select>

                    {{-- Status (Handled via URL query now for simplicity with filters) --}}
                    <select name="status" x-model="filter" class="rounded-xl border-slate-200 text-sm focus:ring-[#16136a]/20 focus:border-[#16136a]">
                        <option value="all">All Statuses</option>
                        <option value="paid">Paid</option>
                        <option value="pending_verification">Pending</option>
                        <option value="owing">Owing</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900 transition flex items-center justify-center gap-2">
                            <i class="ri-filter-3-line"></i>
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'class', 'year']))
                            <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $academicYear, 'description' => $description]) }}" 
                                class="rounded-xl bg-white border border-slate-200 px-3 py-2 text-slate-500 hover:bg-slate-50 transition" title="Clear Filters">
                                <i class="ri-refresh-line"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Filter tabs --}}
            <div x-data="{ 
                filter: '{{ request('status', 'all') }}',
                editingDue: null,
                selectedDues: [],
                showBulkModal: false,
                allSelected: false,
                toggleAll() {
                    this.allSelected = !this.allSelected;
                    if (this.allSelected) {
                        this.selectedDues = Array.from(document.querySelectorAll('.due-checkbox'))
                            .map(cb => cb.value);
                    } else {
                        this.selectedDues = [];
                    }
                },
                openEditModal(due) {
                    this.editingDue = due;
                    document.body.classList.add('overflow-hidden');
                },
                closeEditModal() {
                    this.editingDue = null;
                    document.body.classList.remove('overflow-hidden');
                }
            }" class="space-y-4">
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
                        <thead class="bg-slate-50 uppercase tracking-wider text-slate-500 text-[10px]">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]">
                                </th>
                                <th class="px-4 py-3 text-left font-semibold">Student</th>
                                <th class="px-4 py-3 text-left font-semibold">Reference #</th>
                                <th class="px-4 py-3 text-left font-semibold">Class/Year</th>
                                <th class="px-4 py-3 text-left font-semibold">Amount</th>
                                <th class="px-4 py-3 text-left font-semibold">Due Date</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Payment Info</th>
                                <th class="px-4 py-3 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($dues as $due)
                                <tr x-show="filter === 'all' || filter === '{{ $due->payment_status }}'" class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" value="{{ $due->due_id }}" x-model="selectedDues" class="due-checkbox rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]">
                                    </td>
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

                                            <button @click="openEditModal({
                                                id: {{ $due->due_id }},
                                                student: '{{ addslashes($due->student?->fullname ?? $due->student?->username ?? 'Unknown') }}',
                                                amount: {{ $due->amount }},
                                                description: '{{ addslashes($due->description) }}',
                                                due_date: '{{ $due->due_date }}'
                                            })" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200" title="Edit Due">
                                                <i class="ri-edit-line"></i>
                                            </button>
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
                {{-- Sticky Bulk Actions Bar --}}
                <div x-show="selectedDues.length > 0" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-full opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-full opacity-0"
                    class="fixed bottom-6 left-1/2 z-[90] -translate-x-1/2 w-full max-w-2xl px-4">
                    <div class="rounded-3xl border border-[#16136a]/20 bg-[#16136a] p-4 text-white shadow-2xl flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">
                                <i class="ri-checkbox-multiple-line text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold"><span x-text="selectedDues.length"></span> Students Selected</p>
                                <p class="text-[10px] text-white/60">Bulk update all marked records</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="showBulkModal = true; document.body.classList.add('overflow-hidden')" 
                                class="rounded-xl bg-white px-5 py-2.5 text-xs font-semibold text-[#16136a] shadow-lg transition hover:bg-slate-100">
                                <i class="ri-edit-box-line mr-1"></i>
                                Edit Selection
                            </button>
                            <button @click="selectedDues = []; allSelected = false" class="rounded-xl bg-slate-100/10 px-4 py-2.5 text-xs font-semibold hover:bg-white/10 transition">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bulk Edit Modal --}}
                <template x-if="showBulkModal">
                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                        <div @click="showBulkModal = false; document.body.classList.remove('overflow-hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                        
                        <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl transition-all">
                            <div class="bg-indigo-600 px-6 py-4 text-white">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold uppercase tracking-widest text-sm">Bulk Update Dues</h3>
                                    <button @click="showBulkModal = false; document.body.classList.remove('overflow-hidden')" class="rounded-lg p-1 hover:bg-white/10">
                                        <i class="ri-close-line text-xl"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-indigo-100 mt-1">Updating <span class="font-semibold underline" x-text="selectedDues.length"></span> selected records</p>
                            </div>

                            <form action="{{ route('admin.dues.maintenance.bulk-edit-individual') }}" method="POST" class="p-6 space-y-4">
                                @csrf
                                <template x-for="id in selectedDues">
                                    <input type="hidden" name="due_ids[]" :value="id">
                                </template>
                                
                                <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-[10px] text-amber-700">
                                    <i class="ri-information-line"></i>
                                    This will update the <strong>amount</strong> and/or <strong>due date</strong> for all selected students, including those who have already paid.
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-1">New Batch Amount (GHS)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">GHS</span>
                                        <input type="number" name="amount" step="0.01" min="0" placeholder="0.00" required
                                            class="w-full rounded-xl border-slate-200 pl-12 pr-4 py-2.5 text-sm focus:border-indigo-600 focus:ring-indigo-600/20">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-1">New Batch Due Date (Optional)</label>
                                    <input type="date" name="due_date" 
                                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-indigo-600 focus:ring-indigo-600/20">
                                </div>

                                <div class="flex gap-2 pt-2">
                                    <button type="button" @click="showBulkModal = false; document.body.classList.remove('overflow-hidden')" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                                        Exit
                                    </button>
                                    <button type="submit" class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">
                                        Apply to All
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- Edit Single Modal --}}
                <template x-if="editingDue">
                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                        <div @click="closeEditModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                        
                        <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl transition-all">
                            <div class="bg-[#16136a] px-6 py-4 text-white">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold">Edit Due Amount</h3>
                                    <button @click="closeEditModal()" class="rounded-lg p-1 hover:bg-white/10">
                                        <i class="ri-close-line text-xl"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-white/70" x-text="editingDue.student"></p>
                            </div>

                            <form :action="'{{ route('admin.dues.maintenance.index') }}' + '/' + editingDue.id" method="POST" class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <input type="hidden" name="description" :value="editingDue.description">
                                
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Due Amount (GHS)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">GHS</span>
                                        <input type="number" name="amount" step="0.01" min="0" :value="editingDue.amount" required
                                            class="w-full rounded-xl border-slate-200 pl-12 pr-4 py-2.5 text-sm focus:border-[#16136a] focus:ring-[#16136a]/20">
                                    </div>
                                    <p class="mt-1 text-[10px] text-amber-600">
                                        <i class="ri-error-warning-line"></i>
                                        Note: Changing paid dues will NOT affect payment logs, only the recorded amount.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Due Date</label>
                                    <input type="date" name="due_date" :value="editingDue.due_date" 
                                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm focus:border-[#16136a] focus:ring-[#16136a]/20">
                                </div>

                                <div class="flex gap-2 pt-2">
                                    <button type="button" @click="closeEditModal()" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="flex-1 rounded-xl bg-[#16136a] px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 hover:bg-[#16136a]/90 transition">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>
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
