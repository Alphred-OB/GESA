@php($title = $title ?? 'Edit timeline entry')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 text-center sm:text-left">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-edit-line text-base" aria-hidden="true"></i>
                Update milestone
            </p>
            <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Refine academic timeline entry</h1>
            <p class="text-sm text-slate-600">Adjust messaging, dates, or CTA, then toggle visibility for students.</p>
        </header>

        <section class="space-y-5 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <form method="POST" action="{{ route('admin.timeline.update', $entry) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('dashboards.admin.timeline.partials.form', ['entry' => $entry])

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.timeline.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-[#16136a]">
                        <i class="ri-arrow-go-back-line text-base" aria-hidden="true"></i>
                        Back
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                        <i class="ri-save-3-line text-base" aria-hidden="true"></i>
                        Update milestone
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.timeline.destroy', $entry) }}" onsubmit="return confirm('Delete this timeline entry?');" class="flex justify-end">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 transition hover:border-rose-400 hover:bg-rose-100">
                    <i class="ri-delete-bin-6-line text-base" aria-hidden="true"></i>
                    Delete entry
                </button>
            </form>
        </section>
    </div>
</x-layouts.admin>
