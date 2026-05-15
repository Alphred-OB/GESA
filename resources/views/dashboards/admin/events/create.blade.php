<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header --}}
            <header class="text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-[#16136a]/5 text-[#16136a]">
                    <i class="ri-calendar-event-line text-3xl"></i>
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">{{ $title }}</h1>
                <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Broadcast campus activities to the student body</p>
            </header>

            @if ($errors->any())
                <div class="rounded-[2rem] border border-rose-100 bg-rose-50/50 p-6 text-sm font-semibold text-rose-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <i class="ri-error-warning-line text-2xl"></i>
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

            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Banner Upload --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12" x-data="{
                    preview: null,
                    fileName: '',
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (!file) {
                            this.preview = null;
                            this.fileName = '';
                            return;
                        }
                        this.fileName = file.name;
                        const reader = new FileReader();
                        reader.onload = e => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Event Branding</h2>
                    <div class="space-y-6">
                        <label for="banner_image" class="group relative flex aspect-video w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-slate-50/50 transition-all hover:border-[#16136a]/40 hover:bg-white">
                            <template x-if="preview">
                                <img :src="preview" class="absolute inset-0 h-full w-full object-cover">
                            </template>
                            
                            <div class="relative z-10 flex flex-col items-center transition-transform group-hover:scale-110" x-show="!preview">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white text-[#16136a] shadow-xl">
                                    <i class="ri-image-add-line text-2xl"></i>
                                </div>
                                <p class="mt-4 text-sm font-semibold text-slate-900">Upload Event Banner</p>
                                <p class="mt-1 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">16:9 Aspect ratio recommended</p>
                            </div>

                            <div class="absolute bottom-6 right-6 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#16136a] shadow-xl" x-show="preview">
                                <i class="ri-refresh-line text-xl"></i>
                            </div>
                            <input id="banner_image" name="banner_image" type="file" class="hidden" @change="handleFileChange($event)" accept="image/*">
                        </label>
                        
                        <div class="space-y-2">
                            <label for="banner_alt" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Banner Alt Text</label>
                            <input id="banner_alt" name="banner_alt" type="text" value="{{ old('banner_alt') }}" class="h-12 w-full rounded-2xl border-none bg-slate-50 px-5 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="e.g. Students networking at the atrium">
                        </div>
                    </div>
                </section>

                {{-- Event Info --}}
                <section x-data="{ eventType: '{{ old('type', 'physical') }}' }" class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Core Details</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2 space-y-2">
                            <label for="title" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Event Title</label>
                            <div class="relative">
                                <i class="ri-edit-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="title" name="title" type="text" value="{{ old('title') }}" required class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="e.g. Annual Design Showcase">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="category" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Category</label>
                            <div class="relative">
                                <i class="ri-price-tag-3-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="category" name="category" type="text" value="{{ old('category') }}" class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="e.g. Academic, Social, Workshop">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="type" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Event Type</label>
                            <div class="relative">
                                <i class="ri-global-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                <select x-model="eventType" id="type" name="type" required class="h-14 w-full appearance-none rounded-2xl border-none bg-slate-50 pl-12 pr-10 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    <option value="physical">Physical (In-Person)</option>
                                    <option value="online">Online (Virtual)</option>
                                    <option value="hybrid">Hybrid (Both)</option>
                                </select>
                                <i class="ri-arrow-down-s-line absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="start_at" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Starts At</label>
                            <div class="relative">
                                <i class="ri-time-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="start_at" name="start_at" type="datetime-local" value="{{ old('start_at') }}" required class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="end_at" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Ends At</label>
                            <div class="relative">
                                <i class="ri-timer-2-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="end_at" name="end_at" type="datetime-local" value="{{ old('end_at') }}" class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                            </div>
                        </div>

                        <div x-show="eventType === 'physical' || eventType === 'hybrid'" class="md:col-span-2 space-y-2">
                            <label for="location" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Venue / Location</label>
                            <div class="relative">
                                <i class="ri-map-pin-2-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="location" name="location" type="text" value="{{ old('location') }}" class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="e.g. Great Hall, 2nd Floor">
                            </div>
                        </div>

                        <div x-show="eventType === 'online' || eventType === 'hybrid'" class="space-y-2">
                            <label for="meeting_link" class="text-[10px] font-semibold uppercase tracking-widest text-emerald-500 ml-1">Meeting Link (Zoom, Meet, etc)</label>
                            <div class="relative">
                                <i class="ri-video-chat-line absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500/70"></i>
                                <input id="meeting_link" name="meeting_link" type="url" value="{{ old('meeting_link') }}" class="h-14 w-full rounded-2xl border-none bg-emerald-50/50 pl-12 pr-4 text-sm font-semibold text-emerald-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="https://zoom.us/j/...">
                            </div>
                        </div>

                        <div x-show="eventType === 'online' || eventType === 'hybrid'" class="space-y-2">
                            <label for="meeting_passcode" class="text-[10px] font-semibold uppercase tracking-widest text-emerald-500 ml-1">Meeting Passcode</label>
                            <div class="relative">
                                <i class="ri-key-2-line absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500/70"></i>
                                <input id="meeting_passcode" name="meeting_passcode" type="text" value="{{ old('meeting_passcode') }}" class="h-14 w-full rounded-2xl border-none bg-emerald-50/50 pl-12 pr-4 text-sm font-semibold text-emerald-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="Optional">
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="cta_url" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Registration / External Link</label>
                            <div class="relative">
                                <i class="ri-links-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="cta_url" name="cta_url" type="url" value="{{ old('cta_url') }}" class="h-14 w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="https:// (Optional)">
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label for="description" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Event Brief</label>
                            <div class="relative">
                                <i class="ri-text-snippet absolute left-4 top-6 text-slate-400"></i>
                                <textarea id="description" name="description" rows="5" class="w-full rounded-2xl border-none bg-slate-50 pl-12 pr-4 py-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10" placeholder="What should students know about this event?">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                    <button type="submit" class="flex h-14 flex-1 items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                        <i class="ri-save-line text-lg"></i>
                        Save Event
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="flex h-14 items-center justify-center rounded-2xl bg-slate-50 px-8 text-sm font-semibold uppercase tracking-widest text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
