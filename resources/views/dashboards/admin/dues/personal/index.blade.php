@php
    $title = $title ?? 'Executive Personal Dues';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header Section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Personal Dues</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Manage your individual financial obligations</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Admin Account</span>
                        <span class="text-xl font-semibold text-[#16136a]">{{ auth()->user()->fullname }}</span>
                    </div>
                </div>
            </header>

            {{-- Summary Cards (Bento Style) --}}
            @php
                $outstanding = (float) ($summary['outstanding_amount'] ?? 0);
                $paid = (float) ($summary['paid_amount'] ?? 0);
                $totalDues = $dues->total();
            @endphp
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div @class([
                    'relative overflow-hidden rounded-[2.5rem] p-8 text-white shadow-xl',
                    'bg-rose-600 shadow-rose-600/20' => $outstanding > 0,
                    'bg-emerald-600 shadow-emerald-600/20' => $outstanding == 0,
                ])>
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">My Balance</p>
                        <p class="mt-4 text-4xl font-semibold">GHS {{ number_format($outstanding, 2) }}</p>
                        @if($outstanding > 0)
                            <p class="mt-2 text-xs font-semibold text-white/40 italic">Outstanding dues require clearance</p>
                        @else
                            <p class="mt-2 text-xs font-semibold text-white/40 italic">Your personal account is clear</p>
                        @endif
                    </div>
                    <x-heroicon-o-wallet class="absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12 size-5" />
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Paid to Date</p>
                    <p class="mt-4 text-4xl font-semibold text-emerald-600">GHS {{ number_format($paid, 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Total settled personally</p>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Billing History</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $totalDues }} <span class="text-lg text-slate-300 uppercase tracking-widest">Items</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Personal dues records generated</p>
                </div>
            </div>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-check-circle class="size-6" />
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Filter Bar --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40 lg:p-8">
                <form method="GET" class="grid gap-6 md:grid-cols-4">
                    <div class="md:col-span-2 space-y-2">
                        <label for="search" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Search Records</label>
                        <div class="relative">
                            <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                            <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" 
                                class="h-12 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" 
                                placeholder="Reference number or description...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Payment Status</label>
                        <select id="status" name="status" class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Statuses</option>
                            @foreach ($filterOptions['statuses'] ?? [] as $val => $lbl)
                                <option value="{{ $val }}" @selected(($filters['status'] ?? '') === (string)$val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="flex h-12 w-full items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                            <x-heroicon-o-funnel class="size-5" /> Apply Filters
                        </button>
                    </div>
                </form>
            </section>

            {{-- Dues List --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Payment History</h2>
                </div>

                <div class="grid gap-6">
                    @forelse ($dues as $due)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white p-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60">
                            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <div class="flex items-start gap-6 min-w-0">
                                    <div @class([
                                        'flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl font-semibold shadow-lg transition-transform group-hover:scale-110',
                                        'bg-rose-50 text-rose-600 shadow-rose-600/10' => $due->payment_status === 'owing',
                                        'bg-amber-50 text-amber-600 shadow-amber-600/10' => $due->payment_status === 'pending_verification',
                                        'bg-emerald-50 text-emerald-600 shadow-emerald-600/10' => $due->payment_status === 'paid',
                                    ])>
                                        <i @class([
                                            'ri-error-warning-line text-2xl' => $due->payment_status === 'owing',
                                            'ri-history-line text-2xl' => $due->payment_status === 'pending_verification',
                                            'ri-checkbox-circle-line text-2xl' => $due->payment_status === 'paid',
                                        ])></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span @class([
                                                'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[9px] font-semibold uppercase tracking-widest',
                                                'bg-rose-100 text-rose-700' => $due->payment_status === 'owing',
                                                'bg-amber-100 text-amber-700' => $due->payment_status === 'pending_verification',
                                                'bg-emerald-100 text-emerald-700' => $due->payment_status === 'paid',
                                            ])>
                                                {{ $statusLabels[$due->payment_status] ?? Str::headline($due->payment_status) }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-slate-300 uppercase tracking-widest">
                                                AY {{ $due->academic_year }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $due->description }}</h3>
                                        <div class="mt-2 flex flex-wrap gap-4">
                                            @if($due->due_date)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Due Date:</span>
                                                    <span class="text-xs font-semibold text-slate-600">{{ $due->due_date->format('M j, Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col lg:items-end gap-4 shrink-0 border-t lg:border-t-0 border-slate-50 pt-4 lg:pt-0">
                                    <div class="text-left lg:text-right">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Due Amount</p>
                                        <p class="text-2xl font-semibold text-[#16136a]">GHS {{ number_format((float)$due->amount, 2) }}</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($due->payment_status === 'owing')
                                            @if(\App\Models\PaymentSetting::getValue('manual_payment_enabled', '0') === '1')
                                                <a href="{{ route('admin.personal-dues.manual.show', $due) }}" class="flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-2xl bg-slate-100 px-4 text-[10px] font-semibold uppercase tracking-widest text-slate-700 transition-all hover:-translate-y-0.5">
                                                    <x-heroicon-o-currency-dollar class="size-4" /> Manual
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('admin.personal-dues.rushpay.initialize', $due) }}" class="inline-block w-full sm:w-auto">
                                                    @csrf
                                                    <button type="submit" class="flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5">
                                                        <x-heroicon-o-shield-check class="size-4" /> RushPay
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($due->payment_status === 'paid')
                                            <a href="{{ route('admin.personal-dues.receipt', $due) }}" class="flex h-12 items-center gap-3 rounded-2xl bg-emerald-50 px-6 text-[10px] font-semibold uppercase tracking-widest text-emerald-600 transition-all hover:bg-emerald-100">
                                                <x-heroicon-o-document-arrow-down class="size-5" /> Receipt
                                            </a>
                                        @else
                                            <div class="flex h-12 items-center gap-3 rounded-2xl bg-amber-50 px-6 text-[10px] font-semibold uppercase tracking-widest text-amber-600 italic">
                                                <x-heroicon-o-clock class="size-5" /> Verifying...
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($due->payment_status === 'owing' && $due->rejection_reason)
                                <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50/50 p-4 text-[11px] font-semibold text-rose-700">
                                    <div class="flex items-start gap-3">
                                        <x-heroicon-o-exclamation-triangle class="size-5" />
                                        <div>
                                            <p class="uppercase tracking-widest text-rose-500 mb-1 text-[9px]">Payment Rejected</p>
                                            <p>{{ $due->rejection_reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-[2.5rem] border border-dashed border-slate-300 p-20 text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                <x-heroicon-o-credit-card class="text-5xl size-5" />
                            </div>
                            <h3 class="mt-6 text-lg font-semibold text-slate-900">No Invoices Found</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-400">Your personal billing history is currently clear.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($dues->hasPages())
                    <div class="mt-8 rounded-[2rem] bg-slate-50 p-4 text-center">
                        {{ $dues->onEachSide(1)->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
