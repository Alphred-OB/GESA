@php $title = $title ?? 'Review registration'; @endphp

<x-layouts.admin :title="$title">
    @php
        $student = $registration->student;
        $statusStyles = [
            'approved' => ['pill' => 'bg-emerald-50 text-emerald-700', 'icon' => 'ri-checkbox-circle-line'],
            'rejected' => ['pill' => 'bg-rose-50 text-rose-600', 'icon' => 'ri-close-circle-line'],
            'submitted' => ['pill' => 'bg-sky-50 text-sky-700', 'icon' => 'ri-time-line'],
            'in_progress' => ['pill' => 'bg-amber-50 text-amber-700', 'icon' => 'ri-draft-line'],
            'default' => ['pill' => 'bg-slate-100 text-slate-600', 'icon' => 'ri-information-line'],
        ];
        $style = $statusStyles[$registration->status] ?? $statusStyles['default'];
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5 md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <a href="{{ route('admin.course-registrations.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.3em] text-[#16136a]/70 transition hover:text-[#16136a]">
                    <i class="ri-arrow-go-back-line"></i>
                    Back to registrations
                </a>
                <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">{{ $student?->fullname ?? $student?->username ?? 'Unknown student' }}</h1>
                <p class="text-sm text-slate-600">{{ $student?->email }} · {{ $student?->class }} · Year {{ $student?->year }}</p>
            </div>
            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                <span class="inline-flex items-center gap-2 rounded-full px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $style['pill'] }}">
                    <i class="{{ $style['icon'] }} text-sm"></i>
                    {{ Str::headline($registration->status) }}
                </span>
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

        <div class="grid gap-8 lg:grid-cols-3">
            <section class="space-y-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5 lg:col-span-2">
                <header class="space-y-2 border-b border-slate-200 pb-4">
                    <h2 class="text-lg font-semibold text-[#16136a]">Uploaded documents</h2>
                    <p class="text-sm text-slate-500">Review the PDF submitted by the student before approving or rejecting the request.</p>
                </header>

                <div class="space-y-4 text-sm text-slate-600">
                    @if ($documents->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach ($documents as $doc)
                                <li class="flex flex-col gap-3 rounded-2xl border border-slate-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                                            <i class="ri-file-pdf-line text-lg"></i>
                                        </span>
                                        <div class="space-y-1">
                                            <a href="{{ $doc['url'] }}" target="_blank" rel="noopener" class="font-semibold text-[#16136a] hover:underline">{{ $doc['name'] }}</a>
                                            <p class="text-xs text-slate-500">Uploaded {{ optional($registration->submitted_at)->diffForHumans() ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ $doc['url'] }}" download class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                            <i class="ri-download-2-line text-sm"></i>
                                            Download
                                        </a>
                                        <a href="{{ $doc['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                            <i class="ri-eye-line text-sm"></i>
                                            Preview
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-5 py-10 text-center text-sm text-slate-500">
                            <i class="ri-file-forbid-line text-3xl text-slate-300"></i>
                            <p class="mt-3 font-semibold text-slate-600">No PDF uploaded yet.</p>
                            <p>The student has not provided a registration document.</p>
                        </div>
                    @endif
                </div>
            </section>

            <section class="space-y-6 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                <header class="space-y-2 border-b border-slate-200 pb-4">
                    <h2 class="text-lg font-semibold text-[#16136a]">Review decision</h2>
                    <p class="text-sm text-slate-500">Update the registration status and leave a note. Students can see your comment instantly.</p>
                </header>

                <form method="POST" action="{{ url('/admin/course-registrations/'.$registration->getKey()) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="status" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                        <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption }}" @selected(old('status', $registration->status) === $statusOption)>{{ Str::headline($statusOption) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="admin_comment" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Comment</label>
                        <textarea id="admin_comment" name="admin_comment" rows="5" placeholder="Share feedback or next steps" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">{{ old('admin_comment', $registration->admin_comment) }}</textarea>
                        @error('admin_comment')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-400">This note is visible to the student. Keep it clear and actionable.</p>
                    </div>

                    <div class="space-y-4 rounded-2xl border border-slate-200/80 bg-slate-50/80 px-4 py-4 text-xs text-slate-500">
                        <p class="font-semibold text-slate-600">Submission details</p>
                        <ul class="space-y-2">
                            <li class="flex items-center justify-between"><span>Submitted</span><span>{{ $registration->submitted_at ? $registration->submitted_at->format('M j, Y · g:i A') : '—' }}</span></li>
                            <li class="flex items-center justify-between"><span>Approved</span><span>{{ $registration->approved_at ? $registration->approved_at->format('M j, Y · g:i A') : 'Pending' }}</span></li>
                            <li class="flex items-center justify-between"><span>Pending documents</span><span>{{ $registration->pending_documents ?? 0 }}</span></li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.course-registrations.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                            <i class="ri-save-3-line text-base" aria-hidden="true"></i>
                            Save decision
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.admin>
