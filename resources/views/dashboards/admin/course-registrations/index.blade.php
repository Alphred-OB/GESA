@php($title = $title ?? 'Manage course registrations')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="relative">
        <div x-show="loading" x-transition.opacity.duration.200ms class="pointer-events-none absolute inset-0 z-10 flex justify-center bg-slate-50/80 backdrop-blur-sm">
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-5 py-10 sm:px-6 lg:px-8">
                <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/90 p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="space-y-3">
                        <div class="skeleton inline-flex h-7 w-56 items-center rounded-full bg-[#16136a]/10"></div>
                        <div class="skeleton h-8 w-72 rounded-2xl bg-slate-200"></div>
                        <div class="skeleton h-4 w-96 max-w-full rounded-2xl bg-slate-100"></div>
                    </div>
                </header>

                <section class="space-y-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/8">
                    <div class="grid gap-3 md:grid-cols-4">
                        <div class="space-y-2 md:col-span-2">
                            <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="skeleton h-3 w-20 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="skeleton h-3 w-20 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="skeleton h-3 w-16 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-11 w-full rounded-2xl bg-slate-100"></div>
                        </div>
                    </div>

                    <div class="mt-6 hidden md:block">
                        <div class="skeleton h-10 w-full rounded-2xl bg-slate-100"></div>
                        <div class="mt-3 space-y-2">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="skeleton h-11 w-full rounded-xl bg-slate-50"></div>
                            @endfor
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:hidden">
                        @for ($i = 0; $i < 3; $i++)
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="space-y-3">
                                    <div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
                                    <div class="skeleton h-3 w-32 rounded-full bg-slate-100"></div>
                                    <div class="skeleton h-3 w-24 rounded-full bg-slate-100"></div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="skeleton h-3 w-28 rounded-full bg-slate-100"></div>
                                    <div class="skeleton h-8 w-24 rounded-xl bg-slate-100"></div>
                                </div>
                            </article>
                        @endfor
                    </div>

                    <div class="mt-6 flex flex-col gap-3 border-t border-slate-200/60 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                        <div class="skeleton h-3 w-48 rounded-full bg-slate-100"></div>
                        <div class="flex justify-center gap-2 sm:ml-auto sm:justify-end">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="skeleton h-8 w-8 rounded-full bg-slate-100"></div>
                            @endfor
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="mx-auto w-full max-w-6xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                    <i class="ri-file-edit-line text-base" aria-hidden="true"></i>
                    Course registrations
                </p>
                <h1 class="text-2xl font-semibold text-[#16136a] sm:text-3xl">Review student submissions</h1>
                <p class="text-sm text-slate-600">Track uploaded PDFs, approve or reject requests, and leave guidance for students.</p>
            </div>
        </header>

        @if (session('status'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ri-check-double-line text-lg" aria-hidden="true"></i>
                    <p>{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ri-error-warning-line text-lg" aria-hidden="true"></i>
                    <p>{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <section class="space-y-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
            <form method="GET" class="space-y-3 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 md:flex md:flex-wrap md:items-end md:gap-3 md:space-y-0">
                <input type="hidden" name="per_page" value="{{ request('per_page', $currentPerPage) }}">
                @foreach (request()->except(['search','status','class','year','page','per_page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="flex w-full flex-col gap-2 md:w-64">
                    <label for="filter_search" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</label>
                    <div class="relative">
                        <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="filter_search" type="search" name="search" value="{{ $search }}" placeholder="Name, email, class" class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-48">
                    <label for="filter_status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <div class="relative">
                        <select id="filter_status" name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="" @selected($activeStatus === null || $activeStatus === '')>All</option>
                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption }}" @selected($activeStatus === $statusOption)>{{ Str::headline($statusOption) }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-48">
                    <label for="filter_class" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Class</label>
                    <div class="relative">
                        <select id="filter_class" name="class" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="" @selected($activeClass === null || $activeClass === '')>All</option>
                            @foreach ($classOptions as $option)
                                <option value="{{ $option }}" @selected($activeClass === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-2 md:w-40">
                    <label for="filter_year" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year</label>
                    <div class="relative">
                        <select id="filter_year" name="year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="" @selected($activeYear === null || $activeYear === '')>All</option>
                            @foreach ($yearOptions as $option)
                                <option value="{{ $option }}" @selected($activeYear === $option)>{{ $option }}</option>
                            @endforeach
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
        </section>

        <section class="mt-10 space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" x-data="courseRegistrationBulk()" x-init="initialize(@js($registrations->pluck('id')))" x-cloak>
            <form method="POST" action="{{ route('admin.course-registrations.bulk') }}" x-ref="bulkForm">
                @csrf
                <input type="hidden" name="action" x-ref="actionInput">
                <input type="hidden" name="status" x-ref="statusInput">
                <input type="hidden" name="admin_comment" x-ref="commentInput">
                <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">

                <div class="flex flex-col gap-4 rounded-xl border border-slate-200/80 bg-slate-50/60 px-4 py-3 md:flex-row md:items-center md:justify-between">
                    <p class="text-sm font-medium text-slate-600" x-text="bulkSummary"></p>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4" x-show="selectedIds.length" x-cloak x-transition.opacity>
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                            <div class="flex flex-col gap-1">
                                <label for="bulk_status" class="text-xs font-medium text-slate-500">Change status</label>
                                <select id="bulk_status" x-model="statusValue" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#16136a] focus:ring-[#16136a]/60 md:min-w-[12rem]">
                                    <option value="">Select status</option>
                                    @foreach (['in_progress', 'submitted', 'approved', 'rejected'] as $statusOption)
                                        <option value="{{ $statusOption }}">{{ Str::headline($statusOption) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="statusValue === 'rejected'" class="flex flex-1 flex-col gap-1 md:w-64" x-transition>
                                <label for="bulk_comment" class="text-xs font-medium text-slate-500">Comment (optional)</label>
                                <textarea id="bulk_comment" rows="2" x-model="commentValue" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-[#16136a] focus:ring-[#16136a]/60" placeholder="Let students know what to fix"></textarea>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-[#16136a] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#16136a]/90 disabled:opacity-60" @click="submit('update_status')" :disabled="!canApplyStatus">
                                <i class="ri-check-double-line text-sm" aria-hidden="true"></i>
                                Apply status
                            </button>
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-[#16136a]/40 px-4 py-2 text-sm font-medium text-[#16136a] transition hover:bg-[#16136a]/10 disabled:opacity-60" @click="submit('download_documents')" :disabled="!selectedIds.length">
                                <i class="ri-download-2-line text-sm" aria-hidden="true"></i>
                                Download PDFs
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="flex items-center justify-end gap-3 border-b border-slate-200/60 bg-slate-50/40 px-4 py-2">
                        <form method="GET" class="flex items-center gap-2">
                            @foreach (request()->except(['page','per_page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <label for="rows_per_page" class="text-xs font-medium text-slate-500">Rows per page</label>
                            <select id="rows_per_page" name="per_page" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 focus:border-[#16136a] focus:ring-[#16136a]/60" onchange="this.form.submit()">
                                @foreach ($perPageOptions as $opt)
                                    <option value="{{ $opt }}" @selected($currentPerPage === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <table class="hidden min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600 md:table">
                        <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th class="w-12 px-6 py-3">
                                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggleAll($event.target.checked)" :checked="allSelected">
                                </th>
                                <th class="px-6 py-3">Student</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Submitted</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($registrations as $registration)
                                <tr>
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="ids[]" value="{{ $registration->id }}" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggle({{ $registration->id }}, $event.target.checked)" :checked="selectedIds.includes({{ $registration->id }})">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-900">{{ $registration->student?->fullname ?? $registration->student?->username ?? 'Unknown student' }}</span>
                                            <span class="text-xs text-slate-500">{{ $registration->student?->email }}</span>
                                            <span class="text-xs text-slate-400">{{ $registration->student?->class }} · Year {{ $registration->student?->year }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ match($registration->status) {
                                            'approved' => 'bg-emerald-50 text-emerald-700',
                                            'rejected' => 'bg-rose-50 text-rose-600',
                                            'submitted' => 'bg-sky-50 text-sky-700',
                                            default => 'bg-amber-50 text-amber-700',
                                        } }}">
                                            <i class="{{ match($registration->status) {
                                                'approved' => 'ri-checkbox-circle-line',
                                                'rejected' => 'ri-close-circle-line',
                                                'submitted' => 'ri-time-line',
                                                default => 'ri-draft-line',
                                            } }} text-sm"></i>
                                            {{ Str::headline($registration->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $registration->submitted_at ? $registration->submitted_at->format('M j, Y · g:i A') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if (!empty($registration->document_paths))
                                            <a href="{{ route('admin.course-registrations.show', $registration) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                <i class="ri-download-2-line text-sm"></i>
                                                Download
                                            </a>
                                        @else
                                            <span title="No PDF uploaded" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-300">
                                                <i class="ri-download-2-line text-sm"></i>
                                                Download
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-file-forbid-line text-3xl text-slate-300"></i>
                                            <p class="font-semibold text-slate-600">No course registrations found.</p>
                                            <p>Adjust filters or check back when students upload their PDFs.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="grid gap-4 md:hidden">
                        @forelse ($registrations as $registration)
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h2 class="text-lg font-semibold text-slate-900">{{ $registration->student?->fullname ?? $registration->student?->username ?? 'Unknown student' }}</h2>
                                        <p class="text-xs text-slate-500">{{ $registration->student?->email }}</p>
                                        <p class="text-xs text-slate-400">{{ $registration->student?->class }} · Year {{ $registration->student?->year }}</p>
                                    </div>
                                    <input type="checkbox" name="ids[]" value="{{ $registration->id }}" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" @change="toggle({{ $registration->id }}, $event.target.checked)" :checked="selectedIds.includes({{ $registration->id }})">
                                </div>
                                <dl class="mt-4 space-y-2 text-xs text-slate-500">
                                    <div class="flex items-center justify-between">
                                        <dt>Status</dt>
                                        <dd class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ match($registration->status) {
                                            'approved' => 'bg-emerald-50 text-emerald-700',
                                            'rejected' => 'bg-rose-50 text-rose-600',
                                            'submitted' => 'bg-sky-50 text-sky-700',
                                            default => 'bg-amber-50 text-amber-700',
                                        } }}">
                                            <i class="{{ match($registration->status) {
                                                'approved' => 'ri-checkbox-circle-line',
                                                'rejected' => 'ri-close-circle-line',
                                                'submitted' => 'ri-time-line',
                                                default => 'ri-draft-line',
                                            } }} text-sm"></i>
                                            {{ Str::headline($registration->status) }}
                                        </dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Submitted</dt>
                                        <dd>{{ $registration->submitted_at ? $registration->submitted_at->format('M j, Y · g:i A') : '—' }}</dd>
                                    </div>
                                </dl>
                                <footer class="mt-4 flex items-center justify-end">
                                    @if (!empty($registration->document_paths))
                                        <a href="{{ route('admin.course-registrations.show', $registration) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                            <i class="ri-download-2-line text-sm"></i>
                                            Download
                                        </a>
                                    @else
                                        <span title="No PDF uploaded" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-300">
                                            <i class="ri-download-2-line text-sm"></i>
                                            Download
                                        </span>
                                    @endif
                                </footer>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                                <i class="ri-file-forbid-line text-3xl text-slate-300"></i>
                                <p class="mt-3 font-semibold text-slate-600">No course registrations found.</p>
                                <p>Adjust filters or check back later.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </form>

            <div class="flex flex-col gap-3 border-t border-slate-200/60 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="text-xs text-slate-500">Showing {{ $registrations->firstItem() ?? 0 }}–{{ $registrations->lastItem() ?? 0 }} of {{ $registrations->total() }} registrations</p>
                <div class="flex justify-center sm:ml-auto sm:justify-end">
                    {{ $registrations->onEachSide(1)->links('vendor.pagination.data-limit') }}
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
