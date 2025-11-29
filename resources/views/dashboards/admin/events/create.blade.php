@php($title = $title ?? 'Create event')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 text-center sm:text-left">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-calendar-line text-base" aria-hidden="true"></i>
                New admin event
            </p>
            <h1 class="text-3xl font-semibold text-[#16136a]">Create an event</h1>
            <p class="text-sm text-slate-600">Share upcoming activities with students and staff. All fields can be updated later.</p>
        </header>

        <section class="rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                @include('dashboards.admin.events.partials.form', ['event' => $event])

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-6 sm:flex-row sm:justify-between">
                    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200/70 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                        <i class="ri-arrow-left-line text-base" aria-hidden="true"></i>
                        Back to events
                    </a>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                            <i class="ri-save-3-line text-base" aria-hidden="true"></i>
                            Save event
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
