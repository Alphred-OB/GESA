@php($title = 'Payment Verifications')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Payment Verifications</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Review & Confirm Manual Transactions</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Awaiting Action</span>
                        <span class="text-2xl font-semibold text-[#16136a]">{{ number_format($dues->total()) }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 mx-2"></div>
                    <a href="{{ route('admin.dues.index') }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-white px-6 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-md">
                        <i class="ri-history-line text-lg transition-transform group-hover:rotate-12"></i>
                        View All Dues
                    </a>
                </div>
            </header>

            {{-- Summary Grid --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Verification Volume</p>
                        <p class="mt-4 text-4xl font-semibold">{{ $dues->total() }}</p>
                        <p class="mt-2 text-xs font-semibold text-white/40 italic">Pending manual review</p>
                    </div>
                    <i class="ri-shield-user-line absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12"></i>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Total Value Pending</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">GHS {{ number_format((float) ($totals['outstanding'] ?? 0), 2) }}</p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Awaiting confirmation</p>
                </div>

                <div class="hidden lg:block rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Latest Submission</p>
                    @if($dues->first())
                        <p class="mt-4 text-lg font-semibold text-slate-900 truncate">{{ $dues->first()->student?->fullname }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-400">{{ $dues->first()->updated_at->diffForHumans() }}</p>
                    @else
                        <p class="mt-4 text-lg font-semibold text-slate-300 italic">No pending requests</p>
                    @endif
                </div>
            </div>

            {{-- Filters --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-200/40">
                <form action="{{ route('admin.dues.verifications') }}" method="GET" class="grid gap-6 md:grid-cols-4 lg:grid-cols-5">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Search Transaction</label>
                        <div class="relative">
                            <i class="ri-search-2-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, index number, or reference..." class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Academic Year</label>
                        <select name="academic_year" class="h-14 w-full rounded-2xl border-none bg-slate-50 px-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Years</option>
                            @foreach ($filtersMeta['academic_years'] as $yearOption)
                                <option value="{{ $yearOption }}" @selected(($filters['academic_year'] ?? '') === $yearOption)>{{ $yearOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-semibold uppercase tracking-widest text-slate-400">Programme</label>
                        <select name="class" class="h-14 w-full rounded-2xl border-none bg-slate-50 px-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            <option value="">All Programmes</option>
                            @foreach ($filtersMeta['classes'] as $classOption)
                                <option value="{{ $classOption }}" @selected(($filters['class'] ?? '') === $classOption)>{{ $classOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="h-14 flex-1 rounded-2xl bg-[#16136a] text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                            Filter
                        </button>
                        <a href="{{ route('admin.dues.verifications') }}" class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                            <i class="ri-refresh-line text-xl"></i>
                        </a>
                    </div>
                </form>
            </section>

            {{-- Main List --}}
            <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-50 bg-slate-50/30">
                                <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Student Identity</th>
                                <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 text-center">Year/Level</th>
                                <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Payment Reference</th>
                                <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Submitted</th>
                                <th class="px-8 py-5 text-right text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Vetting</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($dues as $due)
                                <tr class="group transition-colors hover:bg-slate-50/50">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 font-semibold text-[#16136a]">
                                                {{ substr($due->student?->username ?? 'S', 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $due->student?->fullname ?? 'Unknown Student' }}</p>
                                                <p class="truncate text-[10px] font-semibold text-slate-400 uppercase tracking-tight">{{ $due->student?->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600 uppercase">
                                            Year {{ $due->student?->year ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-semibold text-slate-600 font-mono">{{ $due->payment_reference ?? $due->reference_number ?? '—' }}</p>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase">{{ $due->description }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-semibold text-[#16136a]">GHS {{ number_format((float) $due->amount, 2) }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-semibold text-slate-600">{{ $due->updated_at->format('M d, Y') }}</p>
                                        <p class="text-[10px] font-semibold text-slate-400">{{ $due->updated_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('admin.dues.verify-payment', $due) }}" class="inline-flex h-10 items-center gap-2 rounded-xl bg-[#16136a] px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                            Vette Payment
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-50 text-slate-200">
                                                <i class="ri-checkbox-multiple-line text-5xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-slate-900">All caught up!</p>
                                                <p class="text-sm font-semibold text-slate-400">No pending payments require verification at this time.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View --}}
                <div class="grid gap-4 p-4 md:hidden">
                    @foreach ($dues as $due)
                        <article class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#16136a]/5 font-semibold text-[#16136a]">
                                    {{ substr($due->student?->fullname ?? 'S', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">{{ $due->student?->fullname }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $due->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 rounded-2xl bg-slate-50 p-4 mb-4">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Amount</p>
                                    <p class="text-sm font-semibold text-[#16136a]">GHS {{ number_format((float) $due->amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Year</p>
                                    <p class="text-sm font-semibold text-slate-900">Year {{ $due->student?->year ?? '—' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.dues.verify-payment', $due) }}" class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20">
                                Vette Payment
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($dues->hasPages())
                    <div class="border-t border-slate-50 bg-slate-50/30 px-8 py-5">
                        {{ $dues->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
