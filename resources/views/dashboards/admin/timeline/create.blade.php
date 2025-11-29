@php($title = $title ?? 'Create timeline entry')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 text-center sm:text-left">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-time-line text-base" aria-hidden="true"></i>
                New milestone
            </p>
            <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Add a timeline checkpoint</h1>
            <p class="text-sm text-slate-600">Define the milestone window, add optional CTAs, and publish it instantly for students.</p>
        </header>

        <section class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <form method="POST" action="{{ route('admin.timeline.store') }}" class="space-y-6">
                @csrf
                @include('dashboards.admin.timeline.partials.form', ['entry' => $entry])

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.timeline.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-[#16136a]">
                        <i class="ri-arrow-go-back-line text-base" aria-hidden="true"></i>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                        <i class="ri-save-3-line text-base" aria-hidden="true"></i>
                        Save milestone
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
