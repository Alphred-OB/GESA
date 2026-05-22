@forelse ($events as $event)
    <tr class="transition hover:bg-[#16136a]/5">
        <td class="px-6 py-4 align-top">
            <div class="flex items-start gap-4">
                <div class="relative aspect-[16/9] w-40 overflow-hidden rounded-2xl bg-slate-100">
                    @if ($event->banner_url)
                        <img
                            src="{{ $event->banner_url }}"
                            alt="{{ $event->banner_alt ?? ($event->title . ' banner') }}"
                            loading="lazy"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#16136a]/5 via-slate-100 to-[#16136a]/5">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                                <x-heroicon-o-photo class="size-6" aria-hidden="true" />
                            </span>
                        </div>
                    @endif
                </div>

                <div class="space-y-1">
                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                    @if ($event->description)
                        <p class="text-xs text-slate-500 line-clamp-2">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                    @endif
                    <p class="text-xs text-slate-400">Created {{ optional($event->created_at)->diffForHumans() }}</p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 align-top text-sm text-slate-700">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-clock class="size-5 text-[#16136a]" aria-hidden="true" />
                    <span>{{ optional($event->start_at)->format('M j, Y · g:i A') ?? 'TBA' }}</span>
                </div>
                @if ($event->end_at)
                    <p class="flex items-center gap-2 text-xs text-slate-500">
                        <x-heroicon-o-clock class="size-4" aria-hidden="true" />
                        Ends {{ $event->end_at->format('M j, Y · g:i A') }}
                    </p>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 align-top">
            @if ($event->location)
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    <x-heroicon-s-map-pin class="size-4" aria-hidden="true" />
                    {{ $event->location }}
                </span>
            @else
                <span class="text-xs text-slate-400">TBA</span>
            @endif
        </td>
        <td class="px-6 py-4 align-top">
            <div class="flex flex-col gap-2">
                @if ($event->category)
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                        <x-heroicon-o-tag class="size-4" aria-hidden="true" />
                        {{ Str::headline($event->category) }}
                    </span>
                @else
                    <span class="text-xs text-slate-400">General</span>
                @endif

                @if ($event->cta_url)
                    <a href="{{ $event->cta_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-[#16136a] hover:underline">
                        <x-heroicon-o-arrow-top-right-on-square class="size-4" aria-hidden="true" />
                        Call-to-action
                    </a>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 align-top">
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200/70 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]" aria-label="Edit event">
                    <x-heroicon-o-pencil class="size-4" aria-hidden="true" />
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1 rounded-full border border-rose-200/70 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100" aria-label="Delete event">
                        <x-heroicon-o-trash class="size-4" aria-hidden="true" />
                        Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">
            <div class="mx-auto flex max-w-md flex-col items-center gap-4">
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                    <x-heroicon-o-calendar class="size-8" aria-hidden="true" />
                </span>
                <p class="text-base font-semibold text-slate-700">No events scheduled yet</p>
                <p class="text-sm text-slate-500">Get started by creating your first event. It will appear here once saved.</p>
                <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                    <x-heroicon-o-plus class="size-5" aria-hidden="true" />
                    Create event
                </a>
            </div>
        </td>
    </tr>
@endforelse
