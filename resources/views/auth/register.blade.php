@php($title = 'Create Account')

<x-layouts.auth :title="$title" card-width="max-w-2xl">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg">
                <img src="{{ asset('logo.png') }}" alt="GESA Portal Logo" class="h-full w-full object-contain" loading="lazy">
            </div>
            <h1 class="mt-8 text-3xl font-semibold tracking-tight text-white lg:text-4xl">Join the GESA Community</h1>
            <p class="mt-4 max-w-md text-base text-white/80 mx-auto">
                Submit a registration request to join. Once approved by an administrator, you'll be able to manage dues and access resources.
            </p>
        </div>
    </x-slot:hero>

    <div class="mx-auto w-full max-w-7xl rounded-3xl bg-white/95 p-10 shadow-xl ring-1 ring-black/5 backdrop-blur">
        <div class="mb-8 text-center lg:text-left">
            <h2 class="text-2xl font-semibold text-slate-900">Student Registration</h2>
            <p class="mt-2 text-sm text-slate-600">Provide your student details to get started.</p>
        </div>

        <form method="POST" action="{{ route('auth.register.submit') }}" enctype="multipart/form-data" class="space-y-12" data-auth-form>
            @csrf

            <div class="space-y-10">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-medium text-slate-700">First name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-user-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required autocomplete="given-name" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Kwame" />
                            </div>
                            @error('first_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-medium text-slate-700">Last name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-user-3-line text-lg" aria-hidden="true"></i>
                                </span>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required autocomplete="family-name" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Mensah" />
                            </div>
                            @error('last_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-user-star-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="kmensah" />
                        </div>
                        @error('username')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="index_number" class="block text-sm font-medium text-slate-700">Reference number</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-hashtag text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="index_number" name="index_number" type="text" value="{{ old('index_number') }}" required inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="9012345623" />
                        </div>
                        @error('index_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-slate-700">
                            Email Address 
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-mail-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email" 
                                class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" 
                                placeholder="name@example.com" 
                            />
                        </div>
                        
                        <p class="text-xs text-slate-600">
                            You can use your personal or university email address.
                        </p>
                        
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone_number" class="block text-sm font-medium text-slate-700">Phone number</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-phone-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number') }}" inputmode="numeric" pattern="\d{9,11}" maxlength="11" data-numeric-only class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="0541234567" />
                        </div>
                        @error('phone_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="class" class="block text-sm font-medium text-slate-700">Program</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-book-open-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="class" name="class" required class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                    <option value="" disabled {{ old('class') ? '' : 'selected' }}>Select program</option>
                                    @foreach (['Geomatic Engineering', 'Land Administration', 'Spatial Planning'] as $program)
                                        <option value="{{ $program }}" {{ old('class') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('class')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="year" class="block text-sm font-medium text-slate-700">Year</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="ri-medal-line text-lg" aria-hidden="true"></i>
                                </span>
                                <select id="year" name="year" required class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                    <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select year</option>
                                    @foreach (['1', '2', '3', '4'] as $year)
                                        <option value="{{ $year }}" {{ old('year') === $year ? 'selected' : '' }}>Year {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2 space-y-4">
                            <label class="block text-sm font-medium text-slate-700">
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
                            }" class="relative">
                                <!-- Hidden Native Input -->
                                <input 
                                    type="file" 
                                    id="student_document" 
                                    name="student_document" 
                                    accept="image/png,image/jpeg,image/jpg,application/pdf" 
                                    class="sr-only"
                                    x-ref="fileInput"
                                    @change="handleFileSelect"
                                />

                                <!-- Drag & Drop Zone -->
                                <div 
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent="isDragOver = true"
                                    @dragleave.prevent="isDragOver = false"
                                    @drop.prevent="isDragOver = false; $refs.fileInput.files = $event.dataTransfer.files; handleFileSelect({target: $refs.fileInput})"
                                    :class="{
                                        'border-[#16136a] bg-[#16136a]/5 ring-4 ring-[#16136a]/10': isDragOver,
                                        'border-slate-300 bg-slate-50 hover:bg-slate-100': !isDragOver && !fileName,
                                        'border-green-500 bg-green-50': fileName
                                    }"
                                    class="relative flex min-h-[160px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed transition-all duration-200"
                                >
                                    <template x-if="!fileName">
                                        <div class="flex flex-col items-center p-6 text-center">
                                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200">
                                                <i class="ri-upload-cloud-2-line text-2xl text-[#16136a]"></i>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-700">
                                                Click to upload <span class="text-slate-500">or drag and drop</span>
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">PNG, JPG or PDF (MAX. 2MB)</p>
                                        </div>
                                    </template>

                                    <template x-if="fileName">
                                        <div class="flex w-full items-center gap-4 p-4">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                                                <i class="ri-file-check-line text-2xl"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-semibold text-slate-800" x-text="fileName"></p>
                                                <p class="text-xs text-slate-500" x-text="fileSize"></p>
                                            </div>
                                            <button 
                                                @click.stop="removeFile()" 
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                            >
                                                <i class="ri-close-line text-xl"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            @error('student_document')
                                <p class="text-sm text-red-600 font-medium flex items-center gap-1">
                                    <i class="ri-error-warning-line"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="ri-lock-password-line text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Create a secure password" />
                            <button type="button" data-password-toggle="#password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                                <i data-eye class="ri-eye-line text-lg" aria-hidden="true"></i>
                                <i data-eye-off class="ri-eye-off-line hidden text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4" data-password-strength data-password-input="#password">
                        <div class="flex items-center justify-between text-xs font-medium text-slate-600">
                            <span>Password strength</span>
                            <span data-password-strength-label class="text-slate-500">Weak</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-200">
                            <div data-password-strength-bar class="h-2 w-1/12 rounded-full bg-red-500 transition-all duration-300"></div>
                        </div>
                        <ul class="space-y-1 text-xs text-slate-500">
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#16136a]" data-password-rule="length" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>At least 8 characters</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#16136a]" data-password-rule="mixed" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Includes uppercase and lowercase letters</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#16136a]" data-password-rule="number" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Contains at least one number</span>
                            </li>
                            <li class="flex items-center gap-2 data-[state=pass]:text-[#16136a]" data-password-rule="symbol" data-state="fail">
                                <svg data-pass-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4.5 4.5L19 7" />
                                </svg>
                                <svg data-fail-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 5 14 14" />
                                    <path d="m19 5-14 14" />
                                </svg>
                                <span>Contains at least one special character</span>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect width="18" height="11" x="3" y="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" placeholder="Confirm your password" />
                            <button type="button" data-password-toggle="#password_confirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-slate-600" aria-label="Toggle password visibility">
                                <svg data-eye xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.584 10.59a1.999 1.999 0 0 0 2.828 2.83" />
                                    <path d="M9.878 5.132A9.76 9.76 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a9.88 9.88 0 0 1-1.616 3.043m-4.112 2.773A9.711 9.711 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a9.835 9.835 0 0 1 2.223-3.592" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-start space-x-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <input id="accept_terms" name="accept_terms" type="checkbox" value="1" required class="mt-1 h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" {{ old('accept_terms') ? 'checked' : '' }}>
                <label for="accept_terms" class="text-sm text-slate-600">
                    I agree to the
                    <a href="{{ route('legal.terms') }}" class="font-semibold text-[#16136a] hover:underline">Terms</a>
                    and
                    <a href="{{ route('legal.privacy') }}" class="font-semibold text-[#16136a] hover:underline">Privacy Policy</a>
                    of the GESA Portal.
                </label>
            </div>
            @error('accept_terms')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-end">
                <button type="submit" class="flex items-center justify-center space-x-2 rounded-xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/30 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#18188a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                    <i class="ri-send-plane-line text-lg" aria-hidden="true"></i>
                    <span>Submit Request</span>
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-slate-600">
            <p>
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-[#16136a] hover:underline">Sign in</a>
                instead.
            </p>
        </div>
    </div>

    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#16136a]/20">
                <i class="ri-loader-4-line animate-spin text-2xl text-[#16136a]" aria-hidden="true"></i>
            </div>
            <p class="text-sm font-medium text-slate-700">Creating your account…</p>
        </div>
    </div>
</x-layouts.auth>
