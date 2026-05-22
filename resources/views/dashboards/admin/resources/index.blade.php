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
                        <x-heroicon-o-plus class="size-5 transition-transform group-hover:rotate-90" />
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
                    <x-heroicon-o-document class="absolute -right-4 -bottom-4 text-9xl text-white/5 rotate-12 size-5" />
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
                        <x-heroicon-o-check-circle class="size-6" />
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Resource List --}}
            <section class="space-y-6" x-data="{ viewMode: localStorage.getItem('resourceViewMode') || 'table' }" x-init="$watch('viewMode', val => localStorage.setItem('resourceViewMode', val))">
                <form method="GET" class="flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-sm border border-slate-200/60 lg:flex-row lg:items-center lg:justify-between">
                    {{-- Search & Filters --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative">
                            <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources..." class="h-10 w-full min-w-[200px] rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                        </div>
                        <select name="content_type" onchange="this.form.submit()" class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                            <option value="">All Types</option>
                            @foreach ($contentTypes as $type)
                                <option value="{{ $type }}" @selected(request('content_type') === $type)>{{ Str::headline($type) }}</option>
                            @endforeach
                        </select>
                        <select name="year" onchange="this.form.submit()" class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                            <option value="">All Years</option>
                            @foreach ($yearOptions as $year)
                                <option value="{{ $year }}" @selected(request('year') == $year)>Year {{ $year }}</option>
                            @endforeach
                        </select>
                        <select name="class" onchange="this.form.submit()" class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none focus:border-[#16136a] focus:ring-1 focus:ring-[#16136a]">
                            <option value="">All Classes</option>
                            @foreach ($classOptions as $cls)
                                <option value="{{ $cls }}" @selected(request('class') == $cls)>{{ $cls }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="hidden"></button>
                    </div>

                    {{-- View Toggle & Display --}}
                    <div class="flex items-center gap-3">
                        <div class="flex rounded-xl bg-slate-100 p-1">
                            <button type="button" @click="viewMode = 'table'" :class="{ 'bg-white shadow-sm text-[#16136a]': viewMode === 'table', 'text-slate-500 hover:text-slate-700': viewMode !== 'table' }" class="flex h-8 w-10 items-center justify-center rounded-lg transition-all">
                                <x-heroicon-o-clipboard-document-check class="size-5" />
                            </button>
                            <button type="button" @click="viewMode = 'grid'" :class="{ 'bg-white shadow-sm text-[#16136a]': viewMode === 'grid', 'text-slate-500 hover:text-slate-700': viewMode !== 'grid' }" class="flex h-8 w-10 items-center justify-center rounded-lg transition-all">
                                <x-heroicon-s-squares-2x2 class="size-5" />
                            </button>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <select name="per_page" onchange="this.form.submit()" class="h-10 rounded-xl border-none bg-slate-100 px-3 text-xs font-semibold text-slate-600 outline-none focus:ring-2 focus:ring-[#16136a]/10">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }} Rows</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div x-cloak>
                    {{-- Table View --}}
                    <div x-show="viewMode === 'table'" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-[10px] uppercase tracking-widest text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Resource</th>
                                        <th class="px-6 py-4 font-semibold">Type</th>
                                        <th class="px-6 py-4 font-semibold">Audience</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 text-right font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($resources as $resource)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div @class([
                                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl font-semibold',
                                                        'bg-[#16136a]/10 text-[#16136a]' => $resource->resource_type === 'file',
                                                        'bg-blue-50 text-blue-600' => $resource->resource_type !== 'file',
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
                                                        <i class="{{ $icon }} text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-slate-900">{{ $resource->title }}</p>
                                                        <p class="text-xs text-slate-400 line-clamp-1 max-w-xs">{{ $resource->description }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                                    {{ Str::headline($resource->content_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $classes = (array) ($resource->target_classes ?? []);
                                                    $years = (array) ($resource->target_years ?? []);
                                                @endphp
                                                @if (empty($classes) && empty($years))
                                                    <span class="text-xs font-semibold text-emerald-600">Global</span>
                                                @else
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($classes as $classItem)
                                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-600">{{ $classItem }}</span>
                                                        @endforeach
                                                        @foreach ($years as $yearItem)
                                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-600">Yr {{ $yearItem }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($resource->visibility === 'student')
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-600">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Live
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if($resource->resource_type === 'file' && $resource->file_path)
                                                        <a href="{{ $resource->download_url }}" target="_blank" class="p-2 text-slate-400 hover:text-[#16136a] transition-colors" title="Download">
                                                            <x-heroicon-o-cloud-arrow-down class="size-5" />
                                                        </a>
                                                    @elseif($resource->cta_url)
                                                        <a href="{{ $resource->cta_url }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="External Link">
                                                            <x-heroicon-o-arrow-top-right-on-square class="size-5" />
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.resources.edit', $resource) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Edit">
                                                        <x-heroicon-o-pencil class="size-5" />
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource permanentely?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Delete">
                                                            <x-heroicon-o-trash class="size-5" />
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                                No resources found matching your criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Grid View --}}
                    <div x-show="viewMode === 'grid'" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @forelse ($resources as $resource)
                            <div class="group flex flex-col rounded-2xl border border-slate-200/60 bg-white p-5 transition-all hover:border-slate-300 hover:shadow-md">
                                <div class="flex items-start justify-between gap-3">
                                    <div @class([
                                        'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl font-semibold',
                                        'bg-[#16136a]/5 text-[#16136a]' => $resource->resource_type === 'file',
                                        'bg-blue-50 text-blue-600' => $resource->resource_type !== 'file',
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
                                        <i class="{{ $icon }} text-xl"></i>
                                    </div>
                                    <span class="inline-flex rounded-lg bg-slate-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                        {{ Str::headline($resource->content_type) }}
                                    </span>
                                </div>
                                <div class="mt-4 flex-1">
                                    <h3 class="font-semibold text-slate-900 line-clamp-2 leading-snug">{{ $resource->title }}</h3>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @php
                                            $classes = (array) ($resource->target_classes ?? []);
                                            $years = (array) ($resource->target_years ?? []);
                                        @endphp
                                        @if (empty($classes) && empty($years))
                                            <span class="text-[10px] font-semibold text-emerald-600">Global Audience</span>
                                        @else
                                            @foreach (array_slice($classes, 0, 2) as $classItem)
                                                <span class="text-[10px] font-medium text-slate-500 bg-slate-50 rounded px-1">{{ $classItem }}</span>
                                            @endforeach
                                            @foreach (array_slice($years, 0, 2) as $yearItem)
                                                <span class="text-[10px] font-medium text-slate-500 bg-slate-50 rounded px-1">Yr {{ $yearItem }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-50">
                                    <div class="flex gap-1">
                                        <a href="{{ route('admin.resources.edit', $resource) }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-amber-500 transition-colors">
                                            <x-heroicon-o-pencil class="size-5" />
                                        </a>
                                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource permanentely?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-50 hover:text-rose-500 transition-colors">
                                                <x-heroicon-o-trash class="size-5" />
                                            </button>
                                        </form>
                                    </div>
                                    @if($resource->resource_type === 'file' && $resource->file_path)
                                        <a href="{{ $resource->download_url }}" target="_blank" class="text-xs font-semibold text-[#16136a] hover:underline">
                                            Download &rarr;
                                        </a>
                                    @elseif($resource->cta_url)
                                        <a href="{{ $resource->cta_url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline">
                                            Open Link &rarr;
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                    <x-heroicon-o-magnifying-glass class="size-8" />
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-slate-900">No resources found</h3>
                                <p class="mt-1 text-xs text-slate-500">Try adjusting your filters.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pagination --}}
                @if($resources->hasPages())
                    <div class="mt-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-200/60">
                        {{ $resources->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>
