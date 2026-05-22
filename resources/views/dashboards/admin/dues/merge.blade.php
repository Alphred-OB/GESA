@php($title = 'Merge Dues')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-3xl space-y-6 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dues.maintenance.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
                    <x-heroicon-o-arrow-left class="size-5" />
                </a>
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">
                        <x-heroicon-o-arrows-pointing-in class="size-5" aria-hidden="true" />
                        Merge Dues
                    </p>
                    <h1 class="text-xl font-semibold text-[#16136a] mt-1">Merge "{{ $sourceDesc }}" Into Another Due</h1>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <x-heroicon-s-exclamation-triangle class="size-6 text-red-600" />
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Source Due Info --}}
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h3 class="font-semibold text-amber-900 mb-3">Source Due (Will Be Deleted)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-xs font-semibold uppercase text-amber-600">Description</p>
                    <p class="font-semibold text-amber-900">{{ $sourceDesc }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-amber-600">Academic Year</p>
                    <p class="font-semibold text-amber-900">{{ $sourceYear }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-amber-600">Total Students</p>
                    <p class="font-semibold text-amber-900">{{ $sourceStats->total ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-amber-600">Paid / Pending / Owing</p>
                    <p class="font-semibold">
                        <span class="text-emerald-700">{{ $sourceStats->paid ?? 0 }}</span> /
                        <span class="text-amber-700">{{ $sourceStats->pending ?? 0 }}</span> /
                        <span class="text-rose-700">{{ $sourceStats->owing ?? 0 }}</span>
                    </p>
                </div>
            </div>
            @if (($sourceStats->paid ?? 0) > 0 || ($sourceStats->pending ?? 0) > 0)
                <div class="mt-4 p-3 rounded-xl bg-amber-100 text-amber-800 text-sm">
                    <x-heroicon-o-information-circle class="mr-1 size-5" />
                    <strong>{{ ($sourceStats->paid ?? 0) + ($sourceStats->pending ?? 0) }} payment(s)</strong> will be transferred to the target due.
                    Collected: <strong>GHS {{ number_format($sourceStats->collected ?? 0, 2) }}</strong>
                </div>
            @endif
        </div>

        {{-- Target Selection --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
            <h3 class="font-semibold text-slate-800 mb-4">Select Target Due (Will Receive Payments)</h3>
            
            @if ($potentialTargets->isEmpty())
                <div class="text-center py-8 text-slate-500">
                    <x-heroicon-o-inbox class="text-4xl text-slate-300 size-5" />
                    <p class="mt-2">No other dues found in {{ $sourceYear }} to merge into.</p>
                    <a href="{{ route('admin.dues.maintenance.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                        <x-heroicon-o-arrow-left class="size-5" />
                        Back to Maintenance
                    </a>
                </div>
            @else
                <form action="{{ route('admin.dues.maintenance.merge') }}" method="POST" onsubmit="return confirm('⚠️ MERGE DUES\n\nThis will:\n1. Transfer {{ $sourceStats->paid ?? 0 }} paid + {{ $sourceStats->pending ?? 0 }} pending payments to the target\n2. Delete all {{ $sourceStats->total ?? 0 }} instances of \'{{ $sourceDesc }}\'\n\nThis action CANNOT be undone!\n\nAre you absolutely sure?')">
                    @csrf
                    <input type="hidden" name="source_academic_year" value="{{ $sourceYear }}">
                    <input type="hidden" name="source_description" value="{{ $sourceDesc }}">

                    <div class="space-y-3">
                        @foreach ($potentialTargets as $target)
                            <label class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer">
                                <input type="radio" name="target_description" value="{{ $target->description }}" required class="h-5 w-5 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <input type="hidden" name="target_academic_year" value="{{ $target->academic_year }}">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-800">{{ $target->description }}</p>
                                    <p class="text-xs text-slate-500">{{ $target->student_count }} students assigned · {{ $target->academic_year }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                        {{ $target->student_count }} students
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('admin.dues.maintenance.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                            <x-heroicon-o-x-mark class="size-5" />
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700">
                            <x-heroicon-o-arrows-pointing-in class="size-5" />
                            Merge Into Selected Due
                        </button>
                    </div>
                </form>
            @endif
        </div>

        {{-- What Will Happen --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 text-sm text-blue-800">
            <h4 class="font-semibold mb-2"><x-heroicon-o-information-circle class="mr-1 size-5" /> What happens when you merge:</h4>
            <ul class="list-disc ml-5 space-y-1">
                <li><strong>Paid students:</strong> Their payment info is transferred to the target due</li>
                <li><strong>Pending students:</strong> Their pending status is transferred to the target due</li>
                <li><strong>Owing students:</strong> If they don't have the target due, it's created as owing</li>
                <li><strong>Source due:</strong> Completely deleted after transfer</li>
                <li><strong>Students with both dues:</strong> Only payment info is transferred (no duplicate dues)</li>
            </ul>
        </div>
    </div>
</x-layouts.admin>
