<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-4xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8">
            <header class="h-32 w-full animate-pulse rounded-3xl bg-slate-100"></header>
            <div class="h-96 w-full animate-pulse rounded-3xl bg-slate-50"></div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-600">
                        <i class="ri-shield-check-line text-base"></i>
                        Verification Pending
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Verify Manual Payment</h1>
                    <p class="text-sm text-slate-600">Review the uploaded proof for <span class="font-bold">{{ $due->student->fullname }}</span>.</p>
                </div>
                <div>
                    <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i class="ri-arrow-left-line"></i>
                        Back to list
                    </a>
                </div>
            </header>

            <div class="grid gap-8 lg:grid-cols-5">
                <!-- Payment Info & Actions -->
                <div class="lg:col-span-2 space-y-6">
                    <section class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                        <h2 class="text-lg font-semibold text-[#16136a]">Payment Details</h2>
                        <dl class="mt-4 space-y-4">
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Description</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700">{{ $due->description }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Amount to Verify</dt>
                                <dd class="mt-1 text-xl font-bold text-[#16136a]">GHS {{ number_format((float) $due->amount, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Reference Number</dt>
                                <dd class="mt-1 text-sm font-mono text-slate-700">{{ $due->student?->username ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Submitted On</dt>
                                <dd class="mt-1 text-sm text-slate-700">{{ $due->updated_at->format('M j, Y @ H:i') }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section x-data="{ action: 'approve' }" class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                        <div class="flex p-1 bg-slate-100 rounded-2xl mb-6">
                            <button @click="action = 'approve'" :class="action === 'approve' ? 'bg-white shadow text-[#16136a]' : 'text-slate-500'" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition">Approve</button>
                            <button @click="action = 'reject'" :class="action === 'reject' ? 'bg-white shadow text-rose-600' : 'text-slate-500'" class="flex-1 py-2 text-xs font-bold uppercase tracking-widest rounded-xl transition">Reject</button>
                        </div>

                        <!-- Approve Form -->
                        <form x-show="action === 'approve'" action="{{ route('admin.dues.approve', $due) }}" method="POST" class="space-y-4" onsubmit="console.log('Form submitting to:', this.action); return true;">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500">Verification Notes (Optional)</label>
                                <textarea name="verification_notes" rows="3" class="w-full rounded-2xl border border-slate-200 p-4 text-sm focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]" placeholder="Anything to note about this approval..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center gap-3 rounded-2xl bg-emerald-600 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-emerald-600/20 transition hover:-translate-y-1 hover:bg-emerald-700">
                                <i class="ri-checkbox-circle-fill text-lg"></i>
                                Approve Payment
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form x-show="action === 'reject'" action="{{ route('admin.dues.reject', $due) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-rose-500">Rejection Reason (Required)</label>
                                <textarea name="rejection_reason" rows="4" required class="w-full rounded-2xl border border-rose-200 p-4 text-sm focus:border-rose-500 focus:ring-1 focus:ring-rose-500" placeholder="Why is this payment being rejected? e.g. Invalid screenshot, amount mismatch..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center gap-3 rounded-2xl bg-rose-600 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-rose-600/20 transition hover:-translate-y-1 hover:bg-rose-700">
                                <i class="ri-close-circle-fill text-lg"></i>
                                Reject Payment
                            </button>
                        </form>
                    </section>
                </div>

                <!-- Proof Column -->
                <div class="lg:col-span-3">
                    <section class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-[#16136a]">Payment Proof</h2>
                            <a href="{{ Storage::disk('public')->url($due->payment_proof) }}" target="_blank" class="text-xs font-semibold text-[#16136a] hover:underline flex items-center gap-1">
                                <i class="ri-external-link-line"></i>
                                Open in new tab
                            </a>
                        </div>
                        
                        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 min-h-[400px] flex items-center justify-center">
                            @if($due->payment_proof)
                                <img src="{{ Storage::disk('public')->url($due->payment_proof) }}" alt="Payment Proof" class="max-w-full h-auto shadow-sm">
                            @else
                                <div class="text-center p-12">
                                    <i class="ri-image-line text-6xl text-slate-200"></i>
                                    <p class="mt-4 text-sm text-slate-400">No proof image found.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="mt-6 rounded-3xl border border-blue-100 bg-blue-50/30 p-6">
                        <h2 class="flex items-center gap-3 text-sm font-bold text-blue-800 uppercase tracking-widest">
                            <i class="ri-lightbulb-line"></i>
                            Verification Tip
                        </h2>
                        <p class="mt-3 text-xs text-blue-700 leading-relaxed">
                            Check the <strong>Transaction ID</strong>, <strong>Amount</strong>, and <strong>Date</strong> on the screenshot. Ensure the reference used matches the student's index number or name for security.
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
