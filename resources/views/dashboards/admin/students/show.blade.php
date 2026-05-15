@php($title = ($student->fullname ?? $student->username) . ' · Profile')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <header class="mb-8 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-5">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-[#16136a] text-3xl font-semibold text-white shadow-2xl shadow-[#16136a]/20">
                    {{ strtoupper(substr($student->fullname ?? $student->username, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                        <i class="ri-user-3-line text-xs"></i>
                        Student Profile
                    </div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">{{ $student->fullname ?? $student->username }}</h1>
                    <p class="text-sm font-medium text-slate-400">Ref: {{ $student->username }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                    <i class="ri-pencil-line text-lg"></i>
                    Edit Account
                </a>
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Permanently delete this student account?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-rose-100 bg-rose-50 px-6 text-sm font-semibold text-rose-600 transition-all hover:bg-rose-100 active:scale-95">
                        <i class="ri-delete-bin-line text-lg"></i>
                        Delete
                    </button>
                </form>
            </div>
        </header>

        <div class="grid gap-6 md:grid-cols-12">
            {{-- Identity & Contact Card --}}
            <article class="md:col-span-8 rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                <div class="mb-8 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-[#16136a]">
                        <i class="ri-fingerprint-line text-xl"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-[#16136a]">Identity & Contact</h2>
                </div>

                <div class="grid gap-8 sm:grid-cols-2">
                    <div class="space-y-1">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Full Name</p>
                        <p class="text-base font-semibold text-slate-900">{{ $student->fullname ?? 'Not provided' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Email Address</p>
                        <p class="text-base font-semibold text-slate-900">{{ $student->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Phone Number</p>
                        <p class="text-base font-semibold text-slate-900">{{ $student->phone_number ?? 'Not provided' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Index Number</p>
                        <p class="text-base font-semibold text-slate-900">{{ $student->index_number ?? 'Not provided' }}</p>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap gap-4 pt-8 border-t border-slate-50">
                    <a href="mailto:{{ $student->email }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-50 px-5 text-sm font-semibold text-slate-600 transition-all hover:bg-[#16136a]/5 hover:text-[#16136a]">
                        <i class="ri-mail-send-line text-lg"></i>
                        Send Email
                    </a>
                    @if($student->phone_number)
                        <a href="tel:{{ $student->phone_number }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-50 px-5 text-sm font-semibold text-slate-600 transition-all hover:bg-[#16136a]/5 hover:text-[#16136a]">
                            <i class="ri-phone-line text-lg"></i>
                            Call Student
                        </a>
                    @endif
                </div>
            </article>

            {{-- Academic Status Card --}}
            <article class="md:col-span-4 space-y-6">
                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-[#16136a]">
                            <i class="ri-graduation-cap-line text-xl"></i>
                        </span>
                        <h2 class="text-lg font-semibold text-[#16136a]">Academic</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Current Program</p>
                            <p class="text-base font-semibold text-slate-900">{{ $student->class ?? 'Unassigned' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Academic Year</p>
                            <div class="inline-flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2 text-base font-semibold text-[#16136a]">
                                Year {{ $student->year ?? '—' }}
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-50">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-2">Account Status</p>
                            @if($student->email_verified_at)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-emerald-600">
                                    <i class="ri-checkbox-circle-fill"></i>
                                    Active Account
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600">
                                    <i class="ri-time-fill"></i>
                                    Pending Setup
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-[#16136a] p-8 shadow-2xl shadow-[#16136a]/20 text-white">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-white/50 mb-4">System History</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-calendar-event-line text-white/40"></i>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/40 leading-none mb-1">Created On</p>
                                <p class="truncate text-xs font-semibold">{{ $student->created_at?->format('M j, Y · g:i A') ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="ri-history-line text-white/40"></i>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/40 leading-none mb-1">Last Update</p>
                                <p class="truncate text-xs font-semibold">{{ $student->updated_at?->format('M j, Y · g:i A') ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Action Center --}}
            <article class="md:col-span-12 rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                <div class="mb-8 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-[#16136a]">
                        <i class="ri-settings-4-line text-xl"></i>
                    </span>
                    <h2 class="text-lg font-semibold text-[#16136a]">Administrative Actions</h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ route('admin.students.edit', $student) }}" class="group flex flex-col gap-3 rounded-3xl border border-slate-100 bg-slate-50/30 p-5 transition-all hover:border-[#16136a]/30 hover:bg-white hover:shadow-lg">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm group-hover:bg-[#16136a] group-hover:text-white transition-colors">
                            <i class="ri-pencil-line"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Modify Account</p>
                            <p class="text-[11px] font-medium text-slate-400">Update personal or academic details.</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.students.index') }}" class="group flex flex-col gap-3 rounded-3xl border border-slate-100 bg-slate-50/30 p-5 transition-all hover:border-[#16136a]/30 hover:bg-white hover:shadow-lg">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm group-hover:bg-[#16136a] group-hover:text-white transition-colors">
                            <i class="ri-list-unordered"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Student Directory</p>
                            <p class="text-[11px] font-medium text-slate-400">Return to the full student list.</p>
                        </div>
                    </a>

                    <div class="group flex flex-col gap-3 rounded-3xl border border-slate-100 bg-slate-50/30 p-5 transition-all hover:border-[#16136a]/30 hover:bg-white hover:shadow-lg">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm group-hover:bg-[#16136a] group-hover:text-white transition-colors">
                            <i class="ri-lock-password-line"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Reset Password</p>
                            <p class="text-[11px] font-medium text-slate-400">Student can reset via login screen.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Permanently delete this account?');" class="contents">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="group flex flex-col items-start gap-3 rounded-3xl border border-rose-50 bg-rose-50/20 p-5 transition-all hover:border-rose-200 hover:bg-white hover:shadow-lg">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm group-hover:bg-rose-600 group-hover:text-white transition-colors text-rose-400">
                                <i class="ri-delete-bin-line"></i>
                            </span>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-rose-900">Delete Account</p>
                                <p class="text-[11px] font-medium text-rose-400">Warning: This cannot be undone.</p>
                            </div>
                        </button>
                    </form>
                </div>
            </article>
        </div>
    </div>
</x-layouts.admin>
