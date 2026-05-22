@php
    use Illuminate\Support\Str;
    $title = 'Edit announcement';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl px-6 py-8 lg:px-8">
        <header class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]/50">
                    <x-heroicon-o-pencil class="size-5" />
                    Edit
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Edit Announcement</h1>
                <p class="max-w-xl text-sm font-medium text-slate-500">Update your message for <span class="text-[#16136a]">“{{ Str::limit($announcement->title, 40) }}”</span>.</p>
            </div>
            <div class="flex">
                <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] transition-opacity hover:opacity-70">
                    <x-heroicon-o-arrow-left class="size-5" />
                    Back to Announcements
                </a>
            </div>
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
