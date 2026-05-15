@php
    $title = $title ?? 'Academic Resources';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header Section --}}
            <header class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Resource Hub</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Curate academic materials for every cohort</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total Assets</span>
                        <span class="text-2xl font-semibold text-[#16136a]">{{ number_format($resources->total()) }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 mx-2"></div>
                    <a href="{{ route('admin.resources.create') }}" class="group flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <i class="ri-add-line text-lg transition-transform group-hover:rotate-90"></i>
                        New Resource
                    </a>
                </div>
            </header>

            {{-- Summary Cards (Bento Style) --}}
            @php
                $items = collect($resources->items());
                $fileCount = $items->where('resource_type', 'file')->count();
                $linkCount = $items->where('resource_type', '!=', 'file')->count();
                $categories = $items->pluck('content_type')->unique()->count();
            @endphp
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-xl shadow-[#16136a]/20">
                    <div class="relative z-10">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-white/50">Digital Repository</p>
                        <p class="mt-4 text-4xl font-semibold text-emerald-400">{{ $fileCount }} <span class="text-lg text-white/40 font-semibold uppercase tracking-widest">Files</span></p>
                        <p class="mt-2 text-xs font-semibold text-white/40 italic">Managed document assets</p>
                    </div>
                    <i class="ri-file-3-line absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12"></i>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">External Connections</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $linkCount }} <span class="text-lg text-slate-300 uppercase tracking-widest">Links</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Video tutorials & web resources</p>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Library Coverage</p>
                    <p class="mt-4 text-4xl font-semibold text-[#16136a]">{{ $categories }} <span class="text-lg text-slate-300 uppercase tracking-widest">Types</span></p>
                    <p class="mt-2 text-xs font-semibold text-slate-400">Handouts, Slides, & Guides</p>
                </div>
            </div>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Resource List --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Resource Library</h2>
                    <form method="GET" class="flex items-center gap-3">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Display</label>
                        <select name="per_page" onchange="this.form.submit()" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-xs font-semibold text-slate-600 outline-none focus:ring-2 focus:ring-[#16136a]/10">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @if($option === $currentPerPage) selected @endif>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    @forelse ($resources as $resource)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white p-6 transition-all hover:shadow-2xl hover:shadow-slate-200/60">
                            <div class="relative z-10 flex flex-col h-full">
                                <header class="flex items-start justify-between gap-4">
                                    <div @class([
                                        'flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl font-semibold transition-transform group-hover:scale-110 shadow-lg',
                                        'bg-[#16136a] text-white shadow-[#16136a]/20' => $resource->resource_type === 'file',
                                        'bg-blue-500 text-white shadow-blue-500/20' => $resource->resource_type !== 'file',
                                    ])>
                                        @php
                                            $icon = match($resource->content_type) {
                                                'video' => 'ri-play-circle-line',
                                                'lecture_slide' => 'ri-presentation-line',
                                                'past_question' => 'ri-question-answer-line',
                                                'handout' => 'ri-book-open-line',
                                                'guide' => 'ri-compass-3-line',
                                                'policy' => 'ri-scales-3-line',
                                                default => $resource->resource_type === 'file' ? 'ri-file-3-line' : 'ri-links-line'
                                            };
                                        @endphp
                                        <i class="{{ $icon }} text-2xl"></i>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                            {{ Str::headline($resource->content_type) }}
                                        </span>
                                        @if($resource->visibility === 'student')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-600">
                                                Live
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-rose-500">
                                                Draft
                                            </span>
                                        @endif
                                    </div>
                                </header>

                                <div class="mt-6 flex-1">
                                    <h3 class="text-xl font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-1">{{ $resource->title }}</h3>
                                    <p class="mt-2 text-sm font-semibold text-slate-400 line-clamp-2 leading-relaxed">{{ $resource->description }}</p>
                                    
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        @php
                                            $classes = (array) ($resource->target_classes ?? []);
                                            $years = (array) ($resource->target_years ?? []);
                                        @endphp
                                        
                                        @if (empty($classes) && empty($years))
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100/50 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-700">
                                                <i class="ri-global-line"></i> Global Audience
                                            </span>
                                        @endif

                                        @foreach ($classes as $classItem)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#16136a]/5 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-[#16136a]">
                                                {{ $classItem }}
                                            </span>
                                        @endforeach

                                        @foreach ($years as $yearItem)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-blue-600">
                                                Year {{ $yearItem }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <footer class="mt-8 flex items-center justify-between gap-4 pt-6 border-t border-slate-50">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.resources.edit', $resource) }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-[#16136a] hover:text-white">
                                            <i class="ri-edit-line text-lg"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource permanentely?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-400 transition-all hover:bg-rose-500 hover:text-white">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($resource->resource_type === 'file' && $resource->file_path)
                                        <a href="{{ $resource->download_url }}" target="_blank" class="flex h-10 items-center gap-2 rounded-xl bg-emerald-500 px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-emerald-500/20 transition-transform hover:-translate-y-0.5">
                                            <i class="ri-download-cloud-2-line"></i>
                                            Get File
                                        </a>
                                    @elseif($resource->cta_url)
                                        <a href="{{ $resource->cta_url }}" target="_blank" class="flex h-10 items-center gap-2 rounded-xl bg-blue-500 px-4 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-blue-500/20 transition-transform hover:-translate-y-0.5">
                                            <i class="ri-external-link-line"></i>
                                            {{ $resource->cta_label ?? 'View Link' }}
                                        </a>
                                    @endif
                                </footer>
                            </div>
                        </article>
                    @empty
                        <div class="md:col-span-2 rounded-[2.5rem] border border-dashed border-slate-300 p-20 text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 text-slate-200">
                                <i class="ri-book-mark-line text-5xl"></i>
                            </div>
                            <h3 class="mt-6 text-lg font-semibold text-slate-900">No Resources Found</h3>
                            <p class="mt-2 text-sm font-semibold text-slate-400">Start building your academic library for students.</p>
                            <a href="{{ route('admin.resources.create') }}" class="mt-8 inline-flex h-12 items-center gap-3 rounded-2xl bg-[#16136a] px-8 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20">
                                <i class="ri-add-line text-lg"></i>
                                Add First Resource
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($resources->hasPages())
                    <div class="mt-8 rounded-[2rem] bg-slate-50 p-4 text-center">
                        {{ $resources->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
