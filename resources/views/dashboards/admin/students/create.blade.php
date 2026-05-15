@php($title = 'New Student')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <header class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                    <i class="ri-user-add-line text-xs"></i>
                    Onboard Student
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Create Student Account</h1>
                <p class="max-w-xl text-sm font-medium text-slate-500">Provide personal details and academic placement to set up a new student account.</p>
            </div>
            
            <a href="{{ route('admin.students.index') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-95">
                <i class="ri-arrow-left-line text-lg"></i>
                Back to Directory
            </a>
        </header>

        {{-- Form Section --}}
        <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40">
            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                @include('dashboards.admin.students.partials.form', [
                    'student' => $student,
                    'isEdit' => false,
                    'classOptions' => $classOptions ?? [],
                    'yearOptions' => $yearOptions ?? [],
                ])

                {{-- Action Bar --}}
                <footer class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-50 pt-8 sm:flex-row">
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 transition-colors hover:text-[#16136a]">
                        <i class="ri-list-unordered"></i>
                        Cancel and return to list
                    </a>
                    
                    <button type="submit" class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl bg-[#16136a] px-10 text-base font-semibold text-white shadow-2xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                        <i class="ri-user-add-line text-xl"></i>
                        Create Account
                    </button>
                </footer>
            </form>
        </section>
    </div>
</x-layouts.admin>
