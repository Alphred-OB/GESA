@php($title = 'Edit Timeline Entry')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header --}}
            <header class="text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-[#16136a]/5 text-[#16136a]">
                    <x-heroicon-o-pencil class="size-8" />
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">{{ $title }}</h1>
                <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Update existing academic milestone</p>
            </header>

            {{-- Form Card --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                <form action="{{ route('admin.timeline.update', $entry) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="title" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Milestone Title</label>
                                <div class="relative">
                                    <x-heroicon-o-flag class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                    <input id="title" name="title" type="text" value="{{ old('title', $entry->title) }}" required maxlength="150" 
                                        class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" 
                                        placeholder="e.g. End of Semester Exams">
                                </div>
                                @error('title')
                                    <p class="text-[10px] font-semibold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="academic_year" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Academic Year</label>
                                <div class="relative">
                                    <x-heroicon-o-building-library class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                    <input id="academic_year" name="academic_year" type="text" value="{{ old('academic_year', $entry->academic_year) }}" maxlength="15" 
                                        class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" 
                                        placeholder="e.g. 2024/2025">
                                </div>
                                @error('academic_year')
                                    <p class="text-[10px] font-semibold text-rose-500 ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="starts_at" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Event Date</label>
                            <div class="relative">
                                <x-heroicon-o-calendar class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                                <input id="starts_at" name="starts_at" type="date" value="{{ old('starts_at', optional($entry->starts_at)->format('Y-m-d')) }}" required 
                                    class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            </div>
                            @error('starts_at')
                                <p class="text-[10px] font-semibold text-rose-500 ml-1 italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between rounded-3xl border border-slate-100 bg-slate-50/50 p-6">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-tight text-[#16136a]">Publish Immediately</p>
                                <p class="mt-1 text-[10px] font-semibold text-slate-400">Visibility for all students</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" name="is_published" value="1" class="peer sr-only" @checked(old('is_published', $entry->is_published ?? true))>
                                <div class="h-8 w-14 rounded-full bg-slate-200 transition-all peer-checked:bg-[#16136a] peer-focus:ring-4 peer-focus:ring-[#16136a]/10"></div>
                                <div class="absolute left-1 top-1 h-6 w-6 rounded-full bg-white shadow-sm transition-all peer-checked:translate-x-6"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                        <button type="submit" class="flex h-14 flex-1 items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                            <x-heroicon-o-arrow-down-on-square class="size-5" />
                            Update Milestone
                        </button>
                        <a href="{{ route('admin.timeline.index') }}" class="flex h-14 items-center justify-center rounded-2xl bg-slate-50 px-8 text-sm font-semibold uppercase tracking-widest text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                            Cancel
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.admin>
