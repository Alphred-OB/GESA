@php($title = 'Default Dues Configuration')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                    <i class="ri-settings-4-line text-2xl"></i>
                </div>
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-700">
                        <i class="ri-database-2-line text-base" aria-hidden="true"></i>
                        Config Manager
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] mt-1">Default Dues Configuration</h1>
                </div>
            </div>
            <p class="text-sm text-slate-600">
                Manage the default amounts for each class/year combination. <strong>These values are used when new students register.</strong>
                After updating, you can resync existing student dues to match these configurations.
            </p>
        </header>

        {{-- Flash Messages --}}
        @if (session('status'))
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-checkbox-circle-fill text-xl text-green-600"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="ri-error-warning-fill text-xl text-red-600"></i>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Back to Maintenance --}}
            <a href="{{ route('admin.dues.maintenance.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:bg-slate-50 transition flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <i class="ri-arrow-left-line text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Back to Maintenance</h3>
                    <p class="text-sm text-slate-600">Return to the maintenance dashboard</p>
                </div>
            </a>

            {{-- Resync All --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <form action="{{ route('admin.dues.maintenance.resync-from-config') }}" method="POST" 
                    onsubmit="return confirm('This will update ALL owing dues to match the configuration. Continue?')">
                    @csrf
                    <input type="hidden" name="only_owing" value="1">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-200 text-emerald-700">
                            <i class="ri-refresh-line text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-emerald-900">Resync All Owing Dues</h3>
                            <p class="text-sm text-emerald-700">Update all owing dues to match these configs</p>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="ri-refresh-line"></i>
                            Resync
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Configs by Description --}}
        @forelse ($descriptions as $description)
            <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <i class="ri-money-dollar-circle-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">{{ $description }}</h2>
                            <p class="text-xs text-slate-500">Default amounts per class/year</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.dues.maintenance.resync-from-config') }}" method="POST" class="inline"
                        onsubmit="return confirm('Resync all owing dues for {{ $description }} to match config?')">
                        @csrf
                        <input type="hidden" name="description" value="{{ $description }}">
                        <input type="hidden" name="only_owing" value="1">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                            <i class="ri-refresh-line"></i>
                            Resync This Due
                        </button>
                    </form>
                </div>

                <form action="{{ route('admin.dues.maintenance.config.bulk-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="description" value="{{ $description }}">
                    
                    <div class="overflow-x-auto -mx-6 sm:mx-0">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Class / Program</th>
                                    @foreach ($years as $year)
                                        <th class="px-4 py-3 text-center">Year {{ $year }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($classes as $class)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-medium text-slate-700">
                                            {{ $class }}
                                        </td>
                                        @foreach ($years as $year)
                                            @php
                                                $currentAmount = $configByDescription[$description][$class][$year] ?? null;
                                                $studentCount = $studentCounts->get($class . '|' . $year)?->count ?? 0;
                                            @endphp
                                            <td class="px-4 py-2 text-center">
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">GHS</span>
                                                    <input type="number" 
                                                        name="amounts[{{ $class }}][{{ $year }}]" 
                                                        value="{{ $currentAmount }}"
                                                        step="0.01"
                                                        min="0"
                                                        placeholder="—"
                                                        class="w-full rounded-lg border-slate-200 pl-10 pr-3 py-2 text-sm text-center focus:border-blue-500 focus:ring-blue-500
                                                            {{ $currentAmount === null ? 'bg-amber-50 border-amber-200' : '' }}">
                                                </div>
                                                @if ($studentCount > 0)
                                                    <p class="text-xs text-slate-400 mt-1">{{ $studentCount }} student{{ $studentCount > 1 ? 's' : '' }}</p>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-slate-100">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            <i class="ri-save-line"></i>
                            Save Configuration for {{ $description }}
                        </button>
                    </div>
                </form>
            </section>
        @empty
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center">
                <i class="ri-information-line text-4xl text-amber-500 mb-2"></i>
                <h3 class="font-semibold text-amber-900">No Configurations Found</h3>
                <p class="text-sm text-amber-700 mt-1">
                    Default configurations are created when you create a new due from the Dues Management page.
                    Once created, they will appear here for editing.
                </p>
                <a href="{{ route('admin.dues.create') }}" class="inline-flex items-center gap-2 mt-4 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
                    <i class="ri-add-line"></i>
                    Create a New Due
                </a>
            </div>
        @endforelse

        {{-- Info Box --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <div class="flex items-start gap-3">
                <i class="ri-information-line text-xl text-blue-600 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">How This Works</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li><strong>When a new student registers:</strong> The system looks up their class and year in this configuration to determine the correct due amount.</li>
                        <li><strong>Yellow highlighted cells:</strong> No configuration set yet. New students in this class/year won't receive this due automatically.</li>
                        <li><strong>Resync:</strong> Updates existing <em>owing</em> dues to match the current configuration. Paid dues are not affected.</li>
                        <li><strong>After editing:</strong> Changes apply immediately to new registrations. Use "Resync" to fix existing incorrect amounts.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Back to Dues --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Maintenance
            </a>
        </div>
    </div>
</x-layouts.admin>
