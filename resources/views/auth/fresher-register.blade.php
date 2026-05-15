@php($title = 'Fresher Registration')

<x-layouts.auth :title="$title" card-width="max-w-2xl">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="GESA Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="mt-8 text-3xl font-semibold tracking-tight text-white lg:text-4xl">Fresher Registration</h1>
            <p class="mt-4 max-w-md text-base text-white/80 mx-auto">
                For students who don't have access to their university email yet.
            </p>
        </div>
    </x-slot:hero>

    <div class="mx-auto w-full max-w-7xl rounded-3xl bg-white/95 p-10 shadow-xl ring-1 ring-black/5 backdrop-blur auth-card-hover">
        <div class="mb-8 stagger-1">
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50/50 p-6 backdrop-blur-sm transition-all duration-300 hover:bg-yellow-50">
                <div class="flex gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                        <i class="ri-alert-line text-2xl" aria-hidden="true"></i>
                    </div>
                    <div class="text-sm text-yellow-800 leading-relaxed">
                        <p class="font-semibold text-base mb-2">Notice for Freshers</p>
                        <p class="mb-2">If you already have access to your university email (ending with @st.umat.edu.gh), please use the <a href="{{ route('auth.register') }}" class="font-semibold underline hover:text-yellow-900 transition-colors">regular registration form</a>.</p>
                        <p class="font-medium mt-3 opacity-80">Your registration will be reviewed by an administrator within 24-48 hours.</p>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.fresher-register.submit') }}" enctype="multipart/form-data" class="space-y-12" data-auth-form>
            @csrf

            <div class="space-y-10">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2 stagger-2">
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-semibold text-slate-700">First name</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-user-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autocomplete="given-name" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Kwame" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-semibold text-slate-700">Last name</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-user-3-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Mensah" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 stagger-3" x-data="{
                        username: '{{ old('username') }}',
                        checking: false,
                        available: null,
                        message: '',
                        timeout: null,
                        async checkUsername() {
                            if (!this.username || this.username.length < 2) {
                                this.available = null;
                                this.message = '';
                                return;
                            }
                            this.checking = true;
                            try {
                                const response = await fetch('{{ route('api.check-availability') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ field: 'username', value: this.username })
                                });
                                const data = await response.json();
                                this.available = data.available;
                                this.message = data.message;
                            } catch (error) {
                                this.available = null;
                            } finally {
                                this.checking = false;
                            }
                        },
                        debounceCheck() {
                            clearTimeout(this.timeout);
                            this.timeout = setTimeout(() => this.checkUsername(), 500);
                        }
                    }">
                        <label for="username" class="block text-sm font-semibold text-slate-700">Username</label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-user-star-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="username" name="username" type="text" x-model="username" @input="debounceCheck()" required autocomplete="username" 
                                :class="{
                                    'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                    'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                    'border-slate-200 bg-slate-50/10': available === null
                                }"
                                class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="kmensah" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <template x-if="checking">
                                    <i class="ri-loader-4-line animate-spin text-slate-400"></i>
                                </template>
                                <template x-if="!checking && available === true">
                                    <i class="ri-checkbox-circle-fill text-green-500 animate-fade-slide-up"></i>
                                </template>
                                <template x-if="!checking && available === false">
                                    <i class="ri-error-warning-fill text-red-500 animate-fade-slide-up"></i>
                                </template>
                            </div>
                        </div>
                        <p x-show="message && !checking" x-text="message" :class="available ? 'text-green-600' : 'text-red-600'" class="mt-1 text-xs font-medium transition-all animate-fade-slide-up"></p>
                    </div>

                    <div class="space-y-2 stagger-3" x-data="{
                        indexNumber: '{{ old('index_number') }}',
                        checking: false,
                        available: null,
                        message: '',
                        timeout: null,
                        async checkIndexNumber() {
                            if (!this.indexNumber || this.indexNumber.length < 9) {
                                this.available = null;
                                this.message = '';
                                return;
                            }
                            this.checking = true;
                            try {
                                const response = await fetch('{{ route('api.check-availability') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ field: 'index_number', value: this.indexNumber })
                                });
                                const data = await response.json();
                                this.available = data.available;
                                this.message = data.message;
                            } catch (error) {
                                this.available = null;
                            } finally {
                                this.checking = false;
                            }
                        },
                        debounceCheck() {
                            clearTimeout(this.timeout);
                            this.timeout = setTimeout(() => this.checkIndexNumber(), 500);
                        }
                    }">
                        <label for="index_number" class="block text-sm font-semibold text-slate-700">Reference number</label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-hashtag text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="index_number" name="index_number" type="text" x-model="indexNumber" @input="debounceCheck()" required inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only 
                                :class="{
                                    'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                    'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                    'border-slate-200 bg-slate-50/10': available === null
                                }"
                                class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="9012345623" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <template x-if="checking">
                                    <i class="ri-loader-4-line animate-spin text-slate-400"></i>
                                </template>
                                <template x-if="!checking && available === true">
                                    <i class="ri-checkbox-circle-fill text-green-500 animate-fade-slide-up"></i>
                                </template>
                                <template x-if="!checking && available === false">
                                    <i class="ri-error-warning-fill text-red-500 animate-fade-slide-up"></i>
                                </template>
                            </div>
                        </div>
                        <p x-show="message && !checking" x-text="message" :class="available ? 'text-green-600' : 'text-red-600'" class="mt-1 text-xs font-medium transition-all animate-fade-slide-up"></p>
                    </div>

                    <div class="space-y-2 stagger-4" x-data="{
                        email: '{{ old('email') }}',
                        checking: false,
                        available: null,
                        message: '',
                        timeout: null,
                        async checkEmail() {
                            if (!this.email || !this.email.includes('@') || this.email.length < 5) {
                                this.available = null;
                                this.message = '';
                                return;
                            }
                            this.checking = true;
                            try {
                                const response = await fetch('{{ route('api.check-availability') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ field: 'email', value: this.email })
                                });
                                const data = await response.json();
                                this.available = data.available;
                                this.message = data.message;
                            } catch (error) {
                                this.available = null;
                            } finally {
                                this.checking = false;
                            }
                        },
                        debounceCheck() {
                            clearTimeout(this.timeout);
                            this.timeout = setTimeout(() => this.checkEmail(), 800);
                        }
                    }">
                        <label for="email" class="block text-sm font-semibold text-slate-700">
                            Personal Email Address
                            <span class="text-xs font-normal text-slate-500">(Not university email)</span>
                        </label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-mail-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="email" name="email" type="email" x-model="email" @input="debounceCheck()" required autocomplete="email" 
                                :class="{
                                    'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                    'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                    'border-slate-200 bg-slate-50/10': available === null
                                }"
                                class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="kmensah@gmail.com" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <template x-if="checking">
                                    <i class="ri-loader-4-line animate-spin text-slate-400"></i>
                                </template>
                                <template x-if="!checking && available === true">
                                    <i class="ri-checkbox-circle-fill text-green-500 animate-fade-slide-up"></i>
                                </template>
                                <template x-if="!checking && available === false">
                                    <i class="ri-error-warning-fill text-red-500 animate-fade-slide-up"></i>
                                </template>
                            </div>
                        </div>
                        <p x-show="message && !checking" x-text="message" :class="available ? 'text-green-600' : 'text-red-600'" class="mt-1 text-xs font-medium transition-all animate-fade-slide-up"></p>
                    </div>

                    <div class="space-y-2 stagger-4" x-data="{
                        phoneNumber: '{{ old('phone_number') }}',
                        checking: false,
                        available: null,
                        message: '',
                        timeout: null,
                        async checkPhoneNumber() {
                            if (!this.phoneNumber || this.phoneNumber.length < 9) {
                                this.available = null;
                                this.message = '';
                                return;
                            }
                            this.checking = true;
                            try {
                                const response = await fetch('{{ route('api.check-availability') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ field: 'phone_number', value: this.phoneNumber })
                                });
                                const data = await response.json();
                                this.available = data.available;
                                this.message = data.message;
                            } catch (error) {
                                this.available = null;
                            } finally {
                                this.checking = false;
                            }
                        },
                        debounceCheck() {
                            clearTimeout(this.timeout);
                            this.timeout = setTimeout(() => this.checkPhoneNumber(), 500);
                        }
                    }">
                        <label for="phone_number" class="block text-sm font-semibold text-slate-700">Phone number</label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-phone-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="phone_number" name="phone_number" type="tel" x-model="phoneNumber" @input="debounceCheck()" required inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only 
                                :class="{
                                    'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                    'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                    'border-slate-200 bg-slate-50/10': available === null
                                }"
                                class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="0541234567" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <template x-if="checking">
                                    <i class="ri-loader-4-line animate-spin text-slate-400"></i>
                                </template>
                                <template x-if="!checking && available === true">
                                    <i class="ri-checkbox-circle-fill text-green-500 animate-fade-slide-up"></i>
                                </template>
                                <template x-if="!checking && available === false">
                                    <i class="ri-error-warning-fill text-red-500 animate-fade-slide-up"></i>
                                </template>
                            </div>
                        </div>
                        <p x-show="message && !checking" x-text="message" :class="available ? 'text-green-600' : 'text-red-600'" class="mt-1 text-xs font-medium transition-all animate-fade-slide-up"></p>
                    </div>

                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2 stagger-5">
                        <div class="space-y-2">
                            <label for="class" class="block text-sm font-semibold text-slate-700">Program</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-book-open-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="class" name="class" required class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10">
                                    <option value="" disabled {{ old('class') ? '' : 'selected' }}>Select program</option>
                                    @foreach (['Geomatic Engineering', 'Land Administration', 'Spatial Planning'] as $program)
                                        <option value="{{ $program }}" {{ old('class') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="year" class="block text-sm font-semibold text-slate-700">Year</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-medal-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="year" name="year" required class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10">
                                    <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select year</option>
                                    @foreach (['1', '2', '3', '4'] as $year)
                                        <option value="{{ $year }}" {{ old('year') === $year ? 'selected' : '' }}>Year {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-2 stagger-5">
                        <label for="reason" class="block text-sm font-semibold text-slate-700">
                            Why can't you access your student email?
                        </label>
                        <textarea id="reason" name="reason" rows="3" required maxlength="500" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 px-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Example: I am a fresher and haven't received my student email credentials yet...">{{ old('reason') }}</textarea>
                        <p class="text-xs text-slate-500 font-medium">Please be specific. This helps administrators verify your request.</p>
                    </div>

                    <div class="lg:col-span-2 space-y-2 stagger-5">
                        <label for="student_id" class="block text-sm font-semibold text-slate-700">
                            Student ID Card (Optional but recommended)
                        </label>
                        <div class="relative group">
                            <input id="student_id" name="student_id" type="file" accept="image/png,image/jpeg,image/jpg" class="block w-full text-sm text-slate-900 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#16136a] file:text-white hover:file:bg-[#18188a] file:cursor-pointer file:transition-all border border-slate-200 rounded-2xl cursor-pointer bg-slate-50/10 py-1.5 px-1.5 focus:outline-none transition-all duration-300 group-hover:bg-white/20" />
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Upload a clear photo of your student ID to speed up verification (Max 2MB)</p>
                    </div>
                </div>

                <div class="space-y-6 stagger-5">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-lock-password-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="password" name="password" type="password" required autocomplete="new-password" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Create a secure password" />
                            <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-all duration-300 hover:text-[#16136a] hover:scale-110 active:scale-90" aria-label="Toggle password visibility">
                                <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                                <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Strength System -->
                    <div class="stagger-4 hidden space-y-4 rounded-2xl border border-slate-200/50 bg-slate-50/5 p-5 shadow-inner backdrop-blur-sm transition-all duration-300 hover:bg-white/10" data-password-strength data-password-input="#password">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Security Rating</span>
                            <span data-password-strength-label class="text-xs font-semibold text-slate-400 transition-colors duration-300">Weak</span>
                        </div>
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200/50">
                            <div data-password-strength-bar class="h-full w-0 bg-red-500 transition-all duration-500"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="length" data-state="fail">
                                <i class="ri-checkbox-circle-fill text-sm"></i>
                                <span>Min. 8 Chars</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="mixed" data-state="fail">
                                <i class="ri-checkbox-circle-fill text-sm"></i>
                                <span>Case-sensitive</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="number" data-state="fail">
                                <i class="ri-checkbox-circle-fill text-sm"></i>
                                <span>Numbers</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-[#16136a] data-[state=pass]:scale-105" data-password-rule="symbol" data-state="fail">
                                <i class="ri-checkbox-circle-fill text-sm"></i>
                                <span>Symbols</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm password</label>
                        <div class="group auth-input-group relative">
                            <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                <i class="ri-checkbox-circle-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="Confirm your password" />
                            <button type="button" data-password-toggle="#password_confirmation" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-all duration-300 hover:text-[#16136a] hover:scale-110 active:scale-90" aria-label="Toggle password visibility">
                                <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                                <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stagger-5 flex items-start space-x-4 rounded-3xl border border-slate-200/50 bg-slate-50/5 p-6 transition-all duration-300 hover:bg-white/10 hover:shadow-inner backdrop-blur-sm">
                <div class="flex h-6 items-center">
                    <input id="accept_terms" name="accept_terms" type="checkbox" value="1" required class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border border-slate-300 bg-white/50 transition-all duration-300 checked:border-[#16136a] checked:bg-[#16136a] focus:outline-none focus:ring-4 focus:ring-[#16136a]/10">
                </div>
                <label for="accept_terms" class="text-sm leading-relaxed text-slate-600 transition-colors duration-300 peer-checked:text-slate-900">
                    I have read and agree to the 
                    <a href="{{ route('legal.terms') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Terms</a> 
                    and 
                    <a href="{{ route('legal.privacy') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Privacy Policy</a>. 
                </label>
            </div>

            <div class="stagger-5 flex items-center justify-between pt-8 border-t border-slate-200/50">
                <a href="{{ route('auth.register') }}" class="group flex items-center gap-2 text-sm font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:-translate-x-1">
                    <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-1"></i>
                    <span>Back to regular registration</span>
                </a>
                <button type="submit" class="auth-button-press group relative flex items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-8 py-4 text-sm font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                    <div class="flex items-center space-x-3 transition-transform duration-300 group-hover:scale-105">
                        <i class="ri-send-plane-line text-lg" aria-hidden="true"></i>
                        <span>Submit Registration</span>
                    </div>
                </button>
            </div>
        </form>

        <div class="stagger-5 mt-10 text-center text-sm text-slate-500">
            <p>
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Sign in</a>
            </p>
        </div>
    </div>

</x-layouts.auth>
