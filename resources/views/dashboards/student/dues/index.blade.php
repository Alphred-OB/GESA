<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')
    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-10" role="status" aria-live="polite">
            <section class="grid gap-6 md:grid-cols-2">
                <article class="flex h-full flex-col rounded-3xl border border-[#16136a]/15 bg-[#16136a] p-6 text-white shadow-lg shadow-[#16136a]/20">
                    <div class="space-y-5">
                        <div class="skeleton h-3 w-40 rounded-full bg-white/30"></div>
                        <div class="skeleton h-8 w-32 rounded-2xl bg-white/40"></div>
                        <div class="skeleton mt-6 h-12 w-full rounded-2xl bg-white/20"></div>
                    </div>
                </article>

                <article class="flex h-full flex-col rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-lg shadow-emerald-100/40">
                    <div class="space-y-5">
                        <div class="skeleton h-3 w-36 rounded-full bg-emerald-200/60"></div>
                        <div class="skeleton h-8 w-28 rounded-2xl bg-emerald-200/80"></div>
                        <div class="skeleton mt-6 h-12 w-full rounded-2xl bg-emerald-100/80"></div>
                    </div>
                </article>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="space-y-3">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-4 w-64 rounded-full bg-slate-100"></div>
                </div>

                <div class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/60 p-4 md:grid-cols-5">
                    <div class="skeleton h-11 rounded-2xl bg-white md:col-span-2"></div>
                    <div class="skeleton h-11 rounded-2xl bg-white"></div>
                    <div class="skeleton h-11 rounded-2xl bg-white"></div>
                    <div class="flex items-center justify-end gap-3 md:col-span-2 md:col-start-4">
                        <div class="skeleton h-11 w-24 rounded-2xl bg-white"></div>
                        <div class="skeleton h-11 w-28 rounded-2xl bg-[#16136a]/10"></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/70">
                    <ul class="divide-y divide-slate-200">
                        @for ($i = 0; $i < 4; $i++)
                            <li class="grid gap-4 bg-white px-5 py-4 lg:grid-cols-7">
                                <div class="skeleton h-4 w-40 rounded-full bg-slate-100 lg:col-span-2"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-4 w-24 rounded-full bg-slate-100"></div>
                                <div class="skeleton h-9 rounded-full bg-slate-100"></div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            @if (session('student_portal_limited'))
                <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms class="relative">
                    <div class="relative overflow-hidden rounded-3xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 shadow-sm">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                                <i class="ri-lock-2-line text-lg" aria-hidden="true"></i>
                            </span>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-500">Portal access limited</p>
                                <p class="text-sm">Your access to some sections of the student portal is temporarily limited because you have outstanding dues.</p>
                                <ul class="mt-2 space-y-1 text-xs text-amber-800">
                                    <li class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                        <span>Dashboard, announcements, events, resources and the suggestion box are locked until your dues are cleared.</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                        <span>You can still review your dues and complete payment from this page.</span>
                                    </li>
                                </ul>
                            </div>
                            <button type="button" @click="open = false" class="ml-4 inline-flex h-8 w-8 items-center justify-center rounded-full text-amber-400 hover:bg-amber-100 hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-amber-50">
                                <span class="sr-only">Dismiss notice</span>
                                <i class="ri-close-line text-base" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <i class="ri-check-line" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="font-semibold uppercase tracking-[0.25em] text-emerald-500">Success</p>
                            <p class="mt-1 text-sm">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <i class="ri-error-warning-line" aria-hidden="true"></i>
                        </span>
                        <div>
                            <p class="font-semibold uppercase tracking-[0.25em] text-rose-500">Payment error</p>
                            <p class="mt-1 text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <section class="grid gap-6 md:grid-cols-2">
                <article class="flex h-full flex-col rounded-3xl border border-[#16136a]/15 bg-[#16136a] p-6 text-white shadow-lg shadow-[#16136a]/20">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Outstanding balance</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) ($summary['outstanding_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15"><i class="ri-error-warning-line text-2xl"></i></span>
                    </header>
                </article>

                <article class="flex h-full flex-col rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-lg shadow-emerald-100/40">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-600">Payments recorded</p>
                            <p class="text-3xl font-semibold">GHS {{ number_format((float) ($summary['paid_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700"><i class="ri-coins-line text-2xl"></i></span>
                    </header>
                </article>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <header class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-[#16136a]">My dues history</h2>
                    <p class="text-sm text-slate-600">Filter by academic year, status, or search by description or reference.</p>
                </div>
            </header>

            @php($activeStatus = $filters['status'] ?? '')
            @php($activeYear = $filters['academic_year'] ?? '')
            @php($searchTerm = $filters['search'] ?? '')

            <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/60 p-4 md:grid-cols-5">
                <div class="md:col-span-2 flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search description or reference</label>
                    <input type="search" name="search" value="{{ $searchTerm }}" placeholder="e.g. departmental dues" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                    <div class="relative">
                        <select name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All statuses</option>
                            @foreach ($filterOptions['statuses'] ?? [] as $value => $label)
                                <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</label>
                    <div class="relative">
                        <select name="academic_year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <option value="">All years</option>
                            @foreach ($filterOptions['academic_years'] ?? [] as $year)
                                <option value="{{ $year }}" @selected($activeYear === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-end justify-end gap-3 md:col-span-2 md:col-start-4">
                    <a href="{{ route('student.dues.index') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#18188a]">
                        <i class="ri-filter-3-line text-base" aria-hidden="true"></i>
                        Apply
                    </button>
                </div>
            </form>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/80 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                <p class="font-semibold">Showing {{ number_format($dues->firstItem() ?? 0) }}–{{ number_format($dues->lastItem() ?? 0) }} of {{ number_format($dues->total()) }} dues</p>
                <form method="GET" class="flex items-center gap-2">
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                    <select id="per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @php($statusColors = [
                'owing' => 'bg-rose-50 text-rose-600 border border-rose-100',
                'pending_verification' => 'bg-amber-50 text-amber-600 border border-amber-100',
                'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            ])

            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                <table class="hidden min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600 lg:table">
                    <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Description</th>
                            <th class="px-5 py-3">Academic year</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Due date</th>
                            <th class="px-5 py-3">Reference</th>
                            <th class="px-5 py-3 text-right">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($dues as $due)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $due->description }}</p>
                                    @if ($due->payment_notes)
                                        <p class="mt-1 text-xs text-slate-500">{{ $due->payment_notes }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $due->academic_year }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-900">GHS {{ number_format((float) $due->amount, 2) }}</td>
                                <td class="px-5 py-4">
                                    @php($status = $due->payment_status)
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        <i class="ri-checkbox-circle-line text-sm" aria-hidden="true"></i>
                                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ optional($due->due_date)->format('M j, Y') ?? '—' }}</td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if ($status === 'owing')
                                        <form method="POST" action="{{ route('student.payments.paystack.initialize', $due) }}" class="flex justify-end">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#18188a]">
                                                <i class="ri-secure-payment-line text-base" aria-hidden="true"></i>
                                                Pay with Paystack
                                            </button>
                                        </form>
                                    @elseif ($status === 'pending_verification' && $due->payment_method === 'paystack')
                                        <div class="text-right text-xs font-semibold text-amber-600">Verifying…</div>
                                    @elseif ($status === 'paid')
                                        <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/30 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/50">
                                            <i class="ri-file-download-line text-base" aria-hidden="true"></i>
                                            Download receipt
                                        </a>
                                    @else
                                        <div class="text-right text-xs text-slate-400">—</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="ri-archive-drawer-line text-3xl text-slate-300" aria-hidden="true"></i>
                                        <p class="font-semibold text-slate-600">No dues found for the selected filters.</p>
                                        <p class="text-xs text-slate-500">Adjust the filters or contact support if you believe this is incorrect.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="grid gap-4 lg:hidden">
                    @forelse ($dues as $due)
                        <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                            <header class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-900">{{ $due->description }}</h3>
                                    <p class="text-xs text-slate-500">{{ $due->academic_year }} · GHS {{ number_format((float) $due->amount, 2) }}</p>
                                </div>
                                @php($status = $due->payment_status)
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </header>
                            <dl class="mt-4 space-y-1 text-xs text-slate-500">
                                <div class="flex justify-between">
                                    <dt>Due date</dt>
                                    <dd class="text-right text-slate-700">{{ optional($due->due_date)->format('M j, Y') ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Reference</dt>
                                    <dd class="text-right text-slate-700">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</dd>
                                </div>
                                @if ($due->payment_notes)
                                    <div class="mt-2 rounded-xl bg-slate-50 p-3 text-left text-xs text-slate-500">
                                        {{ $due->payment_notes }}
                                    </div>
                                @endif
                            </dl>
                            @if ($due->payment_status === 'owing')
                                <form method="POST" action="{{ route('student.payments.paystack.initialize', $due) }}" class="mt-4">
                                    @csrf
                                    <button type="submit" class="w-full rounded-full bg-[#16136a] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#18188a]">
                                        <i class="ri-secure-payment-line text-base" aria-hidden="true"></i>
                                        Pay Now
                                    </button>
                                </form>
                            @elseif ($due->payment_status === 'pending_verification' && $due->payment_method === 'paystack')
                                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">Verifying Paystack payment…</p>
                            @elseif ($due->payment_status === 'paid')
                                <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="mt-4 inline-flex w-full items-center justifycenter gap-2 rounded-full border border-[#16136a]/30 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/50">
                                    <i class="ri-file-download-line text-base" aria-hidden="true"></i>
                                    Download receipt
                                </a>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 p-8 text-center text-sm text-slate-500">
                            <i class="ri-archive-drawer-line text-3xl text-slate-300" aria-hidden="true"></i>
                            <p class="mt-3 font-semibold text-slate-600">No dues found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="text-xs text-slate-500">Page {{ number_format($dues->currentPage()) }} of {{ number_format($dues->lastPage()) }}</p>
                <div class="flex justify-center sm:ml-auto sm:justify-end">
                    {{ $dues->onEachSide(1)->links('vendor.pagination.data-limit') }}
                </div>
            </div>
        </section>
    </div>
</x-layouts.dashboard>
