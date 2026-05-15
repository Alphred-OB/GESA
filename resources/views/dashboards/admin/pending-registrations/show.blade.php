@php($title = 'Review Request')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Navigation & Status --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.pending-registrations.index') }}" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm transition-all hover:bg-[#16136a] hover:text-white hover:shadow-lg hover:shadow-[#16136a]/20">
                        <i class="ri-arrow-left-line text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Review Registration</h1>
                        <p class="text-sm font-semibold text-slate-400">Request ID: #PR-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                @if($registration->status === 'pending')
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-amber-600 ring-1 ring-amber-100">
                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Waiting for Review
                    </span>
                @elseif($registration->status === 'approved')
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-600 ring-1 ring-emerald-100">
                        <i class="ri-checkbox-circle-fill"></i>
                        Registration Approved
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-rose-600 ring-1 ring-rose-100">
                        <i class="ri-close-circle-fill"></i>
                        Request Rejected
                    </span>
                @endif
            </div>

            <div class="grid gap-8 lg:grid-cols-12">
                {{-- Left Column: Dossier --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- Dossier Card --}}
                    <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                        <div class="border-b border-slate-50 bg-slate-50/30 px-8 py-6">
                            <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Student Dossier</h2>
                        </div>
                        
                        <div class="p-8">
                            <div class="grid gap-8 md:grid-cols-2">
                                <div class="space-y-6">
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Full Name</label>
                                        <p class="text-lg font-semibold text-slate-900">{{ $registration->first_name }} {{ $registration->last_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Preferred Username</label>
                                        <p class="text-base font-semibold text-[#16136a]">{{ $registration->username }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Reference Number</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $registration->index_number }}</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Contact Email</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $registration->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Phone Number</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $registration->phone_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Program & Level</label>
                                        <p class="text-base font-semibold text-slate-900">{{ $registration->class }} (Year {{ $registration->year }})</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 block mb-2">Statement of Reason</label>
                                <p class="text-sm font-medium leading-relaxed text-slate-600 italic">"{{ $registration->reason }}"</p>
                            </div>
                        </div>
                    </section>

                    {{-- Evidence Card --}}
                    @if($registration->student_id_path)
                        <section class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-xl shadow-slate-200/40">
                            <div class="border-b border-slate-50 bg-slate-50/30 px-8 py-6 flex items-center justify-between">
                                <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Identity Verification</h2>
                                <a href="{{ route('admin.pending-registrations.document', $registration) }}" target="_blank" class="text-[10px] font-semibold uppercase tracking-widest text-[#16136a] hover:underline">
                                    View Original <i class="ri-external-link-line ml-1"></i>
                                </a>
                            </div>
                            
                            <div class="p-8">
                                <div class="relative group rounded-3xl border-4 border-slate-50 overflow-hidden shadow-inner bg-slate-100">
                                    <img 
                                        src="{{ route('admin.pending-registrations.document', $registration) }}" 
                                        alt="Student Verification Document" 
                                        class="w-full h-auto transition-transform duration-500 group-hover:scale-105"
                                    />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ route('admin.pending-registrations.document', $registration) }}" target="_blank" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#16136a] shadow-xl">
                                            Open Document
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    {{-- Review History --}}
                    @if($registration->status !== 'pending')
                        <section class="rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-slate-50/50 p-8 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#16136a] shadow-sm mb-4">
                                <i class="ri-history-line text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 uppercase tracking-tight">Review Decision</h3>
                            <p class="mt-2 text-sm font-medium text-slate-500">This request was processed on {{ $registration->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
                            
                            @if($registration->admin_notes)
                                <div class="mt-6 inline-block rounded-2xl bg-white px-6 py-4 shadow-sm text-sm font-semibold text-slate-600 border border-slate-100">
                                    "{{ $registration->admin_notes }}"
                                </div>
                            @endif
                        </section>
                    @endif
                </div>

                {{-- Right Column: Administrative Controls --}}
                <aside class="lg:col-span-4 space-y-6">
                    {{-- Submission Metadata --}}
                    <div class="rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white">
                                <i class="ri-time-line text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/50">Submission Date</p>
                                <p class="text-lg font-semibold">{{ $registration->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-white/50 uppercase tracking-widest">Time</span>
                                <span class="font-semibold">{{ $registration->created_at->format('g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-white/50 uppercase tracking-widest">Wait Time</span>
                                <span class="font-semibold">{{ $registration->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Center --}}
                    @if($registration->status === 'pending')
                        <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                            <h3 class="text-sm font-semibold uppercase tracking-widest text-[#16136a] mb-6">Action Center</h3>

                            <div x-data="{ decision: null }">
                                {{-- Approval Flow --}}
                                <div class="space-y-4">
                                    <button 
                                        @click="decision = decision === 'approve' ? null : 'approve'"
                                        class="w-full flex h-14 items-center justify-between rounded-2xl px-6 text-sm font-semibold transition-all"
                                        :class="decision === 'approve' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-50 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600'"
                                    >
                                        <span class="uppercase tracking-widest">Approve Account</span>
                                        <i class="ri-checkbox-circle-fill text-xl"></i>
                                    </button>

                                    <div x-show="decision === 'approve'" x-collapse>
                                        <form method="POST" action="{{ route('admin.pending-registrations.approve', $registration) }}" class="space-y-4 pt-2">
                                            @csrf
                                            <textarea 
                                                name="notes" 
                                                rows="3" 
                                                placeholder="Approval notes (optional)..."
                                                class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 text-sm font-semibold outline-none focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                            ></textarea>
                                            <button type="submit" class="w-full h-12 rounded-2xl bg-emerald-500 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-emerald-500/20 transition-all hover:opacity-90 active:scale-95">
                                                Confirm Approval
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex items-center gap-4 py-2">
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-300">or</span>
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                    </div>

                                    <button 
                                        @click="decision = decision === 'reject' ? null : 'reject'"
                                        class="w-full flex h-14 items-center justify-between rounded-2xl px-6 text-sm font-semibold transition-all"
                                        :class="decision === 'reject' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600'"
                                    >
                                        <span class="uppercase tracking-widest">Reject Request</span>
                                        <i class="ri-close-circle-fill text-xl"></i>
                                    </button>

                                    <div x-show="decision === 'reject'" x-collapse>
                                        <form method="POST" action="{{ route('admin.pending-registrations.reject', $registration) }}" class="space-y-4 pt-2">
                                            @csrf
                                            <textarea 
                                                name="notes" 
                                                rows="3" 
                                                required
                                                placeholder="Reason for rejection (required)..."
                                                class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 text-sm font-semibold outline-none focus:bg-white focus:ring-4 focus:ring-rose-500/10 transition-all"
                                            ></textarea>
                                            <button type="submit" class="w-full h-12 rounded-2xl bg-rose-500 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-rose-500/20 transition-all hover:opacity-90 active:scale-95" onclick="return confirm('Reject this student request?')">
                                                Confirm Rejection
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Guidelines --}}
                    <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                        <h3 class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-4">Review Checklist</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-xs font-semibold text-slate-600">
                                <i class="ri-checkbox-circle-line text-emerald-500 mt-0.5"></i>
                                <span>Verify index number against department lists.</span>
                            </li>
                            <li class="flex items-start gap-3 text-xs font-semibold text-slate-600">
                                <i class="ri-checkbox-circle-line text-emerald-500 mt-0.5"></i>
                                <span>Cross-check full name and ID photo.</span>
                            </li>
                            <li class="flex items-start gap-3 text-xs font-semibold text-slate-600">
                                <i class="ri-checkbox-circle-line text-emerald-500 mt-0.5"></i>
                                <span>Ensure program and level are accurate.</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-layouts.admin>
