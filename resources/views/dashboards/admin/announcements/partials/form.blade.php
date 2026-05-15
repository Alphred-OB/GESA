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

    <section class="rounded-[32px] border border-slate-200/60 bg-white p-5 shadow-sm sm:p-8">
        <div class="mb-8 border-b border-slate-50 pb-6">
            <h2 class="text-lg font-semibold text-slate-900">Message Details</h2>
            <p class="text-sm font-medium text-slate-500">Write a clear title and message for students.</p>
        </div>

        <div class="grid gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Title</span>
                    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="160" class="h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5" placeholder="e.g., End of Semester Examination Schedule" />
                    @error('title')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                    @enderror
                </label>
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Brief Description <span class="text-slate-300 font-medium">(Optional)</span></span>
                    <input type="text" name="excerpt" value="{{ old('excerpt', $announcement->excerpt ?? '') }}" maxlength="255" class="h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5" placeholder="A short summary of your message" />
                    @error('excerpt')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                    @enderror
                </label>
            </div>

            <label class="flex flex-col gap-2">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Main Message</span>
                <textarea name="content" rows="10" class="rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-medium leading-relaxed transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5" placeholder="Type your message here..."></textarea>
                @error('content')
                    <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                @enderror
            </label>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Category</span>
                    <div class="relative">
                        <select name="type" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', $announcement->type ?? 'general') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Priority Level</span>
                    <div class="relative">
                        <select name="priority" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                            @foreach ($priorities as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', $announcement->priority ?? 'normal') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>
                <label class="flex flex-col gap-2 sm:col-span-2 lg:col-span-1">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Who should see this?</span>
                    <div class="relative">
                        <select name="target_type" x-model="targetType" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                            @foreach ($targetTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    @error('target_type')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                    @enderror
                </label>
            </div>
        </div>

        <!-- Audience Filters -->
        <div class="mt-8 space-y-6 rounded-2xl bg-slate-50/50 p-4 border border-slate-100 sm:p-6" x-cloak x-show="targetType !== 'all'" x-transition>
            <div x-show="['class', 'class_year'].includes(targetType)" x-transition>
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Select Classes</span>
                    <select name="classes[]" multiple class="min-h-[140px] rounded-xl border border-slate-200 bg-white p-3 text-sm font-medium shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                        @foreach ($options['classes'] as $class)
                            <option value="{{ $class }}" @selected(in_array($class, $selectedClasses, true))>{{ $class }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-tighter italic">Hold Cmd (Mac) or Ctrl (Windows) to select multiple.</p>
                    @error('classes')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                    @enderror
                </label>
            </div>

            <div x-show="['year', 'class_year'].includes(targetType)" x-transition>
                <label class="flex flex-col gap-2">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Select Academic Years</span>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($options['years'] as $year)
                            <label @class(['flex cursor-pointer items-center justify-center gap-2 rounded-xl border p-3 transition-all active:scale-95', 
                                'bg-white border-slate-200 hover:border-[#16136a]/30' => !in_array((int) $year, $selectedYears, true),
                                'bg-[#16136a] border-[#16136a] text-white shadow-md shadow-[#16136a]/10' => in_array((int) $year, $selectedYears, true)
                            ]) x-data="{ selected: @js(in_array((int) $year, $selectedYears, true)) }">
                                <input type="checkbox" name="years[]" value="{{ $year }}" class="hidden" x-model="selected" />
                                <span class="text-xs font-semibold uppercase tracking-widest">Year {{ $year }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('years')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
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
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Selected Students</span>
                    
                    <div x-show="selected.length > 0" class="flex flex-wrap gap-2 mb-4">
                        <template x-for="id in selected" :key="id">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#16136a] px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white shadow-sm">
                                <span x-text="getLabel(id).split('(')[0].trim()"></span>
                                <button type="button" @click="remove(id)" class="flex h-4 w-4 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/40">
                                    <i class="ri-close-line"></i>
                                </button>
                            </span>
                        </template>
                    </div>
                    
                    <div class="relative">
                        <i class="ri-search-2-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input 
                            type="text" 
                            x-model="search" 
                            placeholder="Type student name or email..." 
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm font-medium transition focus:border-[#16136a] focus:outline-none focus:ring-4 focus:ring-[#16136a]/5"
                        />
                    </div>
                    
                    <div x-show="showList" x-transition class="mt-2 max-h-[240px] overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <label 
                                class="flex cursor-pointer items-center gap-3 border-b border-slate-50 px-4 py-3 transition last:border-b-0 hover:bg-slate-50"
                                :class="{ 'bg-slate-50': isSelected(student.id) }"
                            >
                                <input 
                                    type="checkbox" 
                                    :checked="isSelected(student.id)" 
                                    @change="toggle(student.id)"
                                    class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]/10"
                                />
                                <span class="text-xs font-semibold text-slate-700" x-text="student.label"></span>
                            </label>
                        </template>
                        <div x-show="filteredStudents.length === 0" class="px-4 py-8 text-center">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">No matching students found</p>
                        </div>
                    </div>
                    
                    <template x-for="id in selected" :key="'input-' + id">
                        <input type="hidden" name="student_ids[]" :value="id" />
                    </template>
                    
                    <p class="mt-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                        <span x-text="selected.length" class="text-[#16136a]"></span> students selected.
                    </p>
                    @error('student_ids')
                        <span class="text-[10px] font-semibold text-rose-500 uppercase tracking-tighter">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <footer class="flex flex-col items-center justify-between gap-6 rounded-[24px] border border-slate-200/60 bg-white p-6 shadow-sm md:flex-row">
        <div class="flex items-center gap-3 text-slate-400">
            <i class="ri-information-line text-xl"></i>
            <p class="text-[11px] font-semibold uppercase tracking-widest leading-relaxed">
                Messages cannot be changed after sending. <br> Please double-check before you click send.
            </p>
        </div>
        <div class="flex w-full items-center gap-3 md:w-auto">
            <a href="{{ route('admin.announcements.index') }}" class="flex-1 rounded-xl bg-slate-50 px-6 py-3 text-center text-xs font-semibold uppercase tracking-widest text-slate-500 transition hover:bg-slate-100 md:flex-none">Cancel</a>
            <button type="submit" class="flex-1 rounded-xl bg-[#16136a] px-8 py-3.5 text-center text-xs font-semibold uppercase tracking-widest text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95 md:flex-none">
                {{ $isEdit ? 'Save Changes' : 'Send Now' }}
            </button>
        </div>
    </footer>
</form>
