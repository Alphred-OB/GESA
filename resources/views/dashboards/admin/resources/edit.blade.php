@php($title = $title ?? 'Edit resource')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-3 rounded-3xl border border-[#16136a]/15 bg-white/90 p-6 text-center shadow-lg shadow-[#16136a]/10 sm:text-left md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-[#16136a]">
                    <i class="ri-book-3-line text-base" aria-hidden="true"></i>
                    Academic resources
                </p>
                <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Update resource</h1>
                <p class="text-sm text-slate-600">Refine targeting, descriptions, or replace files to keep materials relevant.</p>
            </div>
            <a href="{{ route('admin.resources.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                <i class="ri-arrow-go-back-line text-base" aria-hidden="true"></i>
                Back to list
            </a>
        </header>

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200/60 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-inner">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                        <i class="ri-error-warning-line text-lg" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="font-semibold">Please resolve the highlighted fields.</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.resources.update', $resource) }}" enctype="multipart/form-data" class="space-y-8" x-data="resourceForm({
                resourceType: '{{ old('resource_type', $resource->resource_type) }}'
            })">
            @csrf
            @method('PUT')

            <section class="space-y-6 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Resource details</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="title" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Title</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-heading w-5" aria-hidden="true"></i>
                            </span>
                            <input id="title" name="title" type="text" value="{{ old('title', $resource->title) }}" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40" required>
                        </div>
                    </div>
                    <div>
                        <label for="content_type" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Content type</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-layer-group w-5" aria-hidden="true"></i>
                            </span>
                            <select id="content_type" name="content_type" class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40" required>
                                @foreach ($contentTypes as $type)
                                    <option value="{{ $type }}" @selected(old('content_type', $resource->content_type) === $type)>{{ Str::headline($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Description</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-4 text-slate-400">
                                <i class="fa-solid fa-align-left w-5" aria-hidden="true"></i>
                            </span>
                            <textarea id="description" name="description" rows="3" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">{{ old('description', $resource->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-6 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10" x-cloak>
                <h2 class="text-lg font-semibold text-[#16136a]">Delivery type</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="space-y-3">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Resource type</span>
                        @php($resourceTypeIcons = [
                            'link' => 'fa-link',
                            'file' => 'fa-cloud-arrow-up',
                            'video' => 'fa-film',
                            'handout' => 'fa-file-lines',
                            'past_question' => 'fa-lightbulb',
                            'default' => 'fa-layer-group',
                        ])
                        <div class="grid gap-2 sm:grid-cols-3">
                            @foreach ($resourceTypes as $type)
                                <label class="inline-flex items-center gap-2 rounded-2xl border px-4 py-2 text-sm font-semibold transition" :class="resourceType === '{{ $type }}' ? 'border-[#16136a] bg-[#16136a]/10 text-[#16136a]' : 'border-slate-200 text-slate-600 hover:border-[#16136a]/40 hover:text-[#16136a]'">
                                    <input type="radio" name="resource_type" value="{{ $type }}" class="sr-only" x-model="resourceType" @checked(old('resource_type', $resource->resource_type) === $type)>
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fa-solid {{ $resourceTypeIcons[$type] ?? $resourceTypeIcons['default'] }}" aria-hidden="true"></i>
                                        <span class="capitalize">{{ Str::headline($type) }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3" x-show="resourceType !== 'file'" x-cloak>
                        <label for="cta_url" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Link URL</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-link w-5" aria-hidden="true"></i>
                            </span>
                            <input id="cta_url" name="cta_url" type="url" value="{{ old('cta_url', $resource->cta_url) }}" placeholder="https://" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                        </div>
                        <label for="cta_label" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Link label</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-pen-to-square w-5" aria-hidden="true"></i>
                            </span>
                            <input id="cta_label" name="cta_label" type="text" value="{{ old('cta_label', $resource->cta_label) }}" placeholder="e.g. Open resource" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                        </div>
                    </div>
                    <div class="md:col-span-2" x-show="resourceType === 'file'" x-cloak>
                        <label for="file" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Upload file</label>
                        <label for="file" class="mt-2 flex w-full cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-[#16136a]/40 bg-[#16136a]/5 px-6 py-6 text-sm text-slate-600 hover:border-[#16136a]/60">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#16136a] shadow">
                                <i class="fa-solid fa-cloud-arrow-up w-5" aria-hidden="true"></i>
                            </span>
                            <span class="font-semibold text-[#16136a]">Choose resource file</span>
                            <span class="text-xs text-slate-500">Maximum 50MB · pdf, doc(x), ppt(x), xls(x), zip, mp4, mov, avi</span>
                            <input id="file" name="file" type="file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.mp4,.mov,.avi" x-ref="fileInput" @change="handleFileChange($event)">
                        </label>
                        <div x-show="filePreview" x-cloak class="mt-3 flex w-full items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex-shrink-0">
                                <template x-if="filePreview.isImage">
                                    <img :src="filePreview.url" alt="" class="h-10 w-10 rounded-lg object-cover">
                                </template>
                                <template x-if="!filePreview.isImage">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-[#16136a] shadow">
                                        <i class="fa-solid fa-file w-5" aria-hidden="true"></i>
                                    </div>
                                </template>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-800" x-text="filePreview.name"></p>
                                <p class="mt-0.5 text-xs text-slate-500" x-text="`${filePreview.sizeLabel} · ${filePreview.type || 'Unknown file type'}`"></p>
                            </div>
                            <button type="button" @click="clearFile()" class="text-xs font-semibold text-rose-600 transition hover:text-rose-700">
                                Remove
                            </button>
                        </div>
                        @if ($resource->file_path)
                            <p class="text-xs text-slate-500">Current file: <span class="font-medium text-[#16136a]">{{ basename($resource->file_path) }}</span></p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="space-y-6 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Audience targeting</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Target classes</span>
                        <p class="mt-1 text-xs text-slate-500">Leave all unchecked to show to every class.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($classOptions as $class)
                                <label class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold transition" :class="selectedClasses.includes('{{ $class }}') ? 'border-[#16136a] bg-[#16136a]/10 text-[#16136a]' : 'border-slate-200 text-slate-600 hover:border-[#16136a]/40 hover:text-[#16136a]'">
                                    <input type="checkbox" name="target_classes[]" value="{{ $class }}" class="sr-only" @change="toggleClass('{{ $class }}')" @checked(in_array($class, old('target_classes', $resource->target_classes ?? [])))>
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-people-group" aria-hidden="true"></i>
                                        <span>{{ $class }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Target years</span>
                        <p class="mt-1 text-xs text-slate-500">Leave all unchecked to show to all academic levels.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($yearOptions as $year)
                                <label class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold transition" :class="selectedYears.includes('{{ $year }}') ? 'border-[#16136a] bg-[#16136a]/10 text-[#16136a]' : 'border-slate-200 text-slate-600 hover:border-[#16136a]/40 hover:text-[#16136a]'">
                                    <input type="checkbox" name="target_years[]" value="{{ $year }}" class="sr-only" @change="toggleYear('{{ $year }}')" @checked(in_array($year, old('target_years', $resource->target_years ?? [])))>
                                    <span class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                                        <span>Year {{ $year }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.resources.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
                    <i class="ri-save-line text-base" aria-hidden="true"></i>
                    Update resource
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('resourceForm', (state) => ({
                resourceType: state.resourceType ?? 'link',
                selectedClasses: @js(old('target_classes', $resource->target_classes ?? [])),
                selectedYears: @js(old('target_years', $resource->target_years ?? [])),
                filePreview: null,
                toggleClass(value) {
                    this.selectedClasses = this.toggleArray(this.selectedClasses, value);
                },
                toggleYear(value) {
                    this.selectedYears = this.toggleArray(this.selectedYears, value);
                },
                toggleArray(list, value) {
                    const index = list.indexOf(value);
                    if (index === -1) {
                        list.push(value);
                    } else {
                        list.splice(index, 1);
                    }
                    return list;
                },
                handleFileChange(event) {
                    const files = event.target.files || [];
                    const file = files[0];
                    if (!file) {
                        this.clearFile();
                        return;
                    }

                    const sizeKb = file.size / 1024;
                    const sizeLabel = sizeKb > 1024
                        ? `${(sizeKb / 1024).toFixed(1)} MB`
                        : `${Math.round(sizeKb)} KB`;

                    if (this.filePreview && this.filePreview.url) {
                        URL.revokeObjectURL(this.filePreview.url);
                    }

                    const isImage = (file.type || '').startsWith('image/');

                    this.filePreview = {
                        name: file.name,
                        sizeLabel,
                        type: file.type || 'Unknown type',
                        isImage,
                        url: isImage ? URL.createObjectURL(file) : null,
                    };
                },
                clearFile() {
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                    if (this.filePreview && this.filePreview.url) {
                        URL.revokeObjectURL(this.filePreview.url);
                    }
                    this.filePreview = null;
                },
            }));
        });
    </script>
</x-layouts.admin>
