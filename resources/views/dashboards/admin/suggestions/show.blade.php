@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $title = 'Suggestion · ' . ($suggestion->subject ?? 'Preview');
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.suggestions.index') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-600 transition hover:bg-slate-200">
                <i class="ri-arrow-left-line text-base" aria-hidden="true"></i>
                Back to suggestions
            </a>
        </nav>

        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-message-2-line text-base" aria-hidden="true"></i>
                        Student suggestion
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a]">{{ $suggestion->subject }}</h1>
                </div>
                @php
                    $status = strtolower($suggestion->status ?? 'pending');
                    $badgeMap = [
                        'pending' => 'bg-amber-100 text-amber-800',
                        'in_review' => 'bg-blue-100 text-blue-800',
                        'resolved' => 'bg-emerald-100 text-emerald-800',
                        'dismissed' => 'bg-rose-100 text-rose-700',
                    ];
                    $badgeClass = $badgeMap[$status] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.25em] {{ $badgeClass }}">
                    <i class="ri-checkbox-circle-line text-sm"></i>
                    {{ $statuses[$suggestion->status] ?? Str::headline($suggestion->status) }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                <span class="inline-flex items-center gap-2">
                    <i class="ri-user-line" aria-hidden="true"></i>
                    {{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Unknown student' }}
                    @if ($suggestion->user?->email)
                        <span class="text-slate-400">·</span>
                        {{ $suggestion->user?->email }}
                    @endif
                </span>
                <span class="inline-flex items-center gap-2">
                    <i class="ri-time-line" aria-hidden="true"></i>
                    Submitted {{ $suggestion->created_at?->format('M j, Y · g:i A') ?? '—' }}
                </span>
                <span class="inline-flex items-center gap-2">
                    <i class="ri-price-tag-3-line" aria-hidden="true"></i>
                    {{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}
                </span>
            </div>
        </header>

        <section class="space-y-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <h2 class="text-lg font-semibold text-slate-900">Message</h2>
            <div class="prose max-w-none whitespace-pre-line text-slate-700">
                {{ $suggestion->message }}
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <h2 class="text-lg font-semibold text-slate-900">Update status</h2>
            <form method="POST" action="{{ route('admin.suggestions.update', $suggestion) }}" class="mt-4 flex flex-col gap-3 md:flex-row md:items-center">
                @csrf
                @method('PUT')
                <label class="flex w-full flex-col gap-2 text-sm text-slate-600 md:w-64">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</span>
                    <select name="status" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($suggestion->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                    <i class="ri-save-line text-base"></i>
                    Save changes
                </button>
            </form>
            @if ($suggestion->handled_at)
                <p class="mt-3 text-xs uppercase tracking-[0.2em] text-slate-400">Last handled {{ $suggestion->handled_at->format('M j, Y · g:i A') }}</p>
            @endif
        </section>

        @if ($suggestion->attachment_path)
            <section class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-slate-900">Attachment</h2>
                <p class="mt-2 text-sm text-slate-600">Download the file provided by the student.</p>
                <a href="{{ Storage::disk('public')->url($suggestion->attachment_path) }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 rounded-2xl border border-[#16136a]/30 px-4 py-2 text-sm font-semibold text-[#16136a] transition hover:-translate-y-0.5 hover:border-[#16136a]">
                    <i class="ri-download-2-line text-base"></i>
                    Download attachment
                </a>
            </section>
        @endif
    </div>
</x-layouts.admin>
