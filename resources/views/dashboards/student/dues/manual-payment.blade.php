<x-layouts.dashboard :title="$title">
    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-10">
            <header class="h-32 w-full animate-pulse rounded-xl bg-slate-100"></header>
            <div class="h-96 w-full animate-pulse rounded-xl bg-slate-50"></div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-4 rounded-xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-money-dollar-circle-line text-base"></i>
                        Manual Payment
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">{{ $due->description }}</h1>
                    <p class="text-sm text-slate-600">Total Amount: <span class="font-semibold text-slate-800">GHS {{ number_format((float) $due->amount, 2) }}</span></p>
                </div>
                <div>
                    <a href="{{ route('student.dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i class="ri-arrow-left-line"></i>
                        Cancel
                    </a>
                </div>
            </header>

            <div x-data="{ 
                selectedNetwork: 'mtn',
                copySuccess: false,
                copyNumber() {
                    navigator.clipboard.writeText('{{ $settings['merchant_number'] }}');
                    this.copySuccess = true;
                    setTimeout(() => this.copySuccess = false, 2000);
                }
            }" class="grid gap-8 lg:grid-cols-5">
                
                <!-- Instructions Column -->
                <div class="lg:col-span-3 space-y-8">
                    <div class="overflow-hidden rounded-xl border border-slate-200/60 bg-white shadow-2xl shadow-slate-200/50">
                        <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="flex items-center gap-3 text-lg font-semibold text-[#16136a]">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20">
                                    <i class="ri-list-check-3"></i>
                                </span>
                                Payment Instructions
                            </h2>
                            <div class="hidden sm:flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                                Step 1 of 2
                            </div>
                        </div>
                        
                        <div class="p-8">
                            @if($settings['manual_payment_instructions'])
                            <div class="mb-8 rounded-xl bg-indigo-50/50 p-5 border border-indigo-100/50">
                                <div class="flex gap-4">
                                    <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
                                        <i class="ri-information-fill text-sm"></i>
                                    </div>
                                    <p class="text-sm font-medium leading-relaxed text-indigo-900/80 italic">
                                        {{ $settings['manual_payment_instructions'] }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            <div>
                                <label class="mb-4 block text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Select Your Network</label>
                                <div class="grid grid-cols-3 gap-4">
                                    {{-- MTN Button --}}
                                    <button @click="selectedNetwork = 'mtn'" 
                                        :class="selectedNetwork === 'mtn' ? 'ring-2 ring-yellow-400 bg-yellow-400/5 border-yellow-400/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-yellow-400 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-semibold text-yellow-900 text-lg">M</span>
                                        </div>
                                        <span :class="selectedNetwork === 'mtn' ? 'text-yellow-900' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-widest">MTN</span>
                                        <div x-show="selectedNetwork === 'mtn'" class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-yellow-400 border-2 border-white flex items-center justify-center">
                                            <i class="ri-check-line text-yellow-900 text-xs font-semibold"></i>
                                        </div>
                                    </button>

                                    {{-- Telecel Button --}}
                                    <button @click="selectedNetwork = 'telecel'" 
                                        :class="selectedNetwork === 'telecel' ? 'ring-2 ring-red-500 bg-red-500/5 border-red-500/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-red-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-semibold text-white text-lg">T</span>
                                        </div>
                                        <span :class="selectedNetwork === 'telecel' ? 'text-red-700' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-widest">Telecel</span>
                                        <div x-show="selectedNetwork === 'telecel'" class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 border-2 border-white flex items-center justify-center">
                                            <i class="ri-check-line text-white text-xs font-semibold"></i>
                                        </div>
                                    </button>

                                    {{-- AT Button --}}
                                    <button @click="selectedNetwork = 'at'" 
                                        :class="selectedNetwork === 'at' ? 'ring-2 ring-blue-600 bg-blue-600/5 border-blue-600/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-semibold text-white text-lg">AT</span>
                                        </div>
                                        <span :class="selectedNetwork === 'at' ? 'text-blue-700' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-widest">AT</span>
                                        <div x-show="selectedNetwork === 'at'" class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-blue-600 border-2 border-white flex items-center justify-center">
                                            <i class="ri-check-line text-white text-xs font-semibold"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8 relative">
                                <div class="absolute inset-0 bg-slate-50/50 rounded-xl -m-2 -z-10"></div>
                                
                                {{-- MTN Content --}}
                                <div x-show="selectedNetwork === 'mtn'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                                    <div class="flex items-center gap-3 border-b border-yellow-100 pb-4">
                                        <i class="ri-smartphone-line text-2xl text-yellow-500"></i>
                                        <h3 class="text-base font-semibold text-yellow-900">Pay via MTN MoMoPay</h3>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-[10px] font-semibold text-yellow-900 group-hover:scale-110 transition-transform">1</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Dial <span class="text-yellow-600 font-semibold">*170#</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-[10px] font-semibold text-yellow-900 group-hover:scale-110 transition-transform">2</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Select <span class="text-yellow-600 font-semibold">MoMoPay & Pay Bill</span> (Option 2)</p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-[10px] font-semibold text-yellow-900 group-hover:scale-110 transition-transform">3</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Select <span class="text-yellow-600 font-semibold">MoMoPay</span> (Option 1)</p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-[10px] font-semibold text-yellow-900 group-hover:scale-110 transition-transform">4</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Merchant ID: <span class="text-yellow-600 font-semibold">{{ $settings['merchant_number'] }}</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-[10px] font-semibold text-yellow-900 group-hover:scale-110 transition-transform">5</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Amount & Reference, then Confirm with PIN</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Telecel Content --}}
                                <div x-show="selectedNetwork === 'telecel'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
                                    <div class="flex items-center gap-3 border-b border-red-100 pb-4">
                                        <i class="ri-smartphone-line text-2xl text-red-500"></i>
                                        <h3 class="text-base font-semibold text-red-900">Pay via Telecel Merchant</h3>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-red-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">1</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Dial <span class="text-red-600 font-semibold">*110#</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-red-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">2</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Select <span class="text-red-600 font-semibold">Pay Bill</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-red-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">3</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Select <span class="text-red-600 font-semibold">Others</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-red-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">4</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Merchant Code: <span class="text-red-600 font-semibold">{{ $settings['merchant_number'] }}</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-red-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">5</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Amount & Authorize with PIN</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- AT Content --}}
                                <div x-show="selectedNetwork === 'at'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
                                    <div class="flex items-center gap-3 border-b border-blue-100 pb-4">
                                        <i class="ri-smartphone-line text-2xl text-blue-500"></i>
                                        <h3 class="text-base font-semibold text-blue-900">Pay via AT Merchant</h3>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">1</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Dial <span class="text-blue-600 font-semibold">*110#</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">2</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Select <span class="text-blue-600 font-semibold">Pay Bill</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">3</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Merchant Code: <span class="text-blue-600 font-semibold">{{ $settings['merchant_number'] }}</span></p>
                                        </div>
                                        <div class="flex gap-4 group">
                                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-[10px] font-semibold text-white group-hover:scale-110 transition-transform">4</span>
                                            <p class="text-sm font-semibold text-slate-700 leading-normal">Enter Amount & Confirm Payment</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Merchant Card -->
                <div class="lg:col-span-2">
                    <div class="sticky top-10">
                        <div class="overflow-hidden rounded-xl bg-[#16136a] p-1 shadow-2xl shadow-[#16136a]/30">
                            <div class="rounded-xl bg-gradient-to-br from-[#1c198a] to-[#14125a] px-8 py-8">
                                <div class="space-y-8">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-white/40">Receiver</p>
                                            <h4 class="mt-1 text-lg font-semibold text-white">{{ $settings['merchant_name'] }}</h4>
                                        </div>
                                    </div>

                                    <div class="relative group cursor-pointer" @click="copyNumber()">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-white/40">Merchant Number</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-3xl font-mono font-semibold tracking-wider text-yellow-400 transition group-hover:text-yellow-300 group-hover:scale-[1.02] inline-block">{{ $settings['merchant_number'] }}</span>
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 border border-white/10 transition group-hover:bg-white/10">
                                                <i x-show="!copySuccess" class="ri-file-copy-line text-white/60"></i>
                                                <i x-show="copySuccess" x-transition class="ri-check-line text-emerald-400"></i>
                                            </div>
                                        </div>
                                        <p x-show="copySuccess" x-transition class="mt-2 text-[10px] font-semibold uppercase tracking-widest text-emerald-400">Number Copied to clipboard!</p>
                                    </div>

                                    <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                                        <div class="flex -space-x-2">
                                            <div class="h-8 w-8 rounded-full border-2 border-[#16136a] bg-yellow-400 flex items-center justify-center text-[10px] font-semibold">M</div>
                                            <div class="h-8 w-8 rounded-full border-2 border-[#16136a] bg-red-600 flex items-center justify-center text-[10px] font-semibold text-white">T</div>
                                            <div class="h-8 w-8 rounded-full border-2 border-[#16136a] bg-blue-600 flex items-center justify-center text-[10px] font-semibold text-white">A</div>
                                        </div>
                                        <p class="text-[10px] font-semibold text-white/40 uppercase tracking-widest">Multi-Network Support</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Bottom: Upload Proof Section -->
            <div class="mx-auto mt-12 max-w-2xl">
                <section class="overflow-hidden rounded-xl border border-[#16136a]/10 bg-white shadow-2xl shadow-[#16136a]/10">
                    <div class="bg-indigo-50/50 px-8 py-7 border-b border-indigo-100/50 flex items-center justify-between">
                        <h2 class="flex items-center gap-4 text-xl font-semibold text-[#16136a]">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20">
                                <i class="ri-upload-cloud-fill text-xl"></i>
                            </span>
                            Final Step: Submit Proof
                        </h2>
                    </div>
                    
                    <form action="{{ route('student.payments.manual.store', $due) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                        @csrf
                        
                        <div x-data="{ 
                            filename: '', 
                            preview: null,
                            handleFile(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    this.filename = file.name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => this.preview = e.target.result;
                                    reader.readAsDataURL(file);
                                }
                            }
                        }" class="space-y-4">
                            <div class="relative group">
                                <div :class="preview ? 'border-indigo-500 bg-indigo-50/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-100/50'" 
                                    class="relative flex min-h-[260px] items-center justify-center rounded-xl border-2 border-dashed transition-all duration-300">
                                    
                                    <input type="file" name="payment_proof" class="absolute inset-0 h-full w-full cursor-pointer opacity-0 z-10" @change="handleFile" required accept="image/*">
                                    
                                    <div class="text-center p-8 transition-all duration-300" :class="preview ? 'opacity-0 scale-95' : 'opacity-100 scale-100'">
                                        <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-xl bg-white shadow-xl shadow-slate-200/50 text-[#16136a] group-hover:scale-110 transition-transform">
                                            <i class="ri-camera-lens-line text-4xl"></i>
                                        </div>
                                        <p class="text-base font-semibold text-slate-700">Select payment screenshot</p>
                                        <p class="mt-2 text-[11px] font-medium text-slate-400 uppercase tracking-[0.2em]">MAX FILE SIZE: 2MB</p>
                                    </div>

                                    <template x-if="preview">
                                        <div class="absolute inset-4 overflow-hidden rounded-xl shadow-2xl ring-4 ring-white">
                                            <img :src="preview" class="h-full w-full object-cover">
                                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center mb-3">
                                                    <i class="ri-refresh-line text-2xl"></i>
                                                </div>
                                                <p class="text-xs font-semibold uppercase tracking-widest">Change Image</p>
                                                <p class="mt-2 text-[10px] font-semibold text-white/60 truncate px-4" x-text="filename"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @error('payment_proof')
                                <p class="flex items-center justify-center gap-2 text-xs font-semibold text-rose-500 bg-rose-50 p-3 rounded-xl">
                                    <i class="ri-error-warning-fill"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-[#16136a] px-10 py-5 shadow-2xl shadow-[#16136a]/30 transition-all hover:bg-[#1c198a] hover:scale-[1.01] active:scale-95">
                            <div class="flex items-center justify-center gap-4">
                                <span class="text-sm font-semibold uppercase tracking-[0.25em] text-white">Submit Payment for Approval</span>
                                <i class="ri-shield-check-fill text-xl text-white group-hover:rotate-12 transition-transform"></i>
                            </div>
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
