<x-layouts.dashboard :title="$title">
    @include('components.dashboard.skeleton-styles')

    @php
        $displayName = trim($student->fullname ?? $student->username ?? 'Student');
        $initials = collect(preg_split('/\s+/', $displayName))->filter()->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->take(2)->implode('');
        $croppedPreview = old('profile_picture_cropped');
        $storedImage = $student->profile_picture ? asset('storage/' . $student->profile_picture) : null;
        $profileImage = $croppedPreview ?: $storedImage;
        $removeRequested = old('remove_profile_picture') === '1';
        $hasProfileImage = (bool) ($profileImage && ! $removeRequested);
    @endphp

    <div x-data="{ loading: true, submitting: false }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-5xl px-5 py-12 sm:px-6 lg:px-8">
        <div
            x-show="submitting"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
            aria-live="assertive"
            aria-label="Updating your profile"
        >
            <div class="flex items-center gap-3 rounded-3xl border border-slate-200/80 bg-white/95 px-5 py-3 text-sm text-slate-700 shadow-xl">
                <span class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-[#16136a]/70 border-t-transparent"></span>
                <div>
                    <p class="text-sm font-semibold text-[#16136a]">Updating your profile</p>
                    <p class="text-xs text-slate-500">Please wait a moment. Don't close this tab or press back.</p>
                </div>
            </div>
        </div>
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <section class="hidden md:block overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-[#16136a] p-8 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)]">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4">
                        <div class="skeleton h-3 w-32 rounded-full bg-white/30"></div>
                        <div class="skeleton h-9 w-64 rounded-2xl bg-white/20"></div>
                        <div class="skeleton h-4 w-80 rounded-2xl bg-white/15"></div>
                    </div>
                    <div class="space-y-3 text-white/80">
                        <div class="skeleton h-4 w-48 rounded-full bg-white/25"></div>
                        <div class="skeleton h-4 w-40 rounded-full bg-white/20"></div>
                    </div>
                </div>
            </section>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="skeleton h-16 rounded-3xl bg-slate-200/70"></div>
                <div class="skeleton h-16 rounded-3xl bg-slate-200/70"></div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="flex flex-col items-center gap-5">
                        <div class="skeleton h-32 w-32 rounded-full bg-slate-200"></div>
                        <div class="flex flex-wrap justify-center gap-3">
                            <div class="skeleton h-10 w-32 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-10 w-24 rounded-full bg-slate-100"></div>
                        </div>
                        <div class="skeleton h-3 w-48 rounded-full bg-slate-100"></div>
                    </div>
                </article>
                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                    <div class="space-y-4">
                        <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-4 w-64 rounded-full bg-slate-100"></div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="space-y-2">
                                    <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                    <div class="skeleton h-10 rounded-2xl bg-slate-100"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-end">
                            <div class="skeleton h-4 w-40 rounded-full bg-slate-100"></div>
                            <div class="skeleton h-10 w-36 rounded-full bg-[#16136a]/20"></div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="space-y-4">
                        <div class="skeleton h-5 w-52 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-4 w-72 rounded-full bg-slate-100"></div>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="space-y-2">
                                    <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                    <div class="skeleton h-10 rounded-2xl bg-slate-100"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="flex flex-wrap justify-end gap-2 pt-2">
                            <div class="skeleton h-10 w-28 rounded-full bg-slate-100"></div>
                            <div class="skeleton h-10 w-32 rounded-full bg-[#16136a]/20"></div>
                        </div>
                    </div>
                </article>
                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="space-y-4">
                        <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="flex items-center gap-3">
                                    <div class="skeleton h-10 w-10 rounded-full bg-[#16136a]/10"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
                                        <div class="skeleton h-3 w-44 rounded-full bg-slate-100"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </article>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <article class="space-y-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="skeleton h-5 w-56 rounded-full bg-slate-200"></div>
                    @for ($i = 0; $i < 5; $i++)
                        <div class="flex items-center justify-between gap-3">
                            <div class="skeleton h-4 w-40 rounded-full bg-slate-100"></div>
                            <div class="skeleton h-4 w-24 rounded-full bg-slate-200"></div>
                        </div>
                    @endfor
                </article>
                <article class="space-y-4 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="skeleton h-5 w-48 rounded-full bg-slate-200"></div>
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex items-center justify-between gap-3">
                            <div class="skeleton h-4 w-36 rounded-full bg-slate-100"></div>
                            <div class="skeleton h-4 w-20 rounded-full bg-slate-200"></div>
                        </div>
                    @endfor
                </article>
            </div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <section class="relative isolate hidden md:block animate-fade-slide overflow-hidden rounded-[24px] border border-[#16136a]/15 bg-[#16136a] p-8 text-white shadow-[0_20px_50px_-30px_rgba(22,19,106,0.4)]">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-slate-100">Profile</span>
                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold md:text-4xl">Hello, {{ $student->fullname ?? $student->username ?? 'Student' }}</h1>
                        <p class="max-w-2xl text-sm text-slate-100">
                            Review and keep your personal, contact, and academic details current so departments can reach you quickly when needed.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-2xl font-semibold">
                        @php($initials = collect(explode(' ', $student->fullname ?? $student->username ?? 'Student'))->map(fn ($part) => Str::substr($part, 0, 1))->implode(''))
                        {{ Str::upper(Str::substr($initials, 0, 2)) }}
                    </span>
                    <div class="hidden text-sm text-slate-100 sm:flex sm:flex-col">
                        <span class="font-semibold">{{ $student->email }}</span>
                        <span>{{ $student->index_number ?? 'Index unavailable' }}</span>
                    </div>
                </div>
            </div>
        </section>

        @if (session('status'))
            <div class="animate-fade-slide rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900 shadow-lg shadow-emerald-100/60">
                <div class="flex items-start gap-3">
                    <i class="ri-checkbox-circle-line text-xl" aria-hidden="true"></i>
                    <div>
                        <p class="text-sm font-semibold">Profile updated</p>
                        <p class="text-sm text-emerald-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="animate-fade-slide rounded-3xl border border-rose-200 bg-rose-50 p-4 text-rose-900 shadow-lg shadow-rose-100/60">
                <div class="flex items-start gap-3">
                    <i class="ri-error-warning-line text-xl" aria-hidden="true"></i>
                    <div>
                        <p class="text-sm font-semibold">We found {{ $errors->count() }} issue{{ $errors->count() === 1 ? '' : 's' }}</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-rose-800">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('student.profile.update') }}"
            enctype="multipart/form-data"
            class="space-y-10"
            data-profile-form
            x-on:submit="submitting = true"
        >
            @csrf

            <section class="grid gap-6 lg:grid-cols-3">
                <article class="animate-fade-slide rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <header class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-[#16136a]">Profile photo</h2>
                            <p class="text-sm text-slate-500">Upload a clear portrait so staff can recognise you easily.</p>
                        </div>
                    </header>

                    <div class="mt-6 space-y-6">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border border-[#16136a]/20 bg-[#16136a]/5">
                                <img data-avatar-preview src="{{ $hasProfileImage ? $profileImage : '' }}" alt="{{ $displayName }} profile photo" class="{{ $hasProfileImage ? '' : 'hidden' }} h-full w-full object-cover" />
                                <span data-avatar-fallback class="{{ $hasProfileImage ? 'hidden' : '' }} text-2xl font-semibold uppercase text-[#16136a]">{{ $initials ?: 'ST' }}</span>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                <button type="button" data-avatar-trigger class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-[#16136a]/90 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                                    <i class="ri-upload-2-line text-base" aria-hidden="true"></i>
                                    Choose photo
                                </button>
                                <button type="button" data-avatar-remove class="{{ $hasProfileImage ? '' : 'hidden' }} inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-200/80">
                                    <i class="ri-delete-bin-6-line text-base" aria-hidden="true"></i>
                                    Remove
                                </button>
                            </div>
                            <p class="text-center text-xs text-slate-500" data-avatar-helper>Select a square image (minimum 400×400px). A crop dialog will appear so you can fine-tune the frame.</p>
                        </div>

                        <input type="file" name="profile_picture" accept="image/*" class="hidden" data-avatar-input>
                        <input type="hidden" name="profile_picture_cropped" value="{{ old('profile_picture_cropped') }}" data-avatar-cropped>
                        <input type="hidden" name="remove_profile_picture" value="{{ $removeRequested ? '1' : '0' }}" data-avatar-remove-input>
                    </div>

                    
                </article>

                <article class="animate-fade-slide rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 lg:col-span-2">
                    <header>
                        <h2 class="text-lg font-semibold text-[#16136a]">Personal &amp; contact details</h2>
                        <p class="text-sm text-slate-500">Ensure your contact information stays up to date.</p>
                    </header>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Full name</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-user-line text-base" aria-hidden="true"></i>
                                </span>
                                <input type="text" name="fullname" value="{{ old('fullname', $student->fullname) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-600" readonly>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Username</span>
                            <input type="text" value="{{ $student->username }}" disabled class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Primary email</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-mail-line text-base" aria-hidden="true"></i>
                                </span>
                                <input
                                    type="email"
                                    name="pending_email"
                                    id="pending_email"
                                    value="{{ old('pending_email', $student->pending_email ?? $student->email) }}"
                                    class="w-full rounded-2xl border {{ $student->pending_email ? 'border-amber-300 bg-amber-50 text-slate-900' : 'border-slate-200 bg-white text-slate-900' }} py-3 pl-11 pr-4 text-sm shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40"
                                    placeholder="you@example.com"
                                    autocomplete="email"
                                >
                            </div>
                            @error('pending_email')
                                <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror

                            @if ($student->pending_email)
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700">
                                    <div class="flex items-start gap-2">
                                        <i class="ri-alert-line mt-0.5 text-base" aria-hidden="true"></i>
                                        <div class="space-y-2">
                                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-600">Pending verification</p>
                                            <p class="text-xs leading-5 text-amber-700">We sent a verification link to <span class="font-semibold">{{ $student->pending_email }}</span>. Check your inbox or resend the link below.</p>
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" class="inline-flex items-center gap-2 rounded-full bg-amber-600 px-4 py-1.5 text-xs font-semibold text-white shadow transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-600/40" onclick="document.getElementById('pending_email').value='{{ $student->pending_email }}'; this.form.requestSubmit();">
                                                    <i class="ri-refresh-line text-base" aria-hidden="true"></i>
                                                    Resend verification
                                                </button>
                                                <button type="button" class="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white px-4 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500/30" onclick="document.getElementById('pending_email').value=''; this.form.requestSubmit();">
                                                    <i class="ri-close-line text-base" aria-hidden="true"></i>
                                                    Cancel request
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-500">This address is verified. Change it to trigger a new verification.</p>
                            @endif
                        </label>
                        
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Phone number</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-phone-line text-base" aria-hidden="true"></i>
                                </span>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-600" readonly>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Reference number</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-id-card-line text-base" aria-hidden="true"></i>
                                </span>
                                <input type="text" value="{{ $student->index_number ?? 'Not assigned' }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-600" readonly>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Programme / class</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-school-line text-base" aria-hidden="true"></i>
                                </span>
                                <input type="text" name="class" value="{{ old('class', $student->class) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-600" readonly>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Year of study</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-calendar-line text-base" aria-hidden="true"></i>
                                </span>
                                <input type="text" value="{{ $student->year ? 'Year ' . $student->year : 'Not set' }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-600" readonly>
                            </div>
                        </label>
                    </div>

                       <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-end">
                <p class="text-sm text-slate-500">Last updated {{ optional($student->updated_at)->diffForHumans() ?? 'recently' }}</p>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-[#16136a]/90 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="submitting"
                >
                    <i class="ri-save-line text-base" aria-hidden="true"></i>
                    Save changes
                </button>
            </div>
                </article>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <article
                    class="animate-fade-slide rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10"
                    x-data="{
                        editing: false,
                        open() {
                            this.editing = true;
                            this.$nextTick(() => this.$refs.currentPassword?.focus());
                        },
                        cancel() {
                            this.editing = false;
                            if (this.$refs.currentPassword) this.$refs.currentPassword.value = '';
                            if (this.$refs.newPassword) {
                                this.$refs.newPassword.value = '';
                                this.$refs.newPassword.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            if (this.$refs.confirmPassword) this.$refs.confirmPassword.value = '';
                        }
                    }"
                >
                    <header>
                        <h2 class="text-lg font-semibold text-[#16136a]">Security &amp; password</h2>
                        <p class="text-sm text-slate-500">Leave these fields blank to keep your existing password.</p>
                    </header>
                    <div class="mt-6" x-cloak x-show="!editing">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:bg-[#16136a]/90 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40"
                            x-on:click="open()"
                        >
                            <i class="ri-lock-2-line text-base" aria-hidden="true"></i>
                            Change password
                        </button>
                    </div>
                    <div class="mt-6 space-y-6" x-cloak x-show="editing" x-transition.origin-top>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Current password</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-lock-2-line text-base" aria-hidden="true"></i>
                                </span>
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    x-ref="currentPassword"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40"
                                >
                                <button type="button" class="absolute inset-y-0 right-0 mr-3 inline-flex items-center justify-center rounded-full bg-transparent px-2 text-slate-500 transition hover:text-[#16136a]" data-password-toggle="#current_password">
                                    <i data-eye class="ri-eye-line text-base" aria-hidden="true"></i>
                                    <i data-eye-off class="ri-eye-off-line hidden text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">New password</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-shield-keyhole-line text-base" aria-hidden="true"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    id="new_password"
                                    x-ref="newPassword"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40"
                                    autocomplete="new-password"
                                >
                                <button type="button" class="absolute inset-y-0 right-0 mr-3 inline-flex items-center justify-center rounded-full bg-transparent px-2 text-slate-500 transition hover:text-[#16136a]" data-password-toggle="#new_password">
                                    <i data-eye class="ri-eye-line text-base" aria-hidden="true"></i>
                                    <i data-eye-off class="ri-eye-off-line hidden text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="space-y-3" data-password-strength data-password-input="#new_password">
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-1/12 rounded-full bg-red-500 transition-all duration-200" data-password-strength-bar></div>
                                </div>
                                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                    <span>Strength</span>
                                    <span data-password-strength-label>Weak</span>
                                </div>
                                <ul class="space-y-2 text-xs text-slate-500">
                                    <li class="flex items-center gap-2" data-password-rule="length">
                                        <span class="flex h-4 w-4 items-center justify-center" data-fail-icon>
                                            <i class="ri-close-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        <span class="hidden flex h-4 w-4 items-center justify-center text-[#16136a]" data-pass-icon>
                                            <i class="ri-check-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        At least 8 characters
                                    </li>
                                    <li class="flex items-center gap-2" data-password-rule="mixed">
                                        <span class="flex h-4 w-4 items-center justify-center" data-fail-icon>
                                            <i class="ri-close-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        <span class="hidden flex h-4 w-4 items-center justify-center text-[#16136a]" data-pass-icon>
                                            <i class="ri-check-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        Upper &amp; lowercase letters
                                    </li>
                                    <li class="flex items-center gap-2" data-password-rule="number">
                                        <span class="flex h-4 w-4 items-center justify-center" data-fail-icon>
                                            <i class="ri-close-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        <span class="hidden flex h-4 w-4 items-center justify-center text-[#16136a]" data-pass-icon>
                                            <i class="ri-check-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        Include at least one number
                                    </li>
                                    <li class="flex items-center gap-2" data-password-rule="symbol">
                                        <span class="flex h-4 w-4 items-center justify-center" data-fail-icon>
                                            <i class="ri-close-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        <span class="hidden flex h-4 w-4 items-center justify-center text-[#16136a]" data-pass-icon>
                                            <i class="ri-check-line text-[13px]" aria-hidden="true"></i>
                                        </span>
                                        Add a special character
                                    </li>
                                </ul>
                            </div>
                        </label>
                        <label class="flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Confirm new password</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="ri-lock-password-line text-base" aria-hidden="true"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    x-ref="confirmPassword"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40"
                                    autocomplete="new-password"
                                >
                                <button type="button" class="absolute inset-y-0 right-0 mr-3 inline-flex items-center justify-center rounded-full bg-transparent px-2 text-slate-500 transition hover:text-[#16136a]" data-password-toggle="#password_confirmation">
                                    <i data-eye class="ri-eye-line text-base" aria-hidden="true"></i>
                                    <i data-eye-off class="ri-eye-off-line hidden text-base" aria-hidden="true"></i>
                                </button>
                            </div>
                        </label>
                        <div class="flex flex-wrap justify-end gap-2 pt-2">
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 shadow transition hover:-translate-y-0.5 hover:border-slate-300 hover:text-[#16136a] focus:outline-none focus:ring-2 focus:ring-slate-200/80"
                                x-on:click="cancel()"
                            >
                                <i class="ri-close-line text-base" aria-hidden="true"></i>
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2 text-sm font-semibold text-white shadow transition hover:bg-[#16136a]/90 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                                <i class="ri-check-line text-base" aria-hidden="true"></i>
                                Save password
                            </button>
                        </div>
                    </div>
                </article>
                <article class="animate-fade-slide rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <h2 class="text-lg font-semibold text-[#16136a]">Support contacts</h2>
                    <p class="mt-2 text-sm text-slate-500">Need assistance? Reach the GESA student support team directly.</p>
                    <ul class="mt-4 space-y-4 text-sm text-slate-600">
                        <li class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                                <i class="ri-phone-line text-lg" aria-hidden="true"></i>
                            </span>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Hotline</span>
                                <p class="mt-1 text-base font-semibold text-slate-900">
                                    055 318 5125 - President<br>
                                    059 787 0027 - Financial Secretary
                                </p>
                                <p class="text-xs text-slate-500">Available 08:00–20:00 GMT daily</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                                <i class="ri-mail-send-line text-lg" aria-hidden="true"></i>
                            </span>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Email</span>
                                <p class="mt-1 text-base font-semibold text-slate-900">gesaumat24@gmail.com</p>
                                <p class="text-xs text-slate-500">Send detailed issues for next-day responses</p>
                            </div>
                        </li>
                    </ul>
                </article>
            </section>

            <section class="grid gap-6 sm:grid-cols-2">
                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <i class="ri-id-card-line text-xl" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Contact &amp; identity</h2>
                            <p class="text-xs text-slate-500">Snapshot of how we recognise you across GESA.</p>
                        </div>
                    </div>
                    <dl class="mt-6 space-y-3 text-sm text-slate-600">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-user-line text-base text-[#16136a]" aria-hidden="true"></i> Full name</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->fullname }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-mail-line text-base text-[#16136a]" aria-hidden="true"></i> Email</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->email }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-phone-line text-base text-[#16136a]" aria-hidden="true"></i> Phone number</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->phone_number ?? 'Not provided' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-hashtag text-base text-[#16136a]" aria-hidden="true"></i> Index number</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->index_number ?? '—' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <dt class="flex items-center gap-2"><i class="ri-calendar-line text-sm text-[#16136a]" aria-hidden="true"></i> Created</dt>
                            <dd class="text-[13px] font-semibold text-slate-500">{{ optional($student->created_at)->format('M d, Y · g:i A') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 text-xs uppercase tracking-[0.2em] text-slate-400">
                            <dt class="flex items-center gap-2"><i class="ri-time-line text-sm text-[#16136a]" aria-hidden="true"></i> Last updated</dt>
                            <dd class="text-[13px] font-semibold text-slate-500">{{ optional($student->updated_at)->format('M d, Y · g:i A') }}</dd>
                        </div>
                    </dl>
                </article>

                <article class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <i class="ri-graduation-cap-line text-xl" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Academic placement</h2>
                            <p class="text-xs text-slate-500">See where you currently sit within the programme.</p>
                        </div>
                    </div>
                    <dl class="mt-6 space-y-3 text-sm text-slate-600">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-book-open-line text-base text-[#16136a]" aria-hidden="true"></i> Class</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->class ?? 'Not assigned' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="flex items-center gap-2 text-slate-500"><i class="ri-medal-line text-base text-[#16136a]" aria-hidden="true"></i> Year</dt>
                            <dd class="font-semibold text-slate-800">{{ $student->year ? 'Year ' . $student->year : 'Not set' }}</dd>
                        </div>
                    </dl>
                </article>
            </section>

         

            <div data-avatar-overlay class="fixed inset-0 z-[60] hidden items-center justify-center overflow-y-auto bg-slate-900/60 px-4 py-10 backdrop-blur-sm md:px-8">
                <div data-avatar-editor class="relative mx-auto w-full max-w-3xl rounded-[28px] border border-white/10 bg-white p-6 shadow-[0_45px_120px_-45px_rgba(22,19,106,0.8)] md:p-8">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div class="space-y-1">
                            <h3 class="text-xl font-semibold text-[#16136a] md:text-2xl">Adjust profile photo</h3>
                            <p class="text-sm text-slate-500 md:max-w-2xl">Drag the image to reposition, or use the zoom controls so your face fills the frame. When you’re happy, apply the crop.</p>
                        </div>
                        <button type="button" data-avatar-cancel class="inline-flex h-9 w-9 items-center justify-center self-start rounded-full border border-slate-200 text-slate-500 transition hover:border-slate-300 hover:text-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <span class="sr-only">Close crop dialog</span>
                            <i class="ri-close-line text-base" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="mt-6 flex max-h-[65vh] min-h-[320px] items-center justify-center overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-3 md:min-h-[400px]">
                        <img data-avatar-editor-image class="max-h-full w-full object-contain" alt="Adjust profile crop" />
                    </div>
                    <div data-avatar-controls class="mt-6 hidden flex flex-wrap items-center justify-end gap-3">
                        <button type="button" data-avatar-apply class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-[#16136a]/90 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                            <i class="ri-check-line text-base" aria-hidden="true"></i>
                            Use crop
                        </button>
                        <button type="button" data-avatar-cancel class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200/80">
                            <i class="ri-close-line text-base" aria-hidden="true"></i>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </form>

        </div>
    </div>
</x-layouts.dashboard>
