<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-4xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8">
            <header class="h-32 w-full animate-pulse rounded-3xl bg-slate-100"></header>
            <div class="h-96 w-full animate-pulse rounded-3xl bg-slate-50"></div>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-settings-4-line text-base"></i>
                        Configuration
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Payment Settings</h1>
                    <p class="text-sm text-slate-600">Configure manual payment options and instructions for students.</p>
                </div>
                <div>
                    <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i class="ri-arrow-left-line"></i>
                        Back to dues
                    </a>
                </div>
            </header>

            @if (session('status'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        <p class="text-sm font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <section x-data="{ manualEnabled: '{{ $settings['manual_payment_enabled'] }}' }" class="rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:p-8">
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-lg font-semibold text-[#16136a]">General Settings</h2>
                            <p class="text-sm text-slate-500">Enable or disable manual payment options globally.</p>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <div class="space-y-1">
                                <label for="manual_payment_enabled" class="font-semibold text-slate-700">Active Payment Method</label>
                                <p class="text-xs text-slate-500">Choose how students should pay their dues (Manual vs Processor).</p>
                            </div>
                            <div class="relative inline-block w-48 align-middle select-none transition duration-200 ease-in">
                                <select name="manual_payment_enabled" id="manual_payment_enabled" x-model="manualEnabled" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]">
                                    <option value="1">Manual Payment</option>
                                    <option value="0">Payment Processor</option>
                                </select>
                            </div>
                        </div>

                        <div x-show="manualEnabled === '1'" class="space-y-6 pt-4 border-t border-slate-100">
                            <div>
                                <h2 class="text-lg font-semibold text-[#16136a]">Merchant Account Details</h2>
                                <p class="text-sm text-slate-500">Enter the unified Mobile Money merchant details where students should send payments.</p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-3">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Account Network</label>
                                    <input type="text" name="merchant_network" value="{{ $settings['merchant_network'] }}" placeholder="e.g. MTN / Telecel / All Networks" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-[#16136a] focus:ring-2 focus:ring-[#16136a]/20">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Merchant Number</label>
                                    <input type="text" name="merchant_number" value="{{ $settings['merchant_number'] }}" placeholder="e.g. 0541234567" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-[#16136a] focus:ring-2 focus:ring-[#16136a]/20">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Account Name</label>
                                    <input type="text" name="merchant_name" value="{{ $settings['merchant_name'] }}" placeholder="e.g. GESA UMaT" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-[#16136a] focus:ring-2 focus:ring-[#16136a]/20">
                                </div>
                            </div>
                        </div>

                        <div x-show="manualEnabled === '1'" class="space-y-6 pt-4 border-t border-slate-100">
                            <div>
                                <h2 class="text-lg font-semibold text-[#16136a]">Payment Instructions</h2>
                                <p class="text-sm text-slate-500">Provide step-by-step instructions for each network. Students will select their network to see the relevant steps.</p>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Payment Instructions</label>
                                    <textarea name="manual_payment_instructions" rows="6" placeholder="e.g. 1. Dial *170#&#10;2. Select Transfer Money...&#10;3. Use your Student ID as reference." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm transition focus:border-[#16136a] focus:ring-2 focus:ring-[#16136a]/20">{{ $settings['manual_payment_instructions'] }}</textarea>
                                    <p class="text-[10px] text-slate-400 italic">These instructions will be shown to students on the manual payment page.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-8 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition hover:-translate-y-1 hover:bg-[#16136a]/90">
                            <i class="ri-save-line text-lg"></i>
                            Save Settings
                        </button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</x-layouts.admin>
