@php($title = 'Edit due · ' . ($due->student->fullname ?? $due->student->username ?? 'Student #' . $due->student_id))

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-5xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                <i class="ri-money-dollar-circle-line text-base" aria-hidden="true"></i>
                Update student due
            </p>
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-[#16136a]">{{ $due->description }}</h1>
                    <p class="mt-1 text-sm text-slate-600">Adjust billing details for the selected student. Changes take effect immediately.</p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <p class="font-semibold text-slate-900">{{ $due->student->fullname ?? $due->student->username ?? 'Student #' . $due->student_id }}</p>
                    <p>{{ $due->student->email ?? 'No email on file' }}</p>
                    <p class="text-xs text-slate-500">{{ $due->student->class ?? '—' }} · {{ $due->student->year ? 'Year ' . $due->student->year : '—' }}</p>
                </div>
            </div>
        </header>

        <form method="POST" action="{{ route('admin.dues.update', $due) }}" class="space-y-10">
            @csrf
            @method('PUT')

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Due details</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Description</span>
                        <input type="text" name="description" value="{{ old('description', $due->description) }}" maxlength="255" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('description')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Academic year</span>
                        <input type="text" name="academic_year" value="{{ old('academic_year', $due->academic_year) }}" pattern="^\d{4}/\d{4}$" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('academic_year')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <label class="flex flex-col gap-2 md:col-span-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Due date</span>
                        <input type="date" name="due_date" value="{{ old('due_date', optional($due->due_date)->format('Y-m-d')) }}" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('due_date')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Amount (GHS)</span>
                        <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', $due->amount) }}" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('amount')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Payment status</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</span>
                        <select name="payment_status" required class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('payment_status', $due->payment_status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_status')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-[#16136a] focus:ring-[#16136a]" {{ old('is_active', $due->is_active) ? 'checked' : '' }}>
                        <span>Mark due as active</span>
                    </label>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Payment method</span>
                        <input type="text" name="payment_method" value="{{ old('payment_method', $due->payment_method) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('payment_method')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Payment reference</span>
                        <input type="text" name="payment_reference" value="{{ old('payment_reference', $due->payment_reference) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('payment_reference')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Payment date</span>
                        <input type="datetime-local" name="payment_date" value="{{ old('payment_date', optional($due->payment_date)->format('Y-m-d\TH:i')) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('payment_date')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Verification date</span>
                        <input type="datetime-local" name="verification_date" value="{{ old('verification_date', optional($due->verification_date)->format('Y-m-d\TH:i')) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('verification_date')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <label class="flex flex-col gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Verification notes</span>
                    <textarea name="verification_notes" rows="3" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">{{ old('verification_notes', $due->verification_notes) }}</textarea>
                    @error('verification_notes')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </label>
            </section>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <h2 class="text-lg font-semibold text-[#16136a]">Additional metadata</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Network</span>
                        <input type="text" name="network" value="{{ old('network', $due->network) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('network')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Reference number</span>
                        <input type="text" name="reference_number" value="{{ old('reference_number', $due->reference_number) }}" class="h-11 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        @error('reference_number')
                            <span class="text-xs text-rose-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <label class="flex flex-col gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Payment notes</span>
                    <textarea name="payment_notes" rows="3" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">{{ old('payment_notes', $due->payment_notes) }}</textarea>
                    @error('payment_notes')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </label>

                <label class="flex flex-col gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rejection reason</span>
                    <textarea name="rejection_reason" rows="3" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">{{ old('rejection_reason', $due->rejection_reason) }}</textarea>
                    @error('rejection_reason')
                        <span class="text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </label>
            </section>

            <footer class="flex flex-col gap-3 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">Changes save immediately and will update the student's financial overview.</p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('admin.dues.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-save-line text-base" aria-hidden="true"></i>
                        Save changes
                    </button>
                </div>
            </footer>
        </form>
    </div>
</x-layouts.admin>
