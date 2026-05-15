<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="space-y-10">
            
            {{-- Simplified Bento Hero --}}
            <section class="relative isolate overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur-md">
                                <i class="ri-book-read-line"></i> Academics Hub
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-none text-white">Academic Resources</h1>
                            <p class="text-sm font-medium text-white/70 leading-relaxed max-w-xl">
                                Explore lecture handouts, past questions, recordings, and course links curated by the academic office.
                            </p>
                        </div>
                    </div>
                    
                    <div class="shrink-0 rounded-xl border border-white/10 bg-white/5 px-6 py-4 text-sm shadow-inner backdrop-blur-md">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-white/50">Resource Stats</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-3xl font-semibold tabular-nums tracking-tighter text-white">{{ $totalResources }}</span>
                            <span class="text-[9px] font-semibold uppercase tracking-widest text-white/40">Files</span>
                        </div>
                    </div>
                </div>

                <!-- Subtle background depth -->
                <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-white/5 blur-3xl"></div>
                <i class="ri-book-read-line absolute -right-10 -bottom-10 text-[240px] text-white/[0.03] -rotate-12 select-none pointer-events-none"></i>
            </section>

            {{-- Compact Filter Bar --}}
            <section class="rounded-xl border border-slate-100 bg-white p-4 sm:p-6 shadow-xl shadow-[#16136a]/5">
                <form method="GET" action="{{ route('student.resources.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex flex-col md:flex-row items-center gap-4 w-full flex-1">
                        <div class="relative w-full lg:max-w-xl">
                            <i class="ri-search-2-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="search" name="search" type="search" value="{{ $search }}" 
                                placeholder="Search e.g. algorithms handout, level 200..." 
                                class="h-12 w-full rounded-xl border border-slate-100 bg-slate-50/50 pl-12 pr-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/20 focus:border-[#16136a]/30">
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <button type="submit" class="flex h-12 w-full md:w-auto items-center justify-center gap-2 rounded-xl bg-[#16136a] px-8 text-[10px] font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                <span>Search</span>
                            </button>
                            @if($search)
                                <a href="{{ route('student.resources.index') }}" class="flex h-12 w-full md:w-auto items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 text-[10px] font-semibold uppercase tracking-widest text-slate-500 transition-all hover:bg-slate-50">
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </section>

            {{-- Resource Grid --}}
            @if ($resources->isEmpty())
                <section class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-20 text-center">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm ring-1 ring-slate-100">
                        <i class="ri-folder-open-line text-5xl"></i>
                    </div>
                    <p class="mt-8 text-2xl font-semibold tracking-tight text-slate-900">No resources found</p>
                    <p class="mt-3 text-sm font-semibold text-slate-500">Try adjusting your search criteria or check back later.</p>
                </section>
            @else
                <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($resources as $resource)
                        <article class="group relative flex flex-col justify-between overflow-hidden rounded-xl border border-slate-100 bg-white p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-[#16136a]/10 hover:-translate-y-1">
                            <div class="relative z-10 space-y-6">
                                <div class="flex items-start justify-between gap-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1.5 text-[9px] font-semibold uppercase tracking-widest text-slate-500 ring-1 ring-inset ring-slate-200 group-hover:bg-[#16136a]/5 group-hover:text-[#16136a] group-hover:ring-[#16136a]/20 transition-colors">
                                        <i class="ri-attachment-2 font-normal"></i>
                                        {{ $resource['badge_label'] ?? 'Resource' }}
                                    </span>
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-400 group-hover:bg-[#16136a] group-hover:text-white transition-colors">
                                        <i class="ri-file-download-line text-lg"></i>
                                    </div>
                                </div>
                                
                                <div>
                                    <h2 class="text-xl font-semibold tracking-tight text-slate-900 group-hover:text-[#16136a] transition-colors line-clamp-2">
                                        {{ $resource['title'] }}
                                    </h2>
                                    <p class="mt-3 text-sm font-semibold leading-relaxed text-slate-500 line-clamp-3">
                                        {{ $resource['description'] }}
                                    </p>
                                </div>
                            </div>
                            
                            @php
                                $target = ($resource['is_file'] ?? false) || \Illuminate\Support\Str::startsWith($resource['cta_url'], ['http://', 'https://']) ? '_blank' : '_self';
                            @endphp
                            
                            <div class="relative z-10 mt-8 pt-6 border-t border-slate-100">
                                <a href="{{ $resource['cta_url'] }}" target="{{ $target }}" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 px-6 py-4 text-[10px] font-semibold uppercase tracking-widest text-[#16136a] transition-all hover:bg-[#16136a] hover:text-white group-hover:shadow-lg group-hover:shadow-[#16136a]/20">
                                    <span>{{ $resource['cta_label'] }}</span>
                                    <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif

            {{-- Support CTA --}}
            <section class="relative overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl shadow-[#16136a]/5">
                <div class="flex flex-col lg:flex-row lg:items-center">
                    <div class="p-8 sm:p-12 lg:w-1/2 space-y-4">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Need Help?</span>
                        <h2 class="text-3xl font-semibold tracking-tight text-[#16136a]">Academic Assistance</h2>
                        <p class="text-sm font-semibold leading-relaxed text-slate-500 max-w-md">Reach out to your department or academic services for questions about course materials or curriculum updates.</p>
                    </div>
                    
                    <div class="bg-slate-50 p-8 sm:p-12 lg:w-1/2 flex flex-col sm:flex-row gap-6 lg:border-l border-slate-100">
                        <div class="flex-1 space-y-2">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#16136a] shadow-sm mb-4">
                                <i class="ri-building-4-line text-xl"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-900">Faculty Office</p>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest leading-relaxed">Room 204, Eng Block<br>Weekdays · 08:00–16:00</p>
                        </div>
                        
                        <div class="flex-1 space-y-2 relative">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-lg shadow-[#16136a]/30 mb-4">
                                <i class="ri-mail-send-line text-xl"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-900">Support Inbox</p>
                            <a href="mailto:gesaumat24@gmail.com" class="block text-[11px] font-semibold text-[#16136a] uppercase tracking-widest hover:underline">gesaumat24@gmail.com</a>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>
    </div>
</x-layouts.dashboard>
