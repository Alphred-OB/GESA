@php($title = 'Create Account')

<x-layouts.auth :title="$title" card-width="max-w-4xl">
    <div class="p-8 md:p-12 auth-card-hover">
        <div class="mb-12 text-center stagger-1">
            <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Student Registration</h2>
            <p class="mt-2 text-base text-slate-500">Provide your student details to get started with the GESA Portal.</p>
        </div>

        <form method="POST" action="{{ route('auth.register.submit') }}" enctype="multipart/form-data" class="space-y-12" data-auth-form>
            @csrf

            <div class="space-y-12">
                <!-- Section: Personal Information -->
                <div class="stagger-2 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] transition-transform duration-300 hover:scale-110">
                            <i class="ri-user-settings-line text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 tracking-tight">Personal Information</h3>
                        <div class="h-px flex-1 bg-slate-200/50"></div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-semibold text-slate-700">First Name</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-user-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autocomplete="given-name" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="e.g. Kwame" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-semibold text-slate-700">Last Name</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-user-3-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name" class="auth-input block w-full rounded-2xl border border-slate-200 bg-slate-50/10 py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:border-[#16136a] focus:bg-white/50 focus:outline-none focus:ring-4 focus:ring-[#16136a]/10" placeholder="e.g. Mensah" />
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2" x-data="{
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
                                    class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="e.g. kmensah" />
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

                        <div class="space-y-2" x-data="{
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
                            <label for="index_number" class="block text-sm font-semibold text-slate-700">Reference Number</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-hashtag text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="index_number" name="index_number" type="text" x-model="indexNumber" @input="debounceCheck()" required inputmode="numeric" pattern='\d{9,11}' maxlength="11" data-numeric-only 
                                    :class="{
                                        'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                        'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                        'border-slate-200 bg-slate-50/10': available === null
                                    }"
                                    class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="e.g. 90123456" />
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
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2" x-data="{
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
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
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
                                    class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="e.g. name@example.com" />
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

                        <div class="space-y-2" x-data="{
                            phoneNumber: '{{ old('phone_number') }}',
                            checking: false,
                            available: null,
                            message: '',
                            timeout: null,
                            async checkPhone() {
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
                                this.timeout = setTimeout(() => this.checkPhone(), 500);
                            }
                        }">
                            <label for="phone_number" class="block text-sm font-semibold text-slate-700">Phone Number</label>
                            <div class="group auth-input-group relative">
                                <span class="auth-input-icon absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-all duration-300 group-focus-within:text-[#16136a]">
                                    <i class="ri-phone-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="phone_number" name="phone_number" type="tel" x-model="phoneNumber" @input="debounceCheck()" required inputmode="numeric" pattern='\d{9,11}' maxlength="11" data-numeric-only 
                                    :class="{
                                        'border-green-500 focus:border-green-500 focus:ring-green-500/10': available === true,
                                        'border-red-500 focus:border-red-500 focus:ring-red-500/10': available === false,
                                        'border-slate-200 bg-slate-50/10': available === null
                                    }"
                                    class="auth-input block w-full rounded-2xl border py-3.5 pl-12 pr-12 text-sm text-slate-900 shadow-sm transition-all duration-300 focus:bg-white/50 focus:outline-none focus:ring-4" placeholder="e.g. 0541234567" />
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
                    </div>
                </div>

                <!-- Section: Academic Details -->
                <div class="stagger-3 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] transition-transform duration-300 hover:scale-110">
                            <i class="ri-graduation-cap-line text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 tracking-tight">Academic Details</h3>
                        <div class="h-px flex-1 bg-slate-200/50"></div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
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
                </div>

                <!-- Section: Security & Documents -->
                <div class="stagger-4 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a]/5 text-[#16136a] transition-transform duration-300 hover:scale-110">
                            <i class="ri-lock-password-line text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 tracking-tight">Security & Documents</h3>
                        <div class="h-px flex-1 bg-slate-200/50"></div>
                    </div>

                    <div class="grid gap-8 lg:grid-cols-2">
                        <!-- Left: Document Upload -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-slate-700">
                                Student ID or Registration Slip
                                <span class="text-xs font-normal text-slate-500">(Optional)</span>
                            </label>

                            <div x-data="{ 
                                fileName: '', 
                                fileSize: '',
                                isDragOver: false,
                                handleFileSelect(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.fileName = file.name;
                                        this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                                    }
                                },
                                removeFile() {
                                    this.fileName = '';
                                    this.fileSize = '';
                                    this.$refs.fileInput.value = '';
                                }
                            }" class="relative transition-all duration-300 hover:scale-[1.02]">
                                <input type="file" id="student_document" name="student_document" accept="image/png,image/jpeg,image/jpg,application/pdf" class="sr-only" x-ref="fileInput" @change="handleFileSelect" />
                                <div @click="$refs.fileInput.click()" @dragover.prevent="isDragOver = true" @dragleave.prevent="isDragOver = false" @drop.prevent="isDragOver = false; $refs.fileInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.fileInput})"
                                    :class="{
                                        'border-[#16136a] bg-[#16136a]/5 ring-4 ring-[#16136a]/10': isDragOver,
                                        'border-slate-200 bg-slate-50/10 hover:bg-white/20': !isDragOver && !fileName,
                                        'border-green-500 bg-green-50/20': fileName
                                    }"
                                    class="relative flex min-h-[220px] cursor-pointer flex-col items-center justify-center rounded-[2.5rem] border-2 border-dashed transition-all duration-300 shadow-sm backdrop-blur-sm"
                                >
                                    <template x-if="!fileName">
                                        <div class="flex flex-col items-center p-8 text-center animate-fade-slide-up">
                                            <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-white/80 shadow-xl shadow-slate-200/50 ring-1 ring-slate-100 transition-transform hover:scale-110">
                                                <i class="ri-upload-cloud-2-line text-3xl text-[#16136a]"></i>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-700">Click to upload <span class="text-slate-400 font-medium">or drag and drop</span></p>
                                            <p class="mt-2 text-[11px] font-medium uppercase tracking-wider text-slate-400">PNG, JPG or PDF • MAX 2MB</p>
                                        </div>
                                    </template>

                                    <template x-if="fileName">
                                        <div class="flex w-full items-center gap-5 p-6 animate-fade-slide-up">
                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-green-500 text-white shadow-lg shadow-green-500/20">
                                                <i class="ri-file-check-line text-2xl"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-semibold text-slate-800" x-text="fileName"></p>
                                                <p class="text-xs font-medium text-slate-500" x-text="fileSize"></p>
                                            </div>
                                            <button @click.stop="removeFile()" type="button" class="flex h-10 w-10 items-center justify-center rounded-full text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all duration-300 hover:scale-110 active:scale-90">
                                                <i class="ri-close-line text-2xl"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Password & Confirmation -->
                        <div class="space-y-6">
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

                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
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

                            <!-- Password Strength Indicator -->
                            <div class="stagger-4 hidden space-y-4 rounded-[1.5rem] border border-slate-200/50 bg-slate-50/5 p-5 shadow-inner backdrop-blur-sm transition-all duration-300 hover:bg-white/10" data-password-strength data-password-input="#password">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Security Rating</span>
                                    <span data-password-strength-label class="text-xs font-semibold text-slate-400 transition-colors duration-300">Low</span>
                                </div>
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200/50">
                                    <div data-password-strength-bar class="h-full w-0 bg-red-500 transition-all duration-500"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-green-600 data-[state=pass]:scale-105" data-password-rule="length" data-state="fail">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>Min. 8 Chars</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-green-600 data-[state=pass]:scale-105" data-password-rule="mixed" data-state="fail">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>Case-sensitive</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-green-600 data-[state=pass]:scale-105" data-password-rule="number" data-state="fail">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>Numbers</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 transition-all duration-300 data-[state=pass]:text-green-600 data-[state=pass]:scale-105" data-password-rule="symbol" data-state="fail">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>Symbols</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Consent -->
            <div class="stagger-5 flex items-start space-x-4 rounded-3xl border border-slate-200/50 bg-slate-50/5 p-6 transition-all duration-300 hover:bg-white/10 hover:shadow-inner backdrop-blur-sm">
                <div class="flex h-6 items-center">
                    <input id="accept_terms" name="accept_terms" type="checkbox" value="1" required class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border border-slate-300 bg-white/50 transition-all duration-300 checked:border-[#16136a] checked:bg-[#16136a] focus:outline-none focus:ring-4 focus:ring-[#16136a]/10">
                </div>
                <label for="accept_terms" class="text-sm leading-relaxed text-slate-600 transition-colors duration-300 peer-checked:text-slate-900">
                    I have read and agree to the 
                    <a href="{{ route('legal.terms') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Terms of Service</a> 
                    and 
                    <a href="{{ route('legal.privacy') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Privacy Policy</a>. 
                    I understand that my registration requires administrative approval.
                </label>
            </div>

            <!-- Submit Actions -->
            <div class="stagger-5 flex flex-col items-center gap-8 border-t border-slate-200/50 pt-12">
                <button type="submit" class="auth-button-press group relative flex w-full max-w-md items-center justify-center overflow-hidden rounded-2xl bg-[#16136a] px-8 py-5 text-base font-semibold text-white shadow-xl shadow-[#16136a]/20 transition-all duration-300 hover:-translate-y-1 hover:bg-[#18188a] hover:shadow-2xl hover:shadow-[#16136a]/30">
                    <div class="flex items-center space-x-3 transition-transform duration-300 group-hover:scale-105">
                        <span>Submit Registration</span>
                        <i class="ri-user-add-line transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6"></i>
                    </div>
                </button>

                <p class="text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-[#16136a] transition-all duration-300 hover:text-[#18188a] hover:underline hover:scale-105 inline-block">Sign in instead</a>
                </p>
            </div>
        </form>
    </div>
</x-layouts.auth>
