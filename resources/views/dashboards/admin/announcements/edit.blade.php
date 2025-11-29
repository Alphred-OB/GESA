@php
    use Illuminate\Support\Str;
    $title = 'Edit announcement';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-edit-2-line text-base" aria-hidden="true"></i>
                Update announcement
            </p>
            <h1 class="text-3xl font-semibold text-[#16136a]">Edit “{{ Str::limit($announcement->title, 80) }}”</h1>
            <p class="text-sm text-slate-600">Adjust the content or targeting. Students will immediately see the updated message in their portal.</p>
        </header>

        @include('dashboards.admin.announcements.partials.form', [
            'announcement' => $announcement,
            'types' => $types,
            'priorities' => $priorities,
            'targetTypes' => $targetTypes,
            'options' => $options,
            'targetFilters' => $targetFilters,
        ])
    </div>
</x-layouts.admin>
