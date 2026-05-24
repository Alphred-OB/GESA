@php
    $title = $title ?? 'My Financials';
@endphp

<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="space-y-10">

            {{-- Top Bento Grid / Hero --}}
            @php
                $outstanding = (float) ($summary['outstanding_amount'] ?? 0);
                $paid = (float) ($summary['paid_amount'] ?? 0);
                $totalDues = $dues->total();
            @endphp
            <section class="grid gap-6 lg:grid-cols-3">
                <!-- Main Liability Card -->
                <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                   <div class="relative z-10 flex flex-col h-full justify-between gap-10">
                       <header class="flex items-start justify-between">
                           <div>
                               <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-white/50">Student Financials</p>
                               <h1 class="mt-1 text-3xl sm:text-4xl font-semibold tracking-tight leading-none">Financial Overview</h1>
                           </div>
                           <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white shadow-inner backdrop-blur-md ring-1 ring-white/20">
                               <x-heroicon-o-wallet class="size-6" />
                           </div>
                       </header>
                       
                       <div>
                           <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-white/50 mb-1">Current Liability</p>
                           <p class="text-5xl sm:text-6xl font-semibold tabular-nums tracking-tighter leading-none">
                               <span class="text-2xl sm:text-3xl text-white/50 tracking-normal mr-1 align-top leading-tight">GHS</span>{{ number_format($outstanding, 2) }}
                           </p>
                           @if($outstanding > 0)
                               <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-rose-500/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-rose-200 ring-1 ring-rose-500/30 backdrop-blur-md">
                                   <x-heroicon-o-exclamation-triangle class="size-4" /> Action Required
                               </div>
                           @else
                               <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-emerald-500/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-emerald-200 ring-1 ring-emerald-500/30 backdrop-blur-md">
                                   <x-heroicon-o-check-circle class="size-4" /> Account Settled
                               </div>
                           @endif
                       </div>
                   </div>
                   
                   <!-- Subtle background depth -->
                   <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
                   <x-heroicon-o-wallet class="absolute -right-10 -bottom-10 text-[280px] text-white/[0.03] -rotate-12 select-none pointer-events-none size-5" />
                </div>

                <!-- Secondary Stats Stack -->
                <div class="flex flex-col gap-6 lg:col-span-1">
                    <div class="flex-1 relative overflow-hidden rounded-xl border border-slate-100 bg-white p-8 shadow-xl shadow-[#16136a]/5 group">
                        <div class="relative z-10 flex flex-col h-full justify-center">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Settled Amount</p>
                            <p class="mt-4 text-4xl sm:text-5xl font-semibold text-emerald-600 tabular-nums tracking-tighter">
                                <span class="text-lg text-emerald-600/50 mr-1 align-top">GHS</span>{{ number_format($paid, 2) }}
                            </p>
                            <p class="mt-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total payments to date</p>
                        </div>
                        <x-heroicon-o-check-circle class="absolute -right-6 -bottom-6 text-8xl text-emerald-50 opacity-0 transition-opacity duration-500 group-hover:opacity-100 rotate-12 size-5" />
                    </div>

                    <div class="flex-1 relative overflow-hidden rounded-xl border border-slate-100 bg-white p-8 shadow-xl shadow-[#16136a]/5 group">
                        <div class="relative z-10 flex flex-col h-full justify-center">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Billing Records</p>
                            <p class="mt-4 text-4xl sm:text-5xl font-semibold text-[#16136a] tabular-nums tracking-tighter">{{ $totalDues }}</p>
                            <p class="mt-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Invoices generated</p>
                        </div>
                        <x-heroicon-o-document-text class="absolute -right-6 -bottom-6 text-8xl text-slate-50 opacity-0 transition-opacity duration-500 group-hover:opacity-100 rotate-12 size-5" />
                    </div>
                </div>
            </section>

            {{-- Alerts --}}
            @if (session('student_portal_limited'))
                <div class="rounded-xl border border-amber-200/50 bg-gradient-to-r from-amber-50 to-white p-8 shadow-lg shadow-amber-100/50 animate-in fade-in zoom-in-95">
                    <div class="flex items-start gap-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 shadow-inner">
                            <x-heroicon-o-lock-closed class="size-7" />
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 mb-2">Portal Access Restricted</p>
                            <p class="text-sm font-semibold text-amber-900/80 leading-relaxed max-w-3xl">Some sections of the portal are locked until your outstanding dues are cleared. Settle your balance to regain full access to resources and activity feeds.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="rounded-xl border border-emerald-200/50 bg-gradient-to-r from-emerald-50 to-white p-8 shadow-lg shadow-emerald-100/50">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <x-heroicon-s-check-circle class="size-6" />
                        </div>
                        <p class="text-sm font-semibold text-emerald-900">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200/50 bg-gradient-to-r from-red-50 to-white p-8 shadow-lg shadow-red-100/50 mt-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <x-heroicon-s-x-circle class="size-6" />
                        </div>
                        <p class="text-sm font-semibold text-red-900">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Main Content area (Filter & List) --}}
            <div class="grid gap-10 lg:grid-cols-4">
                <div class="lg:col-span-4 space-y-8">
                    
                    {{-- Compact Filter Bar --}}
                    <section class="rounded-xl border border-slate-100 bg-white p-4 sm:p-6 shadow-xl shadow-[#16136a]/5 flex flex-col md:flex-row items-center justify-between gap-6">
                        <form method="GET" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto flex-1">
                            <div class="relative w-full md:w-80">
                                <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" 
                                    class="h-12 w-full rounded-xl border border-slate-100 bg-slate-50/50 pl-12 pr-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/20 focus:border-[#16136a]/30" 
                                    placeholder="Search invoice or reference...">
                            </div>

                            <div class="relative w-full md:w-56">
                                <select id="status" name="status" class="h-12 w-full appearance-none rounded-xl border border-slate-100 bg-slate-50/50 pl-4 pr-10 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/20 focus:border-[#16136a]/30">
                                    <option value="">All Statuses</option>
                                    @foreach ($filterOptions['statuses'] ?? [] as $val => $lbl)
                                        <option value="{{ $val }}" @selected(($filters['status'] ?? '') === (string)$val)>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 size-5" />
                            </div>

                            <button type="submit" class="flex h-12 w-full md:w-auto items-center justify-center gap-2 rounded-xl bg-[#16136a] px-8 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                <span>Filter</span>
                            </button>
                        </form>

                        <div class="flex items-center gap-3 w-full md:w-auto border-t md:border-t-0 border-slate-100 pt-4 md:pt-0 shrink-0">
                            <label class="text-[9px] font-semibold uppercase tracking-widest text-slate-400">Show</label>
                            <select onchange="window.location.href = updateQueryStringParameter(window.location.href, 'per_page', this.value)" class="h-10 appearance-none rounded-xl border border-slate-100 bg-slate-50/50 pl-3 pr-8 text-[10px] font-semibold text-slate-600 outline-none cursor-pointer focus:ring-2 focus:ring-[#16136a]/20 transition-all">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </section>

                    {{-- Dues List --}}
                    <div class="grid gap-6">
                        @forelse ($dues as $due)
                            <article class="group relative overflow-hidden rounded-xl border border-slate-100 bg-white p-6 sm:p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-[#16136a]/10 hover:-translate-y-1">
                                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                                    
                                    {{-- Left side: Icon & Details --}}
                                    <div class="flex items-start gap-6 min-w-0 flex-1">
                                        <div @class([
                                            'flex h-16 w-16 shrink-0 items-center justify-center rounded-xl font-semibold shadow-inner',
                                            'bg-rose-50 text-rose-600 border border-rose-100' => $due->payment_status === 'owing',
                                            'bg-amber-50 text-amber-600 border border-amber-100' => $due->payment_status === 'pending_verification',
                                            'bg-emerald-50 text-emerald-600 border border-emerald-100' => $due->payment_status === 'paid',
                                        ])>
                                            <i @class([
                                                'ri-error-warning-line text-2xl' => $due->payment_status === 'owing',
                                                'ri-history-line text-2xl' => $due->payment_status === 'pending_verification',
                                                'ri-checkbox-circle-line text-2xl' => $due->payment_status === 'paid',
                                            ])></i>
                                        </div>
                                        <div class="min-w-0 flex-1 space-y-3">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span @class([
                                                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[9px] font-semibold uppercase tracking-widest ring-1 ring-inset',
                                                    'bg-rose-50 text-rose-700 ring-rose-200' => $due->payment_status === 'owing',
                                                    'bg-amber-50 text-amber-700 ring-amber-200' => $due->payment_status === 'pending_verification',
                                                    'bg-emerald-50 text-emerald-700 ring-emerald-200' => $due->payment_status === 'paid',
                                                ])>
                                                    {{ $statusLabels[$due->payment_status] ?? Str::headline($due->payment_status) }}
                                                </span>
                                                <span class="rounded-full bg-slate-50 px-3 py-1 text-[9px] font-semibold text-slate-500 uppercase tracking-widest ring-1 ring-inset ring-slate-200">
                                                    AY {{ $due->academic_year }}
                                                </span>
                                            </div>
                                            
                                            <h3 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight group-hover:text-[#16136a] transition-colors line-clamp-2">
                                                {{ $due->description }}
                                            </h3>
                                            
                                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-hashtag class="text-slate-400 size-5" />
                                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Ref:</span>
                                                    <span class="text-xs font-semibold text-slate-700">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</span>
                                                </div>
                                                @if($due->due_date)
                                                    <div class="flex items-center gap-2">
                                                        <x-heroicon-o-calendar class="text-slate-400 size-5" />
                                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Due:</span>
                                                        <span class="text-xs font-semibold text-slate-700">{{ $due->due_date->format('M j, Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right side: Amount & Action --}}
                                    <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-6 shrink-0 border-t lg:border-t-0 border-slate-100 pt-6 lg:pt-0">
                                        <div class="text-left sm:text-right">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-[0.2em] mb-1">Invoice Amount</p>
                                            <p class="text-3xl font-semibold text-[#16136a] tabular-nums tracking-tight">GHS {{ number_format((float)$due->amount, 2) }}</p>
                                        </div>
                                        
                                        <div class="w-full sm:w-auto">
                                            @if($due->payment_status === 'owing')
                                                @if(\App\Models\PaymentSetting::getValue('manual_payment_enabled', '0') === '1')
                                                    <a href="{{ route('student.payments.manual.show', $due) }}" class="flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-[#16136a] px-8 text-[10px] font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 hover:bg-[#18188a]">
                                                        <span>Settle Now</span>
                                                        <x-heroicon-o-arrow-right class="size-5" />
                                                    </a>
                                                @else
                                                    <form method="POST" action="{{ route('student.payments.rushpay.initialize', $due) }}" class="w-full sm:w-auto">
                                                        @csrf
                                                        <button type="submit" class="flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-[#16136a] px-8 text-[10px] font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 hover:bg-[#18188a]">
                                                            <span>Pay Online (RushPay)</span>
                                                            <x-heroicon-o-shield-check class="size-5" />
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($due->payment_status === 'paid')
                                                <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="flex h-12 w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-50 border border-emerald-100 px-8 text-[10px] font-semibold uppercase tracking-widest text-emerald-700 transition-all hover:bg-emerald-100">
                                                    <x-heroicon-o-document-arrow-down class="size-5" />
                                                    <span>View Receipt</span>
                                                </a>
                                            @else
                                                @if($due->payment_method === 'rushpay' && $due->payment_reference)
                                                    <div class="flex w-full flex-col sm:w-auto sm:flex-row gap-2">
                                                        <a href="{{ route('student.payments.rushpay.callback', ['payment_reference' => $due->payment_reference]) }}" class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-amber-50 border border-amber-100 px-6 text-[10px] font-semibold uppercase tracking-widest text-amber-700 transition-all hover:bg-amber-100">
                                                            <x-heroicon-o-arrow-path class="size-5" />
                                                            <span>Re-verify</span>
                                                        </a>
                                                        <form action="{{ route('student.payments.cancel', $due) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-red-50 border border-red-100 px-6 text-[10px] font-semibold uppercase tracking-widest text-red-700 transition-all hover:bg-red-100" onclick="return confirm('Are you sure you want to cancel this pending payment?')">
                                                                <x-heroicon-o-x-circle class="size-5" />
                                                                <span>Cancel</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="flex w-full flex-col sm:w-auto sm:flex-row gap-2">
                                                        <div class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-amber-50 border border-amber-100 px-6 text-[10px] font-semibold uppercase tracking-widest text-amber-700 italic">
                                                            <x-heroicon-o-clock class="size-5" />
                                                            <span>Verifying</span>
                                                        </div>
                                                        <form action="{{ route('student.payments.cancel', $due) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-red-50 border border-red-100 px-6 text-[10px] font-semibold uppercase tracking-widest text-red-700 transition-all hover:bg-red-100" onclick="return confirm('Are you sure you want to cancel this pending payment submission?')">
                                                                <x-heroicon-o-x-circle class="size-5" />
                                                                <span>Cancel</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($due->payment_status === 'owing' && $due->rejection_reason)
                                    <div class="mt-8 rounded-xl border border-rose-100 bg-rose-50/80 p-5 text-sm font-semibold text-rose-900 shadow-inner">
                                        <div class="flex items-start gap-3">
                                            <x-heroicon-o-exclamation-triangle class="size-6 text-rose-600" />
                                            <div>
                                                <p class="uppercase tracking-widest text-rose-600 mb-1 text-[10px] font-semibold">Previous Payment Rejected</p>
                                                <p class="leading-relaxed">{{ $due->rejection_reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-20 text-center">
                                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm ring-1 ring-slate-100">
                                    <x-heroicon-o-credit-card class="text-5xl size-5" />
                                </div>
                                <h3 class="mt-8 text-2xl font-semibold tracking-tight text-slate-900">No Invoices Found</h3>
                                <p class="mt-3 text-sm font-semibold text-slate-500">Your billing history is currently clear.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($dues->hasPages())
                        <div class="mt-10 rounded-xl border border-slate-100 bg-white p-4 shadow-sm flex justify-center">
                            {{ $dues->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>
</x-layouts.dashboard>
