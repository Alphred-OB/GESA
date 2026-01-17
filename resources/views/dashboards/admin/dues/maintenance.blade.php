@php($title = 'Dues Maintenance')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                    <i class="ri-tools-line text-2xl"></i>
                </div>
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">
                        <i class="ri-settings-3-line text-base" aria-hidden="true"></i>
                        Developer Tools
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] mt-1">Dues Maintenance</h1>
                </div>
            </div>
            <p class="text-sm text-slate-600">
                Manage dues assignments, fix missing dues, and clean up duplicates. <strong>Click on any due</strong> to see detailed information.
            </p>
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

        @if (isset($loadError))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-error-warning-fill text-xl text-red-600"></i>
                    <p class="text-sm font-medium text-red-800">{{ $loadError }}</p>
                </div>
            </div>
        @endif

        {{-- Stats Overview --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="ri-user-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalStudents) }}</p>
                        <p class="text-xs text-slate-500">Total Active Students</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                        <i class="ri-file-list-3-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ $uniqueDues->count() }}</p>
                        <p class="text-xs text-slate-500">Unique Dues Types</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i class="ri-alert-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ count($missingDues) }}</p>
                        <p class="text-xs text-slate-500">Dues With Missing Students</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Search & Diagnostic Tools --}}
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Sync All --}}
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-blue-900">Sync All Students</h3>
                        <p class="text-sm text-blue-700">Assign all active dues to students who don't have them.</p>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.sync-all') }}" method="POST" onsubmit="return confirm('This will sync dues for ALL students. Continue?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 w-full justify-center">
                            <i class="ri-refresh-line"></i>
                            Sync All Students
                        </button>
                    </form>
                </div>
            </div>

            {{-- Student Dues Registry --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-emerald-900">Student Dues Registry</h3>
                        <p class="text-sm text-emerald-700">View "what the student sees" - a master list of all students and their net balances.</p>
                    </div>
                    <a href="{{ route('admin.dues.maintenance.registry') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 w-full justify-center">
                        <i class="ri-team-line"></i>
                        Open Registry
                    </a>
                </div>
            </div>

            {{-- Student Due Tracer --}}
            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-purple-900">Student Due Tracer</h3>
                        <p class="text-sm text-purple-700 italic">Investigate balance discrepancies for a specific student.</p>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.trace') }}" method="GET" class="flex gap-2">
                        <input type="text" name="query" placeholder="Name, Index #, or ID" 
                            class="flex-1 rounded-xl border-purple-200 bg-white px-4 py-2 text-sm focus:border-purple-500 focus:ring-purple-500">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-700">
                            <i class="ri-search-line"></i>
                            Trace
                        </button>
                    </form>
                </div>
            </div>

            {{-- Default Dues Config --}}
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-indigo-900">Default Dues Config</h3>
                        <p class="text-sm text-indigo-700">Manage default amounts by class/year for new student registrations.</p>
                    </div>
                    <a href="{{ route('admin.dues.maintenance.config') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 w-full justify-center">
                        <i class="ri-settings-4-line"></i>
                        Open Config Manager
                    </a>
                </div>
            </div>

            {{-- Account Management --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-rose-900">Account Management</h3>
                        <p class="text-sm text-rose-700">Delete stuck accounts, force approve pending registrations, bypass verification.</p>
                    </div>
                    <a href="{{ route('admin.dues.maintenance.accounts') }}" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 w-full justify-center">
                        <i class="ri-user-settings-line"></i>
                        Open Account Manager
                    </a>
                </div>
            </div>

            {{-- Bulk Class Fixer --}}
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5 shadow-sm md:col-span-2">
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="font-semibold text-orange-900">Bulk Class Amount Adjuster</h3>
                        <p class="text-sm text-orange-700">Change the amount for an entire class. <strong class="text-rose-600 underline">This will update PAID dues too.</strong></p>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.update-single-amount') }}" method="POST" class="grid gap-3 sm:grid-cols-5">
                        @csrf
                        <select name="description" required class="rounded-xl border-orange-200 text-sm focus:ring-orange-500">
                            <option value="">Select Due...</option>
                            @foreach($uniqueDues->pluck('description')->unique() as $desc)
                                <option value="{{ $desc }}">{{ $desc }}</option>
                            @endforeach
                        </select>
                        <select name="academic_year" required class="rounded-xl border-orange-200 text-sm focus:ring-orange-500">
                            <option value="">Select Year...</option>
                            @foreach($uniqueDues->pluck('academic_year')->unique() as $ay)
                                <option value="{{ $ay }}">{{ $ay }}</option>
                            @endforeach
                        </select>
                        <select name="class" required class="rounded-xl border-orange-200 text-sm focus:ring-orange-500">
                            <option value="">Select Class...</option>
                            @foreach($classes as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">GHS</span>
                            <input type="number" name="new_amount" step="0.01" min="0" required placeholder="0.00" 
                                class="w-full rounded-xl border-orange-200 pl-10 text-sm focus:ring-orange-500">
                        </div>
                        <button type="submit" onclick="return confirm('⚠️ WARNING: This will update EVERY student in this class, including those who have already PAID.\n\nContinue?')"
                            class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-700 justify-center">
                            <i class="ri-check-double-line"></i>
                            Update All
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- All Dues Overview (Clickable) --}}
        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i class="ri-list-check-2 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">All Due Types</h2>
                    <p class="text-xs text-slate-500">Click on any row to view details, students, and manage</p>
                </div>
            </div>

            <div class="overflow-x-auto -mx-6 sm:mx-0">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Academic Year</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 text-center">Assigned</th>
                            <th class="px-4 py-3 text-center">Paid</th>
                            <th class="px-4 py-3 text-center">Pending</th>
                            <th class="px-4 py-3 text-center">Owing</th>
                            <th class="px-4 py-3 text-right">Collected</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($uniqueDues as $due)
                            <tr class="hover:bg-blue-50/50 cursor-pointer transition" onclick="window.location='{{ route('admin.dues.maintenance.details', ['academic_year' => $due->academic_year, 'description' => $due->description]) }}'">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $due->academic_year }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-slate-800">{{ $due->description }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                        {{ $due->student_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($due->paid_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                            {{ $due->paid_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($due->pending_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                            {{ $due->pending_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($due->owing_count > 0)
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">
                                            {{ $due->owing_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-emerald-700">
                                    GHS {{ number_format($due->collected_amount ?? 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right" onclick="event.stopPropagation()">
                                    <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $due->academic_year, 'description' => $due->description]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                                        <i class="ri-eye-line"></i>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                                    No active dues found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Missing Dues Section --}}
        @if (count($missingDues) > 0)
            <section class="space-y-4 rounded-3xl border border-amber-200 bg-white p-6 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i class="ri-user-unfollow-line text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Students Missing Dues</h2>
                        <p class="text-xs text-slate-500">These dues haven't been assigned to all students yet</p>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 sm:mx-0">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Academic Year</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3 text-center">Has Due</th>
                                <th class="px-4 py-3 text-center">Missing</th>
                                <th class="px-4 py-3 text-center">Paid/Pending/Owing</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($missingDues as $due)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $due['academic_year'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $due['description'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            {{ $due['has_count'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                            {{ $due['missing_count'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs">
                                        <span class="text-emerald-600">{{ $due['paid_count'] ?? 0 }}</span> /
                                        <span class="text-amber-600">{{ $due['pending_count'] ?? 0 }}</span> /
                                        <span class="text-rose-600">{{ $due['owing_count'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.dues.maintenance.details', ['academic_year' => $due['academic_year'], 'description' => $due['description']]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                                                <i class="ri-eye-line"></i>
                                                View
                                            </a>
                                            <form action="{{ route('admin.dues.maintenance.sync-missing') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="academic_year" value="{{ $due['academic_year'] }}">
                                                <input type="hidden" name="description" value="{{ $due['description'] }}">
                                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-200">
                                                    <i class="ri-add-circle-line"></i>
                                                    Assign to Missing
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5">
                <div class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-green-600"></i>
                    <p class="text-sm font-medium text-green-800">All students have all active dues assigned. No missing dues found!</p>
                </div>
            </div>
        @endif

        {{-- Duplicate Dues Section --}}
        @if ($duplicates->count() > 0)
            <section class="space-y-4 rounded-3xl border border-red-200 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600">
                            <i class="ri-file-copy-2-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Duplicate Dues Found</h2>
                            <p class="text-xs text-slate-500">{{ $duplicates->count() }} students have the same due assigned multiple times</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.dues.maintenance.normalize-descriptions') }}" method="POST" 
                            onsubmit="return confirm('This will trim trailing/leading spaces from all due descriptions. This helps group similar-looking dues. Continue?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                <i class="ri-brush-line"></i>
                                Normalize Names
                            </button>
                        </form>
                        <form action="{{ route('admin.dues.maintenance.delete-all-duplicates') }}" method="POST" onsubmit="return confirm('This will delete ALL duplicate dues, keeping only one per student (prioritizing paid ones). Continue?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                                <i class="ri-delete-bin-line"></i>
                                Delete All Duplicates
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 sm:mx-0">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Class / Year</th>
                                <th class="px-4 py-3">Academic Year</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3 text-center">Duplicates</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($duplicates as $dup)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-700">{{ $dup['student_name'] }}</div>
                                        <div class="text-xs text-slate-400">ID: {{ $dup['student_id'] }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $dup['student_class'] ?? 'N/A' }} · Year {{ $dup['student_year'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $dup['academic_year'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $dup['description'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            {{ $dup['duplicate_count'] }}x
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('admin.dues.maintenance.delete-duplicate') }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $dup['duplicate_count'] - 1 }} duplicate(s)? The paid/first due will be kept.')">
                                            @csrf
                                            <input type="hidden" name="due_ids" value="{{ $dup['due_ids'] }}">
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                <i class="ri-delete-bin-line"></i>
                                                Remove Duplicates
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5">
                <div class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-green-600"></i>
                    <p class="text-sm font-medium text-green-800">No duplicate dues found. Each student has unique dues.</p>
                </div>
            </div>
        @endif

        {{-- Orphaned Dues Section --}}
        @if ($orphanedCount > 0)
            <section class="space-y-4 rounded-3xl border border-orange-200 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                            <i class="ri-user-unfollow-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Orphaned Dues Found</h2>
                            <p class="text-xs text-slate-500">{{ $orphanedCount }} dues belong to students who no longer exist in the system</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.delete-all-orphaned') }}" method="POST" onsubmit="return confirm('⚠️ DELETE ALL {{ $orphanedCount }} ORPHANED DUES?\n\nThis will remove dues belonging to deleted students. This action cannot be undone. Continue?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700">
                            <i class="ri-delete-bin-line"></i>
                            Delete All Orphaned
                        </button>
                    </form>
                </div>
                <div class="rounded-xl border border-orange-100 bg-orange-50/50 p-4 text-sm text-orange-800">
                    <i class="ri-information-line mr-1"></i>
                    Orphaned dues occur when a student account is deleted but their dues records remain. These records show up as "Unknown" in reports.
                </div>
            </section>
        @endif

        {{-- Back to Dues --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Dues Management
            </a>
        </div>
    </div>
</x-layouts.admin>
