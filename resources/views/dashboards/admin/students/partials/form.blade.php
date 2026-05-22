@props([
    'student',
    'isEdit' => false,
    'classOptions' => [],
    'yearOptions' => [],
])

@php
    $classOptions = collect($classOptions)
        ->merge([old('class', $student->class)])
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $yearOptions = collect($yearOptions)
        ->merge([old('year', $student->year)])
        ->filter()
        ->unique()
        ->sort()
        ->values();
@endphp

<div class="space-y-10">
    {{-- Personal & Contact --}}
    <section class="space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-[#16136a]">
                <i class="ri-user-line text-lg"></i>
            </span>
            <h3 class="text-sm font-semibold text-[#16136a]">Personal & Contact Details</h3>
        </div>
        
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="fullname" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Full Name</label>
                <div class="group relative">
                    <i class="ri-user-3-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="fullname" type="text" name="fullname" value="{{ old('fullname', $student->fullname) }}" required placeholder="e.g. Emmanuel Osei" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('fullname') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Email Address</label>
                <div class="group relative">
                    <i class="ri-mail-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="email" type="email" name="email" value="{{ old('email', $student->email) }}" required placeholder="name@domain.com" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('email') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="phone_number" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Phone Number</label>
                <div class="group relative">
                    <i class="ri-phone-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="phone_number" type="text" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" placeholder="e.g. 0244123456" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('phone_number') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="username" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Username / ID</label>
                <div class="group relative">
                    <i class="ri-at-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="username" type="text" name="username" value="{{ old('username', $student->username) }}" required placeholder="e.g. emmanuel_o" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('username') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- Academic Info --}}
    <section class="space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-[#16136a]">
                <i class="ri-graduation-cap-line text-lg"></i>
            </span>
            <h3 class="text-sm font-semibold text-[#16136a]">Academic Information</h3>
        </div>
        
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="index_number" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Index Number</label>
                <div class="group relative">
                    <i class="ri-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="index_number" type="text" name="index_number" value="{{ old('index_number', $student->index_number) }}" placeholder="Student Reference ID" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('index_number') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="class" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Class / Program</label>
                <div class="relative group">
                    <i class="ri-building-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <select id="class" name="class" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-12 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                        <option value="">Select class</option>
                        @foreach ($classOptions as $option)
                            <option value="{{ $option }}" @selected((string) old('class', $student->class) === (string) $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                @error('class') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="year" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Current Year</label>
                <div class="relative group">
                    <i class="ri-calendar-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <select id="year" name="year" class="h-12 w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-12 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                        <option value="">Select Year</option>
                        @forelse ($yearOptions as $option)
                            <option value="{{ $option }}" @selected((string) old('year', $student->year) === (string) $option)>Year {{ $option }}</option>
                        @empty
                            @for ($y = 1; $y <= 4; $y++)
                                <option value="{{ $y }}" @selected(old('year', $student->year) == $y)>Year {{ $y }}</option>
                            @endfor
                        @endforelse
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                @error('year') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- Security --}}
    <section class="space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-[#16136a]">
                <i class="ri-lock-2-line text-lg"></i>
            </span>
            <h3 class="text-sm font-semibold text-[#16136a]">Account Security</h3>
        </div>
        
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2" x-data="{
                    password: '',
                    message: '',
                    helper: '',
                    badgeClass: '',
                    evaluate() {
                        const value = this.password || '';
                        if (! value.length) {
                            this.message = '';
                            this.helper = '';
                            this.badgeClass = '';
                            return;
                        }

                        let score = 0;
                        if (value.length >= 8) score += 30;
                        if (/[A-Z]/.test(value)) score += 15;
                        if (/[a-z]/.test(value)) score += 15;
                        if (/[0-9]/.test(value)) score += 20;
                        if (/[^A-Za-z0-9]/.test(value)) score += 20;
                        if (value.length >= 12) score += 10;

                        if (score >= 85) {
                            this.message = 'Strong';
                            this.helper = 'Very secure.';
                            this.badgeClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        } else if (score >= 60) {
                            this.message = 'Good';
                            this.helper = 'Add symbols.';
                            this.badgeClass = 'bg-amber-50 text-amber-600 border-amber-100';
                        } else {
                            this.message = 'Weak';
                            this.helper = 'Add length/case.';
                            this.badgeClass = 'bg-rose-50 text-rose-600 border-rose-100';
                        }
                    }
                }" x-init="$watch('password', () => evaluate())">
                <label for="password" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                    Account Password
                    @if ($isEdit) <span class="text-slate-300 font-medium lowercase italic">(Leave blank to keep)</span> @endif
                </label>
                <div class="group relative">
                    <i class="ri-shield-keyhole-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input x-model="password" id="password" type="password" name="password" @unless($isEdit) required @endunless placeholder="••••••••" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
                @error('password') <p class="text-[10px] font-semibold text-rose-500">{{ $message }}</p> @enderror
                
                <div class="mt-2 flex items-center gap-2" x-show="message" x-cloak>
                    <span class="inline-flex items-center gap-1.5 rounded-xl border px-2.5 py-1 text-[10px] font-semibold uppercase tracking-widest" :class="badgeClass">
                        <span x-text="message"></span>
                    </span>
                    <span class="text-[10px] font-semibold text-slate-400" x-text="helper"></span>
                </div>
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Confirm Password</label>
                <div class="group relative">
                    <i class="ri-lock-password-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-[#16136a]"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" @unless($isEdit) required @endunless placeholder="••••••••" class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                </div>
            </div>
        </div>
    </section>

    @if ($isEdit)
        <input type="hidden" name="is_seller" value="{{ $student->is_seller ? 1 : 0 }}">
    @else
        <input type="hidden" name="is_seller" value="0">
    @endif
</div>
