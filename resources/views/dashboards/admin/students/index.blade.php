@php($title = 'Students')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <header class="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                    <i class="ri-team-line text-xs"></i>
                    Student Directory
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Manage Students</h1>
                <p class="max-w-xl text-sm font-medium text-slate-500">Track student distribution, manage accounts, and handle academic year promotions.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.students.create') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                    <i class="ri-user-add-line text-lg"></i>
                    New Student
                </a>
                <form method="POST" action="{{ route('admin.students.promote-years') }}" onsubmit="return confirm('Promote all students to the next academic year?');">
                    @csrf
                    <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-6 text-sm font-semibold text-amber-700 transition-all hover:bg-amber-100 active:scale-95">
                        <i class="ri-arrow-up-circle-line text-lg"></i>
                        Promote Year
                    </button>
                </form>
                <a href="{{ route('admin.students.export', request()->query()) }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-95">
                    <i class="ri-download-2-line text-lg"></i>
                    Export
                </a>
            </div>
        </header>

        {{-- Bento Stats Grid --}}
        <div class="mb-10 grid gap-6 md:grid-cols-12">
            {{-- Main KPI --}}
            <article class="relative overflow-hidden rounded-3xl border border-[#16136a]/20 bg-[#16136a] p-8 text-white shadow-2xl shadow-[#16136a]/20 md:col-span-4">
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm">
                            <i class="ri-team-line text-2xl text-white"></i>
                        </span>
                        <div class="text-right">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Current Total</p>
                            <p class="text-xs font-semibold text-white/70">Across all years</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-5xl font-semibold tracking-tight">{{ number_format($stats['total']) }}</p>
                        <p class="mt-2 text-sm font-medium text-white/60">Active Student Accounts</p>
                    </div>
                </div>
                <i class="ri-user-star-line absolute -bottom-6 -right-6 text-[120px] text-white/5 opacity-10"></i>
            </article>

            {{-- Class Breakdown Grid --}}
            <div class="grid gap-4 md:col-span-8 md:grid-cols-3">
                @forelse ($stats['class_breakdown'] as $classStat)
                    <article class="group rounded-3xl border border-slate-200/60 bg-white p-5 shadow-sm transition-all hover:border-[#16136a]/30 hover:shadow-md">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="max-w-[80%] space-y-1">
                                <p class="truncate text-[10px] font-semibold uppercase tracking-widest text-slate-400">{{ $classStat['name'] }}</p>
                                <p class="text-2xl font-semibold text-slate-900">{{ number_format($classStat['total']) }}</p>
                            </div>
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-slate-400 group-hover:bg-[#16136a]/5 group-hover:text-[#16136a]">
                                <i class="ri-building-line text-sm"></i>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($stats['year_buckets'] as $year)
                                <div class="flex flex-col rounded-xl bg-slate-50/80 px-3 py-2 border border-slate-100/50">
                                    <span class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Y{{ $year }}</span>
                                    <span class="text-xs font-semibold text-slate-700">{{ number_format($classStat['years'][$year] ?? 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @empty
                    <div class="col-span-full flex h-full items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-8 text-slate-400">
                        No active student data available.
                    </div>
                @endforelse
            </div>

            {{-- Graduation Stats --}}
            <article class="flex flex-col justify-between rounded-3xl border border-emerald-100 bg-emerald-50/40 p-6 md:col-span-12 lg:col-span-12">
                <div class="mb-6 flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-600/60">Alumni Directory</p>
                        <h3 class="text-xl font-semibold text-emerald-900">{{ number_format($stats['graduated_total'] ?? 0) }} Total Graduates</h3>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                        <i class="ri-graduation-cap-line text-xl"></i>
                    </span>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach (($stats['graduated_class_breakdown'] ?? []) as $gradStat)
                        @if($gradStat['total'] > 0)
                            <div class="inline-flex items-center gap-3 rounded-2xl border border-emerald-100 bg-white px-4 py-2 shadow-sm">
                                <span class="text-xs font-semibold text-slate-600">{{ $gradStat['name'] }}</span>
                                <span class="rounded-lg bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-600">{{ number_format($gradStat['total']) }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </article>
        </div>

        {{-- Filters & Directory Section --}}
        <section class="space-y-6 rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40">
            {{-- Search & Filters --}}
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-12">
                @foreach (request()->except(['search', 'class', 'year', 'status', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="lg:col-span-4">
                    <div class="relative group">
                        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name, email, or ID..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5" />
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="relative">
                        <select name="class" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                            <option value="">All Classes</option>
                            @foreach ($filterOptions['classes'] as $classOption)
                                <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="relative">
                        <select name="year" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                            <option value="">All Years</option>
                            @foreach ($filterOptions['years'] as $yearOption)
                                <option value="{{ $yearOption }}" @selected(($filters['year'] ?? '') == $yearOption)>Year {{ $yearOption }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="relative">
                        <select name="status" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                            <option value="">All Statuses</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-4 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/10 transition-all hover:-translate-y-0.5 active:scale-95">
                        <i class="ri-equalizer-line"></i>
                        Filter
                    </button>
                </div>
            </form>

            {{-- Table & Directory --}}
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-slate-50/30">
                {{-- Desktop Table --}}
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/80 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Student</th>
                                <th class="px-6 py-4">Program & Year</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($students as $student)
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[#16136a]/5 text-[15px] font-semibold text-[#16136a]">
                                                @if($student->profile_picture)
                                                    <img src="{{ Str::startsWith($student->profile_picture, ['http://', 'https://']) ? $student->profile_picture : asset('storage/' . $student->profile_picture) }}" alt="{{ $student->fullname ?? $student->username }}" class="h-full w-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($student->fullname ?? $student->username, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="truncate font-semibold text-slate-900">{{ $student->fullname ?? $student->username }}</span>
                                                <span class="truncate text-xs font-medium text-slate-400">{{ $student->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-semibold text-slate-700">{{ $student->class ?? 'Unassigned' }}</span>
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">{{ $student->year ? 'Year ' . $student->year : 'Pre-level' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            @if($student->email_verified_at)
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-wider text-emerald-600">
                                                    <i class="ri-checkbox-circle-fill"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-wider text-amber-600">
                                                    <i class="ri-time-fill"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.students.show', $student) }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition-all hover:border-[#16136a] hover:bg-[#16136a]/5 hover:text-[#16136a]" title="View Profile">
                                                <i class="ri-eye-line text-base"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition-all hover:border-[#16136a] hover:bg-[#16136a]/5 hover:text-[#16136a]" title="Edit Account">
                                                <i class="ri-pencil-line text-base"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline" onsubmit="return confirm('Permanently delete this student account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 text-rose-300 transition-all hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" title="Delete Student">
                                                    <i class="ri-delete-bin-line text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                                <i class="ri-team-line text-4xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-slate-900">No students found</p>
                                                <p class="text-sm font-medium text-slate-400">Try adjusting your filters or search terms.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="grid gap-4 p-4 md:hidden">
                    @forelse ($students as $student)
                        <article class="relative flex flex-col gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-2xl bg-[#16136a]/5 text-lg font-semibold text-[#16136a]">
                                        @if($student->profile_picture)
                                            <img src="{{ Str::startsWith($student->profile_picture, ['http://', 'https://']) ? $student->profile_picture : asset('storage/' . $student->profile_picture) }}" alt="{{ $student->fullname ?? $student->username }}" class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($student->fullname ?? $student->username, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <h3 class="font-semibold text-slate-900 leading-none">{{ $student->fullname ?? $student->username }}</h3>
                                        <p class="mt-1 text-[11px] font-medium text-slate-400">{{ $student->email }}</p>
                                    </div>
                                </div>
                                @if($student->email_verified_at)
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-widest text-emerald-600">Active</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-widest text-amber-600">Pending</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 rounded-2xl bg-slate-50/80 p-3">
                                <div class="space-y-0.5">
                                    <p class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Class</p>
                                    <p class="truncate text-xs font-semibold text-slate-700">{{ $student->class ?? '—' }}</p>
                                </div>
                                <div class="text-right space-y-0.5">
                                    <p class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Year</p>
                                    <p class="text-xs font-semibold text-slate-700">Year {{ $student->year ?? '—' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 border-t border-slate-50 pt-4">
                                <p class="text-[10px] font-semibold text-slate-300">ID: {{ $student->username }}</p>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.students.show', $student) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] transition-all active:scale-95">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] transition-all active:scale-95">
                                        <i class="ri-pencil-line text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10 text-center">
                            <i class="ri-team-line text-4xl text-slate-200"></i>
                            <p class="text-sm font-semibold text-slate-400">No students found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer Pagination --}}
            <footer class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-slate-100 pt-6 sm:flex-row">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                    Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                </div>
                
                <div class="flex items-center gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        @foreach (request()->except(['per_page', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 outline-none transition-all focus:border-[#16136a] focus:ring-4 focus:ring-[#16136a]/5" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </form>
                    
                    <div class="flex justify-center">
                        {{ $students->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </footer>
        </section>
    </div>
</x-layouts.admin>
/x-layouts.admin>
