@php($title = 'Edit Student')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <header class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                    <x-heroicon-o-pencil class="size-3.5" />
                    Update Account
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Edit Student Account</h1>
                <p class="max-w-xl text-sm font-medium text-slate-500">Modify profile details, academic placement, or security credentials.</p>
            </div>
            
            <a href="{{ route('admin.students.show', $student) }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-95">
                <x-heroicon-o-arrow-left class="size-5" />
                Back to Profile
            </a>
        </header>

        {{-- Form Section --}}
        <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
            <form method="POST" action="{{ route('admin.students.update', $student) }}">
                @csrf
                @method('PUT')

                @include('dashboards.admin.students.partials.form', [
                    'student' => $student,
                    'isEdit' => true,
                    'classOptions' => $classOptions ?? [],
                    'yearOptions' => $yearOptions ?? [],
                ])

                {{-- Action Bar --}}
                <footer class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-50 pt-8 sm:flex-row">
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 transition-colors hover:text-[#16136a]">
                        <x-heroicon-o-list-bullet class="size-5" />
                        Cancel and return to list
                    </a>
                    
                    <button type="submit" class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#16136a] px-10 text-base font-semibold text-white shadow-2xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                        <x-heroicon-o-arrow-down-on-square class="size-6" />
                        Save Changes
                    </button>
                </footer>
            </form>
        </section>
    </div>
</x-layouts.admin>
