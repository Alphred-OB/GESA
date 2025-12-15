@php
    /** @var \App\Models\Announcement|null $announcement */
    $announcement = $announcement ?? null;
    $targetFilters = $targetFilters ?? [];
    $isEdit = $announcement?->exists ?? false;
    $action = $isEdit ? route('admin.announcements.update', $announcement) : route('admin.announcements.store');
    $targetType = old('target_type', $announcement->target_type ?? 'all');
    $selectedClasses = collect(old('classes', $targetFilters['classes'] ?? []))->map(fn ($value) => (string) $value)->all();
    $selectedYears = collect(old('years', $targetFilters['years'] ?? []))->map(fn ($value) => (int) $value)->all();
    $selectedStudents = collect(old('student_ids', $targetFilters['students'] ?? []))->map(fn ($value) => (int) $value)->all();
@endphp

<form method="POST" action="{{ $action }}" class="space-y-8" x-data="{ targetType: @js($targetType) }">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
        <h2 class="text-lg font-semibold text-[#16136a]">Announcement details</h2>
        <div class="grid gap-5 md:grid-cols-2">
            <label class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Title</span>
                <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="160" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Exam timetable update" />
                @error('title')
                    <span class="text-xs text-rose-600">{{ $message }}</span>
                @enderror
            </label>
            <label class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Excerpt <span class="text-slate-300">(optional)</span></span>
                <input type="text" name="excerpt" value="{{ old('excerpt', $announcement->excerpt ?? '') }}" maxlength="255" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Brief summary shown in lists" />
                @error('excerpt')
                    <span class="text-xs text-rose-600">{{ $message }}</span>
                @enderror
            </label>
        </div>
        <label class="flex flex-col gap-2">
            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Message body</span>
            <textarea name="content" rows="8" class="rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Include all necessary context, links, and next steps for students.">{{ old('content', $announcement->content ?? '') }}</textarea>
            @error('content')
                <span class="text-xs text-rose-600">{{ $message }}</span>
            @enderror
        </label>
        <div class="grid gap-5 md:grid-cols-3">
            <label class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Type</span>
                <div class="relative">
                    <select name="type" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $announcement->type ?? 'general') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </label>
            <label class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Priority</span>
                <div class="relative">
                    <select name="priority" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($priorities as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', $announcement->priority ?? 'normal') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </label>
            <label class="flex flex-col gap-2">
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Audience</span>
                <div class="relative">
                    <select name="target_type" x-model="targetType" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($targetTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                @error('target_type')
                    <span class="text-xs text-rose-600">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <div class="space-y-4" x-cloak>
            <div x-show="['class', 'class_year'].includes(targetType)" x-transition>
                <label class="flex flex-col gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Select class</span>
                    <select name="classes[]" multiple class="min-h-[140px] rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($options['classes'] as $class)
                            <option value="{{ $class }}" @selected(in_array($class, $selectedClasses, true))>{{ $class }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-slate-400">Hold Ctrl / Cmd to select multiple classes. Applies to class-only, class &amp; year, or year audiences.</span>
                    @error('classes')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </label>
            </div>

            <div x-show="['year', 'class_year'].includes(targetType)" x-transition>
                <label class="flex flex-col gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Select year</span>
                    <select name="years[]" multiple class="min-h-[140px] rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        @foreach ($options['years'] as $year)
                            <option value="{{ $year }}" @selected(in_array((int) $year, $selectedYears, true))>Year {{ $year }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs text-slate-400">Hold Ctrl / Cmd to select multiple years. Required for year-only, class &amp; year, or class audiences.</span>
                    @error('years')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </label>
            </div>

            <div x-show="targetType === 'student'" x-transition>
                <div class="flex flex-col gap-2" x-data="{
                    search: '',
                    students: @js(collect($options['students'])->map(fn($label, $id) => ['id' => (int) $id, 'label' => $label])->values()->all()),
                    selected: @js($selectedStudents),
                    get filteredStudents() {
                        if (this.search.trim().length < 2) return [];
                        const query = this.search.toLowerCase();
                        return this.students.filter(s => s.label.toLowerCase().includes(query));
                    },
                    get showList() {
                        return this.search.trim().length >= 2;
                    },
                    isSelected(id) {
                        return this.selected.includes(id);
                    },
                    toggle(id) {
                        if (this.isSelected(id)) {
                            this.selected = this.selected.filter(i => i !== id);
                        } else {
                            this.selected.push(id);
                        }
                    },
                    remove(id) {
                        this.selected = this.selected.filter(i => i !== id);
                    },
                    getLabel(id) {
                        const student = this.students.find(s => s.id === id);
                        return student ? student.label : '';
                    }
                }">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Select students</span>
                    
                    {{-- Selected students as removable pills --}}
                    <div x-show="selected.length > 0" class="flex flex-wrap gap-2 mb-2">
                        <template x-for="id in selected" :key="id">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-medium text-[#16136a]">
                                <span x-text="getLabel(id).split('(')[0].trim()"></span>
                                <button type="button" @click="remove(id)" class="flex h-4 w-4 items-center justify-center rounded-full bg-[#16136a]/20 text-[#16136a] transition hover:bg-[#16136a]/40">
                                    <i class="ri-close-line text-xs"></i>
                                </button>
                            </span>
                        </template>
                    </div>
                    
                    {{-- Search input --}}
                    <div class="relative">
                        <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input 
                            type="text" 
                            x-model="search" 
                            placeholder="Type to search students by name or email..." 
                            class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30"
                        />
                    </div>
                    
                    {{-- Prompt when not searching --}}
                    <div x-show="!showList" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-6 text-center">
                        <i class="ri-user-search-line text-3xl text-slate-300"></i>
                        <p class="mt-2 text-sm text-slate-500">Start typing to search for students</p>
                        <p class="text-xs text-slate-400">Enter at least 2 characters to see results</p>
                    </div>
                    
                    {{-- Student list with checkboxes (only shown when searching) --}}
                    <div x-show="showList" x-transition class="max-h-[240px] overflow-y-auto rounded-2xl border border-slate-200 bg-white">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <label 
                                class="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-4 py-2.5 transition last:border-b-0 hover:bg-slate-50"
                                :class="{ 'bg-[#16136a]/5': isSelected(student.id) }"
                            >
                                <input 
                                    type="checkbox" 
                                    :checked="isSelected(student.id)" 
                                    @change="toggle(student.id)"
                                    class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]/30"
                                />
                                <span class="text-sm text-slate-700" x-text="student.label"></span>
                            </label>
                        </template>
                        <div x-show="filteredStudents.length === 0" class="px-4 py-6 text-center text-sm text-slate-400">
                            <i class="ri-user-search-line text-2xl text-slate-300"></i>
                            <p class="mt-2">No students match your search.</p>
                        </div>
                    </div>
                    
                    {{-- Hidden inputs for form submission --}}
                    <template x-for="id in selected" :key="'input-' + id">
                        <input type="hidden" name="student_ids[]" :value="id" />
                    </template>
                    
                    <span class="text-xs text-slate-400">
                        <span x-text="selected.length"></span> student(s) selected.
                    </span>
                    @error('student_ids')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <footer class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">Announcements are delivered immediately via email and appear in the student announcement hub.</p>
        <div class="flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                <i class="ri-send-plane-line text-base"></i>
                {{ $isEdit ? 'Update announcement' : 'Send announcement' }}
            </button>
        </div>
    </footer>
</form>
