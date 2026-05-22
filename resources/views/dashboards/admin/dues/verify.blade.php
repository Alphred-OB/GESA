@php($title = 'Vette Payment')

<x-layouts.admin :title="$title">
    {{-- Pause sidebar polling while admin is on this verification page --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('verification-in-progress', { detail: { paused: true } }));
        });
        window.addEventListener('beforeunload', () => {
            window.dispatchEvent(new CustomEvent('verification-in-progress', { detail: { paused: false } }));
        });
    </script>

    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Navigation & Status --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dues.verifications') }}" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm transition-all hover:bg-[#16136a] hover:text-white hover:shadow-lg hover:shadow-[#16136a]/20">
                        <x-heroicon-o-arrow-left class="size-6" />
                    </a>
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Vette Payment</h1>
                        <p class="text-sm font-semibold text-slate-400">Transaction ID: #TXN-{{ str_pad($due->due_id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-amber-600 ring-1 ring-amber-100">
                    <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Reviewing Proof
                </span>
            </div>

            <div class="grid gap-8 lg:grid-cols-12">
                {{-- Left Column: Dossier --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- Student Context Card --}}
                    <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                        <div class="border-b border-slate-50 bg-slate-50/30 px-8 py-6">
                            <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Student Profile</h2>
                        </div>
                        
                        <div class="p-8">
                            <div class="grid gap-8 md:grid-cols-2">
                                <div class="space-y-6">
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Full Name</label>
                                        <p class="text-lg font-semibold text-slate-900">{{ $due->student?->fullname }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Reference Number</label>
                                        <p class="text-base font-semibold text-[#16136a]">{{ $due->student?->username }}</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Programme & Level</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $due->student?->class }} (Year {{ $due->student?->year }})</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Academic Year</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $due->academic_year }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Proof of Payment --}}
                    <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                        <div class="border-b border-slate-50 bg-slate-50/30 px-8 py-6 flex items-center justify-between">
                            <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Transaction Evidence</h2>
                            <a href="{{ Storage::disk('public')->url($due->payment_proof) }}" target="_blank" class="text-[10px] font-semibold uppercase tracking-widest text-[#16136a] hover:underline">
                                View Original <x-heroicon-o-arrow-top-right-on-square class="ml-1 size-5" />
                            </a>
                        </div>
                        
                        <div class="p-8">
                            <div class="relative group rounded-3xl border-4 border-slate-50 overflow-hidden shadow-inner bg-slate-100 min-h-[500px] flex items-center justify-center">
                                @if($due->payment_proof)
                                    <img 
                                        src="{{ Storage::disk('public')->url($due->payment_proof) }}" 
                                        alt="Payment Proof" 
                                        class="max-w-full h-auto transition-transform duration-500 group-hover:scale-105"
                                    />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ Storage::disk('public')->url($due->payment_proof) }}" target="_blank" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#16136a] shadow-xl">
                                            Open Evidence
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center p-12">
                                        <x-heroicon-o-photo class="text-6xl text-slate-200 size-5" />
                                        <p class="mt-4 text-sm font-semibold text-slate-400">No evidence uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Right Column: Vetting Controls --}}
                <aside class="lg:col-span-4 space-y-6">
                    {{-- Transaction Summary --}}
                    <div class="rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-white/50 mb-6 italic">Payment Summary</p>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/40 mb-1">Claimed Amount</p>
                                <p class="text-3xl font-semibold">GHS {{ number_format((float) $due->amount, 2) }}</p>
                            </div>
                            <div class="h-px bg-white/10"></div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/40 mb-1">Due Description</p>
                                <p class="text-sm font-semibold leading-relaxed">{{ $due->description }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Decision Matrix --}}
                    <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40" x-data="{ decision: 'approve' }">
                        <h3 class="text-sm font-semibold uppercase tracking-widest text-[#16136a] mb-6">Decision Matrix</h3>

                        <div class="flex p-1 bg-slate-50 rounded-2xl mb-8">
                            <button 
                                @click="decision = 'approve'"
                                :class="decision === 'approve' ? 'bg-white shadow-md text-[#16136a]' : 'text-slate-400'"
                                class="flex-1 py-3 text-xs font-semibold uppercase tracking-widest rounded-xl transition-all"
                            >Approve</button>
                            <button 
                                @click="decision = 'reject'"
                                :class="decision === 'reject' ? 'bg-white shadow-md text-rose-600' : 'text-slate-400'"
                                class="flex-1 py-3 text-xs font-semibold uppercase tracking-widest rounded-xl transition-all"
                            >Reject</button>
                        </div>

                        {{-- Approval Form --}}
                        <div x-show="decision === 'approve'" x-collapse>
                            <form method="POST" action="{{ route('admin.dues.approve', $due) }}" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Internal Note (Optional)</label>
                                    <textarea 
                                        name="verification_notes" 
                                        rows="3" 
                                        placeholder="Add a note about this transaction..."
                                        class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 text-sm font-semibold outline-none focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                    ></textarea>
                                </div>
                                <button type="submit" class="w-full h-14 rounded-2xl bg-emerald-500 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-emerald-500/20 transition-all hover:opacity-90 active:scale-95">
                                    Confirm Payment
                                </button>
                            </form>
                        </div>

                        {{-- Rejection Form --}}
                        <div x-show="decision === 'reject'" x-collapse>
                            <form method="POST" action="{{ route('admin.dues.reject', $due) }}" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-rose-500">Reason for Rejection (Required)</label>
                                    <textarea 
                                        name="rejection_reason" 
                                        rows="4" 
                                        required
                                        placeholder="Explain why this payment is being rejected..."
                                        class="w-full rounded-2xl border-rose-100 bg-rose-50/30 p-4 text-sm font-semibold text-rose-900 outline-none focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all"
                                    ></textarea>
                                </div>
                                <button type="submit" class="w-full h-14 rounded-2xl bg-rose-500 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-rose-500/20 transition-all hover:opacity-90 active:scale-95" onclick="return confirm('Reject this student payment?')">
                                    Submit Rejection
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Vetting Guidelines --}}
                    <div class="rounded-[2.5rem] border border-blue-100 bg-blue-50/30 p-8">
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-blue-800 mb-4 flex items-center gap-2">
                            <x-heroicon-o-information-circle class="size-5" />
                            Vetting Guidelines
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-xs font-semibold text-blue-700/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400 mt-1.5 shrink-0"></span>
                                <span>Verify the transaction date matches the submission.</span>
                            </li>
                            <li class="flex items-start gap-3 text-xs font-semibold text-blue-700/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400 mt-1.5 shrink-0"></span>
                                <span>Ensure the reference number on the receipt is valid.</span>
                            </li>
                            <li class="flex items-start gap-3 text-xs font-semibold text-blue-700/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400 mt-1.5 shrink-0"></span>
                                <span>Check for signs of image manipulation.</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.admin>

