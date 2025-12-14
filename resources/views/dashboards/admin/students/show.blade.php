@php($title = ($student->fullname ?? $student->username) . ' · Student profile')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                    <i class="ri-user-3-line text-base" aria-hidden="true"></i>
                    Student profile
                </p>
                <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">{{ $student->fullname ?? $student->username }}</h1>
                <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                        <i class="ri-at-line text-sm"></i>
                        {{ $student->username }}
                    </span>
                    @if ($student->index_number)
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                            <i class="ri-hashtag text-sm"></i>
                            {{ $student->index_number }}
                        </span>
                    @endif
                    @if ($student->class)
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                            <i class="ri-community-line text-sm"></i>
                            {{ $student->class }}
                        </span>
                    @endif
                    @if ($student->year)
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                            <i class="ri-calendar-2-line text-sm"></i>
                            Year {{ $student->year }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3 sm:justify-end">
                <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <i class="ri-pencil-line text-base" aria-hidden="true"></i>
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student account? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                        <i class="ri-delete-bin-line text-base" aria-hidden="true"></i>
                        Delete
                    </button>
                </form>
            </div>
        </header>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                <h2 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Contact & identity</h2>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-user-line text-base text-[#16136a]" aria-hidden="true"></i> Full name</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->fullname ?? '—' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-mail-line text-base text-[#16136a]" aria-hidden="true"></i> Email</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->email }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-phone-line text-base text-[#16136a]" aria-hidden="true"></i> Phone number</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->phone_number ?? '—' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-hashtag text-base text-[#16136a]" aria-hidden="true"></i> Reference number</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->index_number ?? '—' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-calendar-line text-base text-[#16136a]" aria-hidden="true"></i> Created</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->created_at?->format('M j, Y · g:i A') ?? '—' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-time-line text-base text-[#16136a]" aria-hidden="true"></i> Last updated</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->updated_at?->format('M j, Y · g:i A') ?? '—' }}</dd>
                    </div>
                </dl>
            </article>

            <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
                <h2 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Academic placement</h2>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-book-open-line text-base text-[#16136a]" aria-hidden="true"></i> Class</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->class ?? '—' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-6">
                        <dt class="flex items-center gap-2 text-slate-400"><i class="ri-medal-line text-base text-[#16136a]" aria-hidden="true"></i> Year</dt>
                        <dd class="text-right font-medium text-slate-900">{{ $student->year ? 'Year ' . $student->year : '—' }}</dd>
                    </div>
                </dl>
            </article>
        </section>

        <section class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/5">
            <h2 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Admin actions</h2>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <span>Edit profile & credentials</span>
                    <i class="ri-arrow-right-line text-sm"></i>
                </a>
                <a href="mailto:{{ $student->email }}" class="inline-flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <span>Contact student via email</span>
                    <i class="ri-mail-line text-sm"></i>
                </a>
                <a href="tel:{{ $student->phone_number }}" class="inline-flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a] @if(!$student->phone_number) pointer-events-none opacity-50 @endif">
                    <span>Call phone number</span>
                    <i class="ri-phone-line text-sm"></i>
                </a>
                <a href="{{ route('admin.students.index') }}" class="inline-flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <span>Back to student list</span>
                    <i class="ri-list-unordered text-sm"></i>
                </a>
            </div>
        </section>
    </div>
</x-layouts.admin>
