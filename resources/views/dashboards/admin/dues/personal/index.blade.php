<x-layouts.admin :title="$title">
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
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
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
                            <p class="font-semibold uppercase tracking-[0.25em] text-rose-500">Error</p>
                            <p class="mt-1 text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <section class="grid gap-4 sm:grid-cols-2 lg:gap-6">
                <article class="relative overflow-hidden rounded-3xl border border-[#16136a]/15 bg-[#16136a] p-6 text-white shadow-lg shadow-[#16136a]/20 sm:p-8">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-white/60 sm:text-xs">Outstanding balance</p>
                            <p class="text-3xl font-bold sm:text-4xl">GHS {{ number_format((float) ($summary['outstanding_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15"><i class="ri-error-warning-line text-2xl"></i></span>
                    </header>
                    <i class="ri-wallet-3-line absolute -bottom-4 -right-4 text-7xl text-white/5 opacity-10" aria-hidden="true"></i>
                </article>

                <article class="relative overflow-hidden rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900 shadow-lg shadow-emerald-100/40 sm:p-8">
                    <header class="flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-emerald-600 sm:text-xs">Payments recorded</p>
                            <p class="text-3xl font-bold sm:text-4xl">GHS {{ number_format((float) ($summary['paid_amount'] ?? 0), 2) }}</p>
                        </div>
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100/80 text-emerald-700 shadow-sm"><i class="ri-coins-line text-2xl"></i></span>
                    </header>
                    <i class="ri-checkbox-circle-line absolute -bottom-4 -right-4 text-7xl text-emerald-200/20 opacity-30" aria-hidden="true"></i>
                </article>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <header class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-[#16136a]">My personal dues history</h2>
                        <p class="text-sm text-slate-600">As an executive, you are also required to clear your student dues.</p>
                    </div>
                </header>

                @php($activeStatus = $filters['status'] ?? '')
                @php($activeYear = $filters['academic_year'] ?? '')
                @php($searchTerm = $filters['search'] ?? '')

                <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/60 p-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="sm:col-span-2 lg:col-span-2 flex flex-col gap-2">
                        <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Search</label>
                        <input type="search" name="search" value="{{ $searchTerm }}" placeholder="e.g. departmental..." class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Status</label>
                        <div class="relative">
                            <select name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All Statuses</option>
                                @foreach ($filterOptions['statuses'] ?? [] as $value => $label)
                                    <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Academic Year</label>
                        <div class="relative">
                            <select name="academic_year" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All Years</option>
                                @foreach ($filterOptions['academic_years'] ?? [] as $year)
                                    <option value="{{ $year }}" @selected($activeYear === $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex items-end justify-end gap-2 lg:col-span-1">
                        <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 text-sm font-bold uppercase tracking-[0.15em] text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                            <i class="ri-filter-3-line text-base" aria-hidden="true"></i>
                            Filter
                        </button>
                    </div>
                </form>

                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/80 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <p class="font-semibold">Showing {{ number_format($dues->firstItem() ?? 0) }}–{{ number_format($dues->lastItem() ?? 0) }} of {{ number_format($dues->total()) }} dues</p>
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
                                <th class="px-5 py-3 text-right">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($dues as $due)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-900">{{ $due->description }}</p>
                                        @if ($due->payment_status === 'owing' && $due->rejection_reason)
                                            <div class="mt-2 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
                                                <p class="font-bold uppercase tracking-widest text-[#9f1239] mb-1">Previous Payment Rejected</p>
                                                <p>{{ $due->rejection_reason }}</p>
                                            </div>
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
                                    <td class="px-5 py-4 text-right">
                                        @if ($status === 'owing')
                                            @if(\App\Models\PaymentSetting::getValue('manual_payment_enabled', '0') === '1')
                                                <a href="{{ route('admin.personal-dues.manual.show', $due) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50">
                                                    <i class="ri-file-upload-line text-base" aria-hidden="true"></i>
                                                    Pay Manually
                                                </a>
                                            @endif
                                        @elseif ($status === 'paid')
                                            <a href="{{ route('admin.personal-dues.receipt', $due) }}" class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/30 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] shadow-sm transition hover:-translate-y-0.5 hover:border-[#16136a]/50">
                                                <i class="ri-file-download-line text-base" aria-hidden="true"></i>
                                                Receipt
                                            </a>
                                        @else
                                            <div class="text-xs text-slate-400">Verifying…</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="ri-archive-drawer-line text-3xl text-slate-300" aria-hidden="true"></i>
                                            <p class="font-semibold text-slate-600">No personal dues found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="grid gap-4 lg:hidden">
                        @forelse ($dues as $due)
                            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 space-y-1">
                                        <h3 class="text-[15px] font-bold text-slate-900 leading-tight">{{ $due->description }}</h3>
                                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">{{ $due->academic_year }}</p>
                                    </div>
                                    @php($status = $due->payment_status)
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50/80 p-3">
                                    <div class="space-y-0.5">
                                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Amount</p>
                                        <p class="text-sm font-bold text-[#16136a]">GHS {{ number_format((float) $due->amount, 2) }}</p>
                                    </div>
                                    <div class="space-y-0.5 text-right">
                                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Due Date</p>
                                        <p class="text-[11px] font-bold text-slate-700">{{ optional($due->due_date)->format('M d, Y') ?? '—' }}</p>
                                    </div>
                                </div>

                                @if ($due->payment_status === 'owing' && $due->rejection_reason)
                                    <div class="mt-3 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2 text-[10px] text-rose-700">
                                        <p class="font-bold uppercase tracking-widest text-[#9f1239] mb-0.5">Payment Rejected</p>
                                        <p>{{ $due->rejection_reason }}</p>
                                    </div>
                                @endif

                                <footer class="mt-5">
                                    @if ($due->payment_status === 'owing')
                                        @if(\App\Models\PaymentSetting::getValue('manual_payment_enabled', '0') === '1')
                                            <a href="{{ route('admin.personal-dues.manual.show', $due) }}" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#16136a] text-xs font-bold uppercase tracking-widest text-white shadow-md transition-all active:scale-95">
                                                <i class="ri-file-upload-line text-base"></i>
                                                Pay Manually
                                            </a>
                                        @endif
                                    @elseif ($due->payment_status === 'paid')
                                        <a href="{{ route('admin.personal-dues.receipt', $due) }}" class="flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 text-[11px] font-bold uppercase tracking-widest text-emerald-700 shadow-sm transition hover:bg-emerald-100/50">
                                            <i class="ri-file-download-line text-lg"></i>
                                            Download Receipt
                                        </a>
                                    @else
                                        <div class="flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-amber-200 bg-amber-50 text-[11px] font-bold uppercase tracking-widest text-amber-700">
                                            <i class="ri-history-line animate-spin-slow"></i>
                                            Awaiting Verification
                                        </div>
                                    @endif
                                </footer>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/70 p-10 text-center">
                                <p class="font-bold text-slate-600">No personal dues found</p>
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
    </div>
</x-layouts.admin>
