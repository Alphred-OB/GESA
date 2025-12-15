@php($title = 'Student accounts')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5">
                <div class="space-y-2">
                    <div class="skeleton inline-flex h-7 w-40 items-center rounded-full bg-[#16136a]/10"></div>
                    <div class="skeleton h-8 w-64 rounded-2xl bg-slate-200"></div>
                    <div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
                </div>
                <div class="flex flex-wrap justify-center gap-3 sm:justify-end">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="skeleton h-10 w-32 rounded-2xl bg-slate-100"></div>
                    @endfor
                </div>
            </header>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="grid gap-4 lg:grid-cols-4">
                    <article class="rounded-2xl border border-[#16136a]/20 bg-[#16136a] px-6 py-5 text-white shadow-lg shadow-[#16136a]/20">
                        <div class="space-y-3">
                            <div class="skeleton h-3 w-32 rounded-full bg-white/40"></div>
                            <div class="skeleton h-8 w-20 rounded-2xl bg-white/30"></div>
                        </div>
                        <div class="mt-4 skeleton h-3 w-40 rounded-full bg-white/25"></div>
                    </article>

                    <div class="lg:col-span-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @for ($i = 0; $i < 3; $i++)
                            <article class="rounded-2xl border border-slate-200/80 bg-white px-5 py-4 shadow-sm shadow-[#16136a]/5">
                                <div class="space-y-3">
                                    <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                    <div class="skeleton h-7 w-16 rounded-2xl bg-slate-200"></div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @for ($j = 0; $j < 4; $j++)
                                            <div class="skeleton h-7 rounded-xl bg-slate-100"></div>
                                        @endfor
                                    </div>
                                </div>
                            </article>
                        @endfor
                    </div>
                </div>

                <div class="mt-6 space-y-4 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4">
                    <div class="grid gap-3 md:grid-cols-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="space-y-2">
                                <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
                                <div class="skeleton h-10 w-full rounded-2xl bg-white"></div>
                            </div>
                        @endfor
                        <div class="flex items-end justify-start md:justify-end">
                            <div class="skeleton h-11 w-36 rounded-2xl bg-[#16136a]/10"></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 rounded-2xl border border-slate-200/70 bg-white/60 p-4">
                    <div class="skeleton h-4 w-56 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-40 rounded-full bg-slate-100"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="hidden md:block">
                        <div class="skeleton h-10 w-full bg-slate-50/80"></div>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="skeleton h-12 w-full bg-white"></div>
                        @endfor
                    </div>
                    <div class="grid gap-4 p-4 md:hidden">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="skeleton h-24 w-full rounded-2xl bg-white"></div>
                        @endfor
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <div class="skeleton h-3 w-40 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-8 w-32 rounded-2xl bg-slate-100 sm:ml-auto"></div>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
        <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 text-center shadow-lg shadow-[#16136a]/5 sm:text-left md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                    <i class="ri-team-line text-base" aria-hidden="true"></i>
                    Student accounts
                </p>
                <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Manage student accounts</h1>
                <p class="text-sm text-slate-600">Review onboarded students, analyse class/year distribution, and manage credentials from a single dashboard.</p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap sm:justify-end">
                <a href="{{ route('admin.students.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl sm:w-auto" aria-label="Create student account">
                    <i class="ri-user-add-line text-base" aria-hidden="true"></i>
                    New student
                </a>
                <form method="POST" action="{{ route('admin.students.promote-years') }}" onsubmit="return confirm('Promote all students to the next academic year?');">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-amber-500 bg-amber-50 px-5 py-2.5 text-sm font-semibold text-amber-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-amber-100 sm:w-auto" aria-label="Promote all students to the next academic year">
                        <i class="ri-arrow-up-circle-line text-base" aria-hidden="true"></i>
                        Promote year
                    </button>
                </form>
                <a href="{{ route('admin.students.export', request()->query()) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-[#16136a]/30 bg-white px-5 py-2.5 text-sm font-semibold text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg sm:w-auto" aria-label="Export students">
                    <i class="ri-download-2-line text-base" aria-hidden="true"></i>
                    Export
                </a>
            </div>
        </header>

        <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="grid gap-4 lg:grid-cols-4">
                <article class="rounded-2xl border border-[#16136a]/20 bg-[#16136a] px-6 py-5 text-white shadow-lg shadow-[#16136a]/20">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/80">Total students</p>
                            <p class="text-4xl font-semibold">{{ number_format($stats['total']) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                            <i class="ri-team-line text-2xl" aria-hidden="true"></i>
                        </span>
                    </div>
                    <p class="mt-6 text-xs uppercase tracking-[0.25em] text-white/70">Across all programmes</p>
                </article>

                <div class="lg:col-span-3">
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($stats['class_breakdown'] as $classStat)
                            <article class="rounded-2xl border border-slate-200/80 bg-white px-5 py-4 shadow-sm shadow-[#16136a]/5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm font-semibold text-[#16136a]">
                                            <i class="ri-community-line text-base" aria-hidden="true"></i>
                                            <span>{{ $classStat['name'] }}</span>
                                        </div>
                                        <p class="text-xl font-semibold text-slate-900">{{ number_format($classStat['total']) }}</p>
                                        <p class="text-xs font-medium uppercase tracking-[0.25em] text-slate-400">students</p>
                                    </div>
                                </div>
                                <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-xs text-slate-500">
                                    @foreach ($stats['year_buckets'] as $year)
                                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                                            <dt class="font-semibold text-slate-500">Year {{ $year }}</dt>
                                            <dd class="text-sm font-semibold text-slate-900">{{ number_format($classStat['years'][$year] ?? 0) }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </article>
                        @empty
                            <div class="sm:col-span-2 xl:col-span-3">
                                <div class="flex h-full items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-10 text-sm text-slate-500">
                                    <p>No student class data yet. Invite students to onboard.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Graduated students</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ number_format($stats['graduated_total'] ?? 0) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Total graduates across all programmes, broken down by class.</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse (($stats['graduated_class_breakdown'] ?? []) as $gradStat)
                            <article class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm shadow-[#16136a]/5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="space-y-1">
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $gradStat['name'] }}</p>
                                        <p class="text-xl font-semibold text-[#16136a]">{{ number_format($gradStat['total'] ?? 0) }}</p>
                                        <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">graduates</p>
                                    </div>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a]">
                                        <i class="ri-graduation-cap-line text-base" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </article>
                        @empty
                            <p class="text-xs text-slate-500">No graduates recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <form method="GET" class="space-y-3 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 md:flex md:flex-wrap md:items-end md:gap-3 md:space-y-0">
                @foreach (request()->except(['search', 'class', 'year', 'status', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="flex w-full flex-col gap-2 md:w-64">
                    <label for="filter_search" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</label>
                    <div class="relative">
                        <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="filter_search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, username, email, reference no." class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-52">
                    <label for="filter_class" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Class</label>
                    <div class="relative">
                        <select id="filter_class" name="class" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All</option>
                            @foreach ($filterOptions['classes'] as $classOption)
                                <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-40">
                    <label for="filter_year" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year</label>
                    <div class="relative">
                        <select id="filter_year" name="year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All</option>
                            @foreach ($filterOptions['years'] as $yearOption)
                                <option value="{{ $yearOption }}" @selected(($filters['year'] ?? '') == $yearOption)>Year {{ $yearOption }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-40">
                    <label for="filter_status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <div class="relative">
                        <select id="filter_status" name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end md:ml-auto md:w-auto">
                    <button type="submit" class="inline-flex h-11 min-w-[140px] items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-equalizer-line text-base"></i>
                        Apply
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                <p class="font-semibold">Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</p>
                <form method="GET" class="flex items-center gap-2">
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="students_per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                    <select id="students_per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-[13px] text-slate-600">
                        <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="px-5 py-2.5">Student</th>
                                <th class="px-5 py-2.5">Class · Year</th>
                                <th class="px-5 py-2.5">Status</th>
                                <th class="px-5 py-2.5">Created</th>
                                <th class="px-5 py-2.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($students as $student)
                                <tr>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-semibold text-slate-900 text-[15px]">{{ $student->fullname ?? $student->username }}</span>
                                            <span class="text-[12px] text-slate-500">{{ $student->email }}</span>
                                            <span class="text-[12px] text-slate-400">{{ $student->username }} @if($student->index_number) · {{ $student->index_number }} @endif</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-slate-500">
                                        {{ $student->class ?? '—' }}
                                        <span class="text-slate-400">·</span>
                                        {{ $student->year ? 'Year ' . $student->year : '—' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($student->email_verified_at)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                                <i class="ri-checkbox-circle-fill text-xs"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                <i class="ri-time-line text-xs"></i>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-[12px] text-slate-500">{{ $student->created_at?->format('M j, Y · g:i A') ?? '—' }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                <i class="ri-eye-line text-sm"></i>
                                                View
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                <i class="ri-pencil-line text-sm"></i>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline-flex" onsubmit="return confirm('Delete this student account? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-xl border border-rose-200 px-3 py-1.5 text-[12px] font-semibold text-rose-600 transition hover:bg-rose-50">
                                                    <i class="ri-delete-bin-line text-sm"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-emotion-unhappy-line text-3xl text-slate-300"></i>
                                            <p class="font-semibold text-slate-600">No students found</p>
                                            <p class="text-sm text-slate-500">Adjust filters or invite students to onboard.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden">
                    <div class="grid gap-4">
                        @forelse ($students as $student)
                            <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h2 class="text-base font-semibold text-slate-900">{{ $student->fullname ?? $student->username }}</h2>
                                        <p class="text-xs text-slate-500">{{ $student->email }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->username }} @if($student->index_number) · {{ $student->index_number }} @endif</p>
                                    </div>
                                </div>
                                <dl class="mt-4 space-y-2 text-xs text-slate-500">
                                    <div class="flex items-center justify-between">
                                        <dt>Class · Year</dt>
                                        <dd>{{ $student->class ?? '—' }} · {{ $student->year ? 'Year ' . $student->year : '—' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Status</dt>
                                        <dd>
                                            @if($student->email_verified_at)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                                    <i class="ri-checkbox-circle-fill text-xs"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                    <i class="ri-time-line text-xs"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Created</dt>
                                        <dd>{{ $student->created_at?->format('M j, Y') ?? '—' }}</dd>
                                    </div>
                                </dl>
                                <footer class="mt-4 flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                        <i class="ri-eye-line text-sm"></i>
                                        View
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                        <i class="ri-pencil-line text-sm"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" class="inline-flex" onsubmit="return confirm('Delete this student account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-xl border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                            <i class="ri-delete-bin-line text-sm"></i>
                                            Delete
                                        </button>
                                    </form>
                                </footer>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                                <i class="ri-emotion-unhappy-line text-3xl text-slate-300"></i>
                                <p class="mt-3 font-semibold text-slate-600">No students found.</p>
                                <p class="text-sm text-slate-500">Adjust filters or add a new student.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="text-xs text-slate-500">Page {{ $students->currentPage() }} of {{ $students->lastPage() }}</p>
                <div class="flex justify-center sm:ml-auto sm:justify-end">
                    {{ $students->onEachSide(1)->links('vendor.pagination.data-limit') }}
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
