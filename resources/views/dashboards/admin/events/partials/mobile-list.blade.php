<ul class="divide-y divide-slate-200 bg-white">
    @forelse ($events as $event)
        <li class="space-y-4 px-4 py-5">
            <div class="relative mb-3 aspect-[16/9] w-full overflow-hidden rounded-2xl bg-slate-100">
                @if ($event->banner_url)
                    <img
                        src="{{ $event->banner_url }}"
                        alt="{{ $event->banner_alt ?? ($event->title . ' banner') }}"
                        loading="lazy"
                        class="h-full w-full object-cover"
                    >
                @else
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#16136a]/5 via-slate-100 to-[#16136a]/5">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <x-heroicon-o-photo class="size-7" aria-hidden="true" />
                        </span>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900">{{ $event->title }}</h3>
                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200/70 bg-white px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    <x-heroicon-o-pencil class="size-4" aria-hidden="true" />
                    Edit
                </a>
            </div>
            @if ($event->description)
                <p class="text-sm text-slate-600">{{ Str::limit(strip_tags($event->description), 160) }}</p>
            @endif

            <div class="flex flex-wrap gap-3 text-sm text-slate-600">
                <span class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                    <x-heroicon-s-clock class="size-4" aria-hidden="true" />
                    {{ optional($event->start_at)->format('M j, Y · g:i A') ?? 'TBA' }}
                </span>
                @if ($event->end_at)
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">
                        <x-heroicon-o-clock class="size-4" aria-hidden="true" />
                        Ends {{ $event->end_at->format('M j, Y · g:i A') }}
                    </span>
                @endif
                @if ($event->location)
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">
                        <x-heroicon-s-map-pin class="size-4" aria-hidden="true" />
                        {{ $event->location }}
                    </span>
                @endif
                @if ($event->category)
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                        <x-heroicon-o-tag class="size-4" aria-hidden="true" />
                        {{ Str::headline($event->category) }}
                    </span>
                @endif
                @if ($event->cta_url)
                    <a href="{{ $event->cta_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#16136a] shadow-sm hover:underline">
                        <x-heroicon-o-arrow-top-right-on-square class="size-4" aria-hidden="true" />
                        Call-to-action
                    </a>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1">
                    <x-heroicon-o-calendar-days class="size-5" aria-hidden="true" />
                    Created {{ optional($event->created_at)->diffForHumans() }}
                </span>
            </div>

            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event? This action cannot be undone.');" class="pt-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-rose-200/70 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100">
                    <x-heroicon-o-trash class="size-4" aria-hidden="true" />
                    Delete event
                </button>
            </form>
        </li>
    @empty
        <li class="px-4 py-16 text-center text-sm text-slate-500">
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
        </li>
    @endforelse
</ul>
