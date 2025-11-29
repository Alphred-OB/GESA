@props(['event'])

@php
    $startValue = old('start_at');
    if (! $startValue && $event?->start_at) {
        $startValue = $event->start_at->format('Y-m-d\TH:i');
    }

    $endValue = old('end_at');
    if (! $endValue && $event?->end_at) {
        $endValue = $event->end_at->format('Y-m-d\TH:i');
    }
@endphp

<div class="space-y-6">
    <div class="space-y-3 rounded-3xl border border-dashed border-[#16136a]/30 bg-[#16136a]/5 p-5" x-data="{
            preview: @js($event?->banner_url),
            fileName: '',
            chooseBanner(event) {
                const input = event?.target ?? event;
                const [file] = input?.files ?? [];
                if (!file) {
                    this.preview = null;
                    this.fileName = '';
                    return;
                }

                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = e => {
                    this.preview = e.target?.result;
                };
                reader.readAsDataURL(file);
            }
        }">
        <input id="banner_image" name="banner_image" type="file" accept="image/*" class="sr-only" @change="chooseBanner($event)">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-700">Event banner <span class="font-normal text-xs text-slate-500">(optional)</span></p>
                <p class="text-xs text-slate-500">Upload a WebP/JPEG/PNG flyer or banner (up to 5MB) to enrich the student-facing event card. Any aspect ratio works.</p>
            </div>
            @if ($event?->banner_url)
                <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-rose-600">
                    <input type="checkbox" name="remove_banner" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500" @change="if ($event.target.checked) { preview = null; fileName = ''; } else { preview = @js($event?->banner_url); }">
                    Remove existing banner
                </label>
            @endif
        </div>

        <div class="space-y-4">
            <label for="banner_image" x-show="!preview" x-cloak class="flex cursor-pointer items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-[#16136a] shadow-sm transition hover:border-[#16136a]/50 hover:-translate-y-0.5">
                <span class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center rounded-full bg-[#16136a]/10 p-2 text-[#16136a]">
                        <i class="ri-image-add-line text-lg" aria-hidden="true"></i>
                    </span>
                    Choose banner image
                </span>
                <i class="ri-upload-2-line text-xl" aria-hidden="true"></i>
            </label>

            <div x-show="preview" x-cloak class="space-y-4 md:grid md:grid-cols-[minmax(0,1fr)_minmax(0,0.75fr)] md:items-start md:gap-5">
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="relative h-48 w-full bg-slate-100">
                        <template x-if="preview">
                            <img :src="preview" alt="{{ $event?->banner_alt ?? 'Event banner preview' }}" class="h-full w-full object-cover" loading="lazy">
                        </template>
                        <template x-if="!preview">
                            <div class="flex h-full items-center justify-center text-xs text-slate-400">No banner selected</div>
                        </template>
                    </div>

                    <label for="banner_image" class="absolute bottom-3 right-3 inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/95 text-[#16136a] shadow-md shadow-[#16136a]/20 transition hover:scale-105 hover:bg-white">
                        <i class="ri-upload-cloud-2-line text-lg" aria-hidden="true"></i>
                        <span class="sr-only">Choose banner image</span>
                    </label>
                </div>

                <div class="space-y-2">
                    <label for="banner_alt" class="text-sm font-semibold text-slate-700">Alt text</label>
                    <input id="banner_alt" name="banner_alt" type="text" maxlength="150" value="{{ old('banner_alt', $event->banner_alt) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="Describe the banner visuals for screen readers">
                </div>

                <p x-show="fileName" x-cloak class="text-xs text-slate-500 md:col-span-2">Selected: <span class="font-semibold text-slate-700" x-text="fileName"></span></p>
            </div>
        </div>

        @error('banner_image')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @error('banner_alt')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="title" class="text-sm font-semibold text-slate-700">Event title</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-calendar-event-fill text-base" aria-hidden="true"></i>
                </span>
                <input id="title" name="title" type="text" required maxlength="150" value="{{ old('title', $event->title) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="e.g. Orientation Mixer" />
            </div>
            @error('title')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="category" class="text-sm font-semibold text-slate-700">Category</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-price-tag-3-line text-base" aria-hidden="true"></i>
                </span>
                <input id="category" name="category" type="text" maxlength="80" value="{{ old('category', $event->category) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="e.g. Admissions" />
            </div>
            @error('category')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="start_at" class="text-sm font-semibold text-slate-700">Start date & time</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-time-fill text-base" aria-hidden="true"></i>
                </span>
                <input id="start_at" name="start_at" type="datetime-local" required value="{{ $startValue }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" />
            </div>
            @error('start_at')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="end_at" class="text-sm font-semibold text-slate-700">End date & time</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-timer-2-line text-base" aria-hidden="true"></i>
                </span>
                <input id="end_at" name="end_at" type="datetime-local" value="{{ $endValue }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" />
            </div>
            @error('end_at')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="location" class="text-sm font-semibold text-slate-700">Location</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-map-pin-2-fill text-base" aria-hidden="true"></i>
                </span>
                <input id="location" name="location" type="text" maxlength="150" value="{{ old('location', $event->location) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="e.g. Main Auditorium" />
            </div>
            @error('location')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="cta_url" class="text-sm font-semibold text-slate-700">CTA link</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#16136a]">
                    <i class="ri-external-link-line text-base" aria-hidden="true"></i>
                </span>
                <input id="cta_url" name="cta_url" type="url" maxlength="255" value="{{ old('cta_url', $event->cta_url) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="https://" />
            </div>
            @error('cta_url')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="space-y-2">
        <label for="description" class="text-sm font-semibold text-slate-700">Description</label>
        <textarea id="description" name="description" rows="6" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" placeholder="Add agenda, speaker details, or submission requirements">{{ old('description', $event->description) }}</textarea>
        @error('description')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
