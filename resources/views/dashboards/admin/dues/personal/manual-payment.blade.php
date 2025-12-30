<x-layouts.admin :title="$title">
    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-12 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-10">
            <header class="h-32 w-full animate-pulse rounded-3xl bg-slate-100"></header>
            <div class="h-96 w-full animate-pulse rounded-3xl bg-slate-50"></div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-money-dollar-circle-line text-base"></i>
                        Personal Due Payment
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">{{ $due->description }}</h1>
                    <p class="text-sm text-slate-600">Total Amount: <span class="font-bold text-slate-800">GHS {{ number_format((float) $due->amount, 2) }}</span></p>
                </div>
                <div>
                    <a href="{{ route('admin.personal-dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
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
                
                <div class="lg:col-span-3 space-y-8">
                    <div class="overflow-hidden rounded-[2.5rem] border border-slate-200/60 bg-white shadow-2xl shadow-slate-200/50">
                        <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="flex items-center gap-3 text-lg font-bold text-[#16136a]">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20">
                                    <i class="ri-list-check-3"></i>
                                </span>
                                Payment Instructions
                            </h2>
                        </div>
                        
                        <div class="p-8">
                            @if($settings['manual_payment_instructions'])
                            <div class="mb-8 rounded-2xl bg-indigo-50/50 p-5 border border-indigo-100/50">
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
                                <label class="mb-4 block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Select Your Network</label>
                                <div class="grid grid-cols-3 gap-4">
                                    <button @click="selectedNetwork = 'mtn'" 
                                        :class="selectedNetwork === 'mtn' ? 'ring-2 ring-yellow-400 bg-yellow-400/5 border-yellow-400/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-2xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-yellow-400 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-black text-yellow-900 text-lg">M</span>
                                        </div>
                                        <span :class="selectedNetwork === 'mtn' ? 'text-yellow-900' : 'text-slate-500'" class="text-[11px] font-bold uppercase tracking-widest">MTN</span>
                                    </button>

                                    <button @click="selectedNetwork = 'telecel'" 
                                        :class="selectedNetwork === 'telecel' ? 'ring-2 ring-red-500 bg-red-500/5 border-red-500/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-2xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-red-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-black text-white text-lg">T</span>
                                        </div>
                                        <span :class="selectedNetwork === 'telecel' ? 'text-red-700' : 'text-slate-500'" class="text-[11px] font-bold uppercase tracking-widest">Telecel</span>
                                    </button>

                                    <button @click="selectedNetwork = 'at'" 
                                        :class="selectedNetwork === 'at' ? 'ring-2 ring-blue-600 bg-blue-600/5 border-blue-600/20' : 'border-slate-100 bg-slate-50/50 grayscale hover:grayscale-0'"
                                        class="group relative flex flex-col items-center gap-3 rounded-2xl border p-4 transition-all duration-300">
                                        <div class="h-12 w-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                            <span class="font-black text-white text-lg">AT</span>
                                        </div>
                                        <span :class="selectedNetwork === 'at' ? 'text-blue-700' : 'text-slate-500'" class="text-[11px] font-bold uppercase tracking-widest">AT</span>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8 relative">
                                <div class="absolute inset-0 bg-slate-50/50 rounded-3xl -m-2 -z-10"></div>
                                
                                <div x-show="selectedNetwork === 'mtn'" class="space-y-4">
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-yellow-400 text-[10px] font-black text-yellow-900">1</span>
                                        <p class="text-sm text-slate-700">Dial <span class="font-bold">*170#</span></p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-yellow-400 text-[10px] font-black text-yellow-900">2</span>
                                        <p class="text-sm text-slate-700">Select <span class="font-bold">MoMoPay & Pay Bill</span> (2) > <span class="font-bold">MoMoPay</span> (1)</p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-yellow-400 text-[10px] font-black text-yellow-900">3</span>
                                        <p class="text-sm text-slate-700">Enter Merchant ID: <span class="font-bold text-yellow-700">{{ $settings['merchant_number'] }}</span></p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-yellow-400 text-[10px] font-black text-yellow-900">4</span>
                                        <p class="text-sm text-slate-700">Complete payment and save the confirmation message.</p>
                                    </div>
                                </div>

                                <div x-show="selectedNetwork === 'telecel'" x-cloak class="space-y-4">
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-red-600 text-[10px] font-black text-white">1</span>
                                        <p class="text-sm text-slate-700">Dial <span class="font-bold">*110#</span></p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-red-600 text-[10px] font-black text-white">2</span>
                                        <p class="text-sm text-slate-700">Select <span class="font-bold">Pay Bill</span> > <span class="font-bold">Others</span></p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-red-600 text-[10px] font-black text-white">3</span>
                                        <p class="text-sm text-slate-700">Enter Merchant Code: <span class="font-bold text-red-700">{{ $settings['merchant_number'] }}</span></p>
                                    </div>
                                </div>

                                <div x-show="selectedNetwork === 'at'" x-cloak class="space-y-4">
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-[10px] font-black text-white">1</span>
                                        <p class="text-sm text-slate-700">Dial <span class="font-bold">*110#</span></p>
                                    </div>
                                    <div class="flex gap-4 group">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-[10px] font-black text-white">2</span>
                                        <p class="text-sm text-slate-700">Select <span class="font-bold">Pay Bill</span> and enter code: <span class="font-bold text-blue-700">{{ $settings['merchant_number'] }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="sticky top-10">
                        <div class="overflow-hidden rounded-[2.5rem] bg-[#16136a] p-1 shadow-2xl shadow-[#16136a]/30">
                            <div class="rounded-[2.3rem] bg-gradient-to-br from-[#1c198a] to-[#14125a] px-8 py-8">
                                <div class="space-y-8">
                                    <div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-white/40">Merchant Name</p>
                                        <h4 class="mt-1 text-lg font-bold text-white">{{ $settings['merchant_name'] }}</h4>
                                    </div>

                                    <div class="relative group cursor-pointer" @click="copyNumber()">
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-white/40">Merchant Number</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-3xl font-mono font-black tracking-wider text-yellow-400">{{ $settings['merchant_number'] }}</span>
                                            <i x-show="!copySuccess" class="ri-file-copy-line text-white/60"></i>
                                            <i x-show="copySuccess" class="ri-check-line text-emerald-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-12 max-w-2xl">
                <section class="overflow-hidden rounded-[3rem] border border-[#16136a]/10 bg-white shadow-2xl shadow-[#16136a]/10">
                    <div class="bg-indigo-50/50 px-8 py-7 border-b border-indigo-100/50">
                        <h2 class="flex items-center gap-4 text-xl font-bold text-[#16136a]">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20">
                                <i class="ri-upload-cloud-fill text-xl"></i>
                            </span>
                            Final Step: Submit Proof
                        </h2>
                    </div>
                    
                    <form action="{{ route('admin.personal-dues.manual.store', $due) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
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
                                    class="relative flex min-h-[260px] items-center justify-center rounded-[2.5rem] border-2 border-dashed transition-all duration-300">
                                    
                                    <input type="file" name="payment_proof" class="absolute inset-0 h-full w-full cursor-pointer opacity-0 z-10" @change="handleFile" required accept="image/*">
                                    
                                    <div class="text-center p-8 transition-all duration-300" :class="preview ? 'opacity-0 scale-95' : 'opacity-100 scale-100'">
                                        <p class="text-base font-bold text-slate-700">Select payment screenshot</p>
                                        <p class="mt-2 text-[11px] font-medium text-slate-400 uppercase tracking-[0.2em]">MAX FILE SIZE: 2MB</p>
                                    </div>

                                    <template x-if="preview">
                                        <div class="absolute inset-4 overflow-hidden rounded-[2rem] shadow-2xl ring-4 ring-white">
                                            <img :src="preview" class="h-full w-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-[2rem] bg-[#16136a] px-10 py-5 text-sm font-black uppercase tracking-[0.25em] text-white shadow-2xl transition-all hover:bg-[#1c198a] active:scale-95">
                            Submit Evidence for Verification
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-layouts.admin>
