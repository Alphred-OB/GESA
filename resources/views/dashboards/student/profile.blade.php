@php
    $displayName = trim($student->fullname ?? $student->username ?? 'Student');
    $initials = collect(preg_split('/\s+/', $displayName))->filter()->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->take(2)->implode('');
    $croppedPreview = old('profile_picture_cropped');
    $storedImage = $student->profile_picture ? asset('storage/' . $student->profile_picture) : null;
    $profileImage = $croppedPreview ?: $storedImage;
    $removeRequested = old('remove_profile_picture') === '1';
    $hasProfileImage = (bool) ($profileImage && ! $removeRequested);
@endphp

<x-layouts.dashboard :title="$title">
    <div x-data="{ 
        submitting: false,
        activeTab: 'personal'
    }" class="mx-auto w-full max-w-full px-8 py-10">
        
        {{-- Submission Overlay --}}
        <div x-show="submitting" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-6 rounded-xl bg-white p-12 shadow-2xl text-center">
                <div class="relative">
                    <div class="h-24 w-24 animate-spin rounded-full border-8 border-slate-100 border-t-[#16136a]"></div>
                    <i class="ri-refresh-line absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-3xl text-[#16136a]"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-[#16136a]">Saving Changes</h2>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Updating your digital identity...</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" 
            data-profile-form class="space-y-10" x-on:submit="submitting = true">
            @csrf

            {{-- God-Tier Header --}}
            <header class="relative overflow-hidden rounded-2xl bg-[#16136a] p-12 text-white shadow-2xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-10 lg:flex-row lg:items-center">
                    {{-- Avatar Section --}}
                    <div class="relative group mx-auto lg:mx-0">
                        <div class="relative h-40 w-40 overflow-hidden rounded-xl border-4 border-white/20 bg-white/5 shadow-2xl transition-transform group-hover:scale-105">
                            <img data-avatar-preview src="{{ $hasProfileImage ? $profileImage : '' }}" class="{{ $hasProfileImage ? '' : 'hidden' }} h-full w-full object-cover">
                            <div data-avatar-fallback class="{{ $hasProfileImage ? 'hidden' : '' }} flex h-full w-full items-center justify-center bg-slate-800 text-5xl font-semibold text-white">
                                {{ $initials }}
                            </div>
                        </div>
                        <button type="button" data-avatar-trigger class="absolute -bottom-2 -right-2 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#16136a] shadow-xl transition-transform hover:scale-110 active:scale-95">
                            <i class="ri-camera-lens-line text-2xl"></i>
                        </button>
                    </div>

                    <div class="flex-1 space-y-4 text-center lg:text-left">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.3em] text-white/70">
                            Student Profile
                        </span>
                        <h1 class="text-4xl font-semibold tracking-tight sm:text-6xl">{{ $student->fullname }}</h1>
                        <div class="flex flex-wrap justify-center lg:justify-start gap-6 text-sm font-semibold text-white/50">
                            <span class="flex items-center gap-2"><i class="ri-hashtag text-indigo-300"></i> {{ $student->index_number }}</span>
                            <span class="flex items-center gap-2"><i class="ri-book-open-line text-slate-300"></i> {{ $student->class }}</span>
                            <span class="flex items-center gap-2"><i class="ri-medal-line text-amber-300"></i> Year {{ $student->year }}</span>
                        </div>
                    </div>
                </div>
                <i class="ri-user-smile-line absolute -right-20 -bottom-20 text-[25rem] text-white/5 rotate-12"></i>
            </header>

            @if (session('status'))
                <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-6 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-4">
                        <i class="ri-checkbox-circle-line text-2xl"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-10 lg:grid-cols-4">

                {{-- Sidebar Navigation --}}
                <aside class="space-y-3">
                    <button type="button" @click="activeTab = 'personal'" :class="activeTab === 'personal' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-xl px-8 py-5 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-user-heart-line text-xl"></i> Personal
                    </button>
                    <button type="button" @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-xl px-8 py-5 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-shield-keyhole-line text-xl"></i> Security
                    </button>
                    <button type="button" @click="activeTab = 'support'" :class="activeTab === 'support' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-xl px-8 py-5 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-customer-service-2-line text-xl"></i> Support
                    </button>
                </aside>

                {{-- Tab Content --}}
                <main class="lg:col-span-3 space-y-10">
                    {{-- Personal Information --}}
                    <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10">
                        <section class="rounded-xl border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40 lg:p-16">
                            <h2 class="text-2xl font-semibold text-[#16136a]">Personal Information</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Your digital identity in GESA</p>

                            <div class="mt-12 grid gap-8 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Full Identity</label>
                                    <div class="relative">
                                        <i class="ri-user-6-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" name="fullname" value="{{ old('fullname', $student->fullname) }}" readonly
                                            class="h-14 w-full rounded-xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-400 cursor-not-allowed">
                                    </div>
                                    <p class="text-[9px] font-semibold text-slate-300 italic px-1">Contact registrar for name corrections</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Portal Username</label>
                                    <div class="relative">
                                        <i class="ri-at-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                        <input type="text" value="{{ $student->username }}" disabled
                                            class="h-14 w-full rounded-xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-400 cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 space-y-2">
                                <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Primary Email</label>
                                <div class="relative">
                                    <i class="ri-mail-send-line absolute left-4 top-1/2 -translate-y-1/2 text-indigo-400"></i>
                                    <input type="email" name="pending_email" value="{{ old('pending_email', $student->pending_email ?? $student->email) }}" 
                                        class="h-14 w-full rounded-xl border-none bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                </div>
                                @if($student->pending_email)
                                    <div class="mt-3 rounded-xl bg-amber-50 p-4 border border-amber-100 flex items-center gap-3">
                                        <i class="ri-time-line text-amber-500 text-lg"></i>
                                        <p class="text-xs font-semibold text-amber-700">Verification link sent to <span class="underline">{{ $student->pending_email }}</span></p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-12 grid gap-8 sm:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Programme</label>
                                    <p class="text-sm font-semibold text-[#16136a]">{{ $student->class }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Study Year</label>
                                    <p class="text-sm font-semibold text-[#16136a]">Year {{ $student->year }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Status</label>
                                    <p class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-semibold uppercase text-emerald-600 ring-1 ring-emerald-100">Verified</p>
                                </div>
                            </div>

                            <div class="mt-16 flex justify-end pt-8 border-t border-slate-50">
                                <button type="submit" class="flex h-16 items-center gap-4 rounded-xl bg-[#16136a] px-12 text-[11px] font-semibold uppercase tracking-[0.3em] text-white shadow-2xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                    <i class="ri-save-3-line text-xl"></i> Persist Changes
                                </button>
                            </div>
                        </section>
                    </div>

                    {{-- Security Section --}}
                    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10">
                        <section class="rounded-xl border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40 lg:p-16">
                            <h2 class="text-2xl font-semibold text-[#16136a]">Credential Hardening</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Manage your portal access & security</p>

                            <div class="mt-12 space-y-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Current Password</label>
                                    <input type="password" name="current_password" 
                                        class="h-14 w-full rounded-xl border-none bg-slate-50 px-6 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10"
                                        placeholder="••••••••">
                                </div>

                                <div class="grid gap-8 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">New Password</label>
                                        <input type="password" name="password" 
                                            class="h-14 w-full rounded-xl border-none bg-slate-50 px-6 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10"
                                            placeholder="••••••••">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" 
                                            class="h-14 w-full rounded-xl border-none bg-slate-50 px-6 text-sm font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-12 rounded-xl bg-indigo-50/30 p-8 border border-indigo-100/50">
                                <div class="flex items-start gap-4">
                                    <i class="ri-shield-star-line text-3xl text-[#16136a]"></i>
                                    <div>
                                        <h3 class="text-sm font-semibold uppercase tracking-widest text-[#16136a]">Security Policy</h3>
                                        <p class="mt-2 text-xs font-semibold text-slate-400 leading-relaxed uppercase tracking-tighter">
                                            Password must contain 8+ characters, including uppercase, numbers, and symbols. 
                                            Account lockout occurs after 5 failed attempts.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- Support Section --}}
                    <div x-show="activeTab === 'support'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-10">
                        <section class="rounded-xl border border-slate-200/60 bg-white p-10 shadow-xl shadow-slate-200/40 lg:p-16">
                            <h2 class="text-2xl font-semibold text-[#16136a]">Support Ecosystem</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Connect with the GESA leadership</p>

                            <div class="mt-12 grid gap-10 md:grid-cols-2">
                                <div class="space-y-6">
                                    <div class="flex items-center gap-6 p-6 rounded-xl bg-slate-50/50 border border-slate-100">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-lg">
                                            <i class="ri-phone-line text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">President</p>
                                            <p class="text-lg font-semibold text-[#16136a]">055 318 5125</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 p-6 rounded-xl bg-slate-50/50 border border-slate-100">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-lg">
                                            <i class="ri-money-dollar-circle-line text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Fin. Secretary</p>
                                            <p class="text-lg font-semibold text-[#16136a]">059 787 0027</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl bg-indigo-600 p-8 text-white shadow-xl shadow-indigo-600/20">
                                    <i class="ri-mail-open-line text-5xl opacity-30"></i>
                                    <h3 class="mt-6 text-xl font-semibold italic">Email Correspondence</h3>
                                    <p class="mt-4 text-sm font-semibold text-white/60 leading-relaxed uppercase tracking-wider">
                                        Official inquiries: gesaumat24@gmail.com
                                    </p>
                                    <p class="mt-6 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/40">Expected response: 24-48 Hours</p>
                                </div>
                            </div>
                        </section>
                    </div>
                </main>

                {{-- Hidden Inputs for Avatar Logic --}}
                <input type="file" name="profile_picture" accept="image/*" class="hidden" data-avatar-input>
                <input type="hidden" name="profile_picture_cropped" value="{{ old('profile_picture_cropped') }}" data-avatar-cropped>
                <input type="hidden" name="remove_profile_picture" value="{{ $removeRequested ? '1' : '0' }}" data-avatar-remove-input>
            </div>
        {{-- Cropper Modal (God-Tier Overlay) --}}
        <div data-avatar-overlay class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/80 p-6 backdrop-blur-md">
            <div data-avatar-editor class="relative w-full max-w-4xl rounded-2xl bg-white p-12 shadow-2xl">
                <div class="flex items-start justify-between gap-8 mb-10">
                    <div class="space-y-2">
                        <h3 class="text-3xl font-semibold text-[#16136a]">Refine Portrait</h3>
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-widest leading-relaxed">Adjust your profile image to fit the executive frame</p>
                    </div>
                    <button type="button" data-avatar-cancel class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-transform hover:rotate-90 hover:text-rose-500">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <div class="flex max-h-[50vh] min-h-[400px] items-center justify-center overflow-hidden rounded-xl bg-slate-50 border-4 border-slate-100">
                    <img data-avatar-editor-image class="max-h-full w-full object-contain">
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" data-avatar-cancel class="px-8 py-4 text-xs font-semibold uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="button" data-avatar-apply class="flex h-14 items-center gap-3 rounded-xl bg-[#16136a] px-10 text-[10px] font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <i class="ri-check-line text-lg"></i> Apply Portrait
                    </button>
                </div>
            </div>
        </div>
        </form>
        </div>
    </div>


</x-layouts.dashboard>
