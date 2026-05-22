<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header --}}
            <header class="text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-[#16136a]/5 text-[#16136a]">
                    <x-heroicon-o-book-open class="size-8" />
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">{{ $title }}</h1>
                <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Share knowledge across the engineering cohort</p>
            </header>

            @if ($errors->any())
                <div class="rounded-[2rem] border border-rose-100 bg-rose-50/50 p-6 text-sm font-semibold text-rose-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-exclamation-triangle class="size-7" />
                        <div>
                            <p class="text-xs uppercase tracking-widest">Validation Errors</p>
                            <ul class="mt-2 list-disc pl-5 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.resources.store') }}" enctype="multipart/form-data" class="space-y-8" 
                x-data="resourceForm({ resourceType: '{{ old('resource_type', $resource->resource_type) }}' })">
                @csrf

                {{-- Basic Details --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Resource Core Information</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="title" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Asset Title</label>
                            <div class="relative">
                                <x-heroicon-o-pencil class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                <input id="title" name="title" type="text" value="{{ old('title', $resource->title) }}" required maxlength="150" 
                                    class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" 
                                    placeholder="e.g. Applied Thermodynamics Handout">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="content_type" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Content Category</label>
                            <div class="relative">
                                <x-heroicon-o-rectangle-stack class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                <select id="content_type" name="content_type" class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    @foreach ($contentTypes as $type)
                                        <option value="{{ $type }}" @selected(old('content_type', $resource->content_type) === $type)>{{ Str::headline($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="description" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Asset Summary</label>
                            <div class="relative">
                                <x-heroicon-o-document-text class="absolute left-4 top-6 text-slate-400 size-5" />
                                <textarea id="description" name="description" rows="3" class="w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 py-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="Briefly describe what this resource covers...">{{ old('description', $resource->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Delivery & Payload --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Delivery Mode</h2>
                    
                    <div class="space-y-8">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <input type="hidden" name="resource_type" x-model="resourceType">
                            @foreach ($resourceTypes as $type)
                                <button type="button" 
                                    @click="resourceType = '{{ $type }}'"
                                    :class="resourceType === '{{ $type }}' ? 'bg-[#16136a] text-white' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                                    class="flex h-16 items-center justify-center gap-3 rounded-2xl text-xs font-semibold uppercase tracking-widest transition-all">
                                    <i class="ri-{{ $type === 'file' ? 'file-3-line' : 'links-line' }} text-xl"></i>
                                    {{ Str::headline($type) }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Link Inputs --}}
                        <div x-show="resourceType !== 'file'" x-cloak class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="cta_url" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Destination URL</label>
                                <div class="relative">
                                    <x-heroicon-o-link class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                    <input id="cta_url" name="cta_url" type="url" value="{{ old('cta_url', $resource->cta_url) }}" placeholder="https://" 
                                        class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="cta_label" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Button Label</label>
                                <div class="relative">
                                    <x-heroicon-o-pencil-square class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                    <input id="cta_label" name="cta_label" type="text" value="{{ old('cta_label', $resource->cta_label) }}" placeholder="e.g. Open Portal" 
                                        class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                </div>
                            </div>
                        </div>

                        {{-- File Upload --}}
                        <div x-show="resourceType === 'file'" x-cloak class="space-y-4">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Asset Upload</label>
                            <label for="file" class="group flex min-h-[200px] cursor-pointer flex-col items-center justify-center gap-4 rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-slate-50/50 transition-all hover:border-[#16136a]/40 hover:bg-white">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white text-[#16136a] shadow-xl shadow-slate-200/50 transition-transform group-hover:scale-110">
                                    <x-heroicon-o-cloud-arrow-up class="size-7" />
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-slate-900">Choose Resource File</p>
                                    <p class="mt-1 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Max 50MB · PDF, DOCX, ZIP</p>
                                </div>
                                <input id="file" name="file" type="file" class="hidden" x-ref="fileInput" @change="handleFileChange($event)">
                            </label>

                            <div x-show="filePreview" x-cloak class="flex items-center gap-4 rounded-3xl border border-emerald-100 bg-emerald-50/50 p-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-emerald-500 shadow-sm">
                                    <x-heroicon-o-document-check class="size-6" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-semibold text-emerald-900" x-text="filePreview?.name"></p>
                                    <p class="text-[10px] font-semibold text-emerald-600 uppercase" x-text="filePreview?.sizeLabel"></p>
                                </div>
                                <button type="button" @click="clearFile()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-rose-500 shadow-sm hover:bg-rose-500 hover:text-white transition-all">
                                    <x-heroicon-o-x-mark class="size-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Targeting --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Audience Visibility</h2>
                    <div class="grid gap-8 md:grid-cols-2">
                        <div class="space-y-4">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Target Classes</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($classOptions as $class)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="target_classes[]" value="{{ $class }}" class="peer sr-only" @change="toggleClass('{{ $class }}')" @checked(in_array($class, old('target_classes', $resource->target_classes ?? [])))>
                                        <div class="flex h-11 items-center px-5 rounded-xl bg-slate-50 text-[10px] font-semibold uppercase tracking-widest text-slate-400 transition-all peer-checked:bg-[#16136a] peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-[#16136a]/20">
                                            {{ $class }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Target Years</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($yearOptions as $year)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="target_years[]" value="{{ $year }}" class="peer sr-only" @change="toggleYear('{{ $year }}')" @checked(in_array($year, old('target_years', $resource->target_years ?? [])))>
                                        <div class="flex h-11 items-center px-5 rounded-xl bg-slate-50 text-[10px] font-semibold uppercase tracking-widest text-slate-400 transition-all peer-checked:bg-[#16136a] peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-[#16136a]/20">
                                            Year {{ $year }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex items-center justify-between rounded-3xl border border-slate-100 bg-slate-50/50 p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-tight text-[#16136a]">Release to Students</p>
                            <p class="mt-1 text-[10px] font-semibold text-slate-400">Immediate visibility upon saving</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="hidden" name="visibility" value="hidden">
                            <input type="checkbox" name="visibility" value="student" class="peer sr-only" @checked(old('visibility', $resource->visibility) === 'student')>
                            <div class="h-8 w-14 rounded-full bg-slate-200 transition-all peer-checked:bg-[#16136a] peer-focus:ring-4 peer-focus:ring-[#16136a]/10"></div>
                            <div class="absolute left-1 top-1 h-6 w-6 rounded-full bg-white shadow-sm transition-all peer-checked:translate-x-6"></div>
                        </label>
                    </div>
                </section>

                <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                    <button type="submit" class="flex h-14 flex-1 items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                        <x-heroicon-o-arrow-down-on-square class="size-5" />
                        Save Resource
                    </button>
                    <a href="{{ route('admin.resources.index') }}" class="flex h-14 items-center justify-center rounded-2xl bg-slate-50 px-8 text-sm font-semibold uppercase tracking-widest text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('resourceForm', (state) => ({
                resourceType: state.resourceType || 'link',
                selectedClasses: @js(old('target_classes', $resource->target_classes ?? [])),
                selectedYears: @js(old('target_years', $resource->target_years ?? [])),
                filePreview: null,
                toggleClass(value) {
                    const index = this.selectedClasses.indexOf(value);
                    if (index === -1) this.selectedClasses.push(value);
                    else this.selectedClasses.splice(index, 1);
                },
                toggleYear(value) {
                    const index = this.selectedYears.indexOf(value);
                    if (index === -1) this.selectedYears.push(value);
                    else this.selectedYears.splice(index, 1);
                },
                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (!file) return this.clearFile();
                    const sizeKb = file.size / 1024;
                    this.filePreview = {
                        name: file.name,
                        sizeLabel: sizeKb > 1024 ? `${(sizeKb / 1024).toFixed(1)} MB` : `${Math.round(sizeKb)} KB`
                    };
                },
                clearFile() {
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                    this.filePreview = null;
                }
            }));
        });
    </script>
</x-layouts.admin>
