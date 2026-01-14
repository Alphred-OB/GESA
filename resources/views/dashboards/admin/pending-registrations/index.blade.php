@php($title = 'Pending Registrations')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-5 py-10 sm:px-6 lg:px-8">
        <div class="space-y-10">
            {{-- Header --}}
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 text-center shadow-lg shadow-[#16136a]/5 sm:text-left md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-user-add-line text-base" aria-hidden="true"></i>
                        Pending Registrations
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Review Registration Requests</h1>
                    <p class="text-sm text-slate-600">Review and approve student registration requests from freshers and students without email access.</p>
                </div>
            </header>

            {{-- Main Content Section --}}
            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                {{-- Stats Cards --}}
                {{-- Stats Cards --}}
                <div class="grid gap-4 md:grid-cols-3">
                    <article class="rounded-2xl border border-yellow-200 bg-yellow-50/30 p-5 transition hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-yellow-600">Pending</p>
                                <p class="mt-2 text-3xl font-bold text-slate-800">
                                    {{ \App\Models\PendingRegistration::where('status', 'pending')->count() }}
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
                                <i class="ri-time-line text-2xl"></i>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-green-200 bg-green-50/30 p-5 transition hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-green-600">Approved</p>
                                <p class="mt-2 text-3xl font-bold text-slate-800">
                                    {{ \App\Models\PendingRegistration::where('status', 'approved')->count() }}
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
                                <i class="ri-checkbox-circle-line text-2xl"></i>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-red-200 bg-red-50/30 p-5 transition hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-red-600">Rejected</p>
                                <p class="mt-2 text-3xl font-bold text-slate-800">
                                    {{ \App\Models\PendingRegistration::where('status', 'rejected')->count() }}
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                                <i class="ri-close-circle-line text-2xl"></i>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Filter and Search --}}
                <form method="GET" class="space-y-3 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 md:flex md:flex-wrap md:items-end md:gap-3 md:space-y-0">
                    <div class="flex w-full flex-col gap-2 md:w-64">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</label>
                        <div class="relative">
                            <i class="ri-search-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, index..." class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-2 md:w-52">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Status</label>
                        <div class="relative">
                            <select name="status" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex w-full flex-col gap-2 md:w-52">
                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Program</label>
                        <div class="relative">
                            <select name="class" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All Programs</option>
                                <option value="Geomatic Engineering" {{ request('class') === 'Geomatic Engineering' ? 'selected' : '' }}>Geomatic Engineering</option>
                                <option value="Land Administration" {{ request('class') === 'Land Administration' ? 'selected' : '' }}>Land Administration</option>
                                <option value="Spatial Planning" {{ request('class') === 'Spatial Planning' ? 'selected' : '' }}>Spatial Planning</option>
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex items-end md:ml-auto md:w-auto">
                        <button type="submit" class="inline-flex h-11 min-w-[140px] items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                            <i class="ri-equalizer-line text-base"></i> Apply
                        </button>
                    </div>
                </form>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="rounded-2xl bg-green-50 border border-green-200 p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-checkbox-circle-fill text-2xl text-green-600"></i>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-2xl bg-red-50 border border-red-200 p-4">
                        <div class="flex items-center gap-3">
                            <i class="ri-error-warning-fill text-2xl text-red-600"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Registrations Table --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-[13px] text-slate-600">
                            <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                                <tr>
                                    <th class="px-5 py-2.5">Student</th>
                                    <th class="px-5 py-2.5">Program</th>
                                    <th class="px-5 py-2.5">Contact</th>
                                    <th class="px-5 py-2.5">Status</th>
                                    <th class="px-5 py-2.5">Submitted</th>
                                    <th class="px-5 py-2.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($registrations as $registration)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a] text-white font-semibold text-sm">
                                                    {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900 text-[15px]">{{ $registration->first_name }} {{ $registration->last_name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $registration->index_number }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-sm font-medium text-slate-900">{{ $registration->class }}</p>
                                            <p class="text-xs text-slate-500">Year {{ $registration->year }}</p>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-sm text-slate-900">{{ $registration->email }}</p>
                                            @if($registration->phone_number)
                                                <p class="text-xs text-slate-500">{{ $registration->phone_number }}</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            @if($registration->status === 'pending')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                                    <i class="ri-time-line"></i> Pending
                                                </span>
                                            @elseif($registration->status === 'approved')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                    <i class="ri-checkbox-circle-line"></i> Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                                    <i class="ri-close-circle-line"></i> Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-sm text-slate-900">{{ $registration->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-slate-500">{{ $registration->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                <i class="ri-eye-line text-sm"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <i class="ri-inbox-line text-5xl text-slate-300"></i>
                                                <p class="font-semibold text-slate-600">No pending registrations found</p>
                                                <p>Try adjusting your filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="md:hidden">
                        <div class="grid gap-4 p-4">
                            @forelse($registrations as $registration)
                                <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a] text-white font-semibold text-sm">
                                                {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h2 class="text-base font-semibold text-slate-900">{{ $registration->first_name }} {{ $registration->last_name }}</h2>
                                                <p class="text-xs text-slate-500">{{ $registration->index_number }}</p>
                                            </div>
                                        </div>
                                        @if($registration->status === 'pending')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-semibold text-yellow-800">
                                                <i class="ri-time-line"></i> Pending
                                            </span>
                                        @elseif($registration->status === 'approved')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-800">
                                                <i class="ri-check-line"></i> Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-[11px] font-semibold text-red-800">
                                                <i class="ri-close-line"></i> Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <dl class="space-y-2 text-xs text-slate-500 border-t border-slate-100 pt-3">
                                        <div class="flex justify-between">
                                            <dt class="font-medium text-slate-600">Program</dt>
                                            <dd>{{ $registration->class }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="font-medium text-slate-600">Year</dt>
                                            <dd>Year {{ $registration->year }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="font-medium text-slate-600">Email</dt>
                                            <dd>{{ $registration->email }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="font-medium text-slate-600">Submitted</dt>
                                            <dd>{{ $registration->created_at->format('M d, Y') }}</dd>
                                        </div>
                                    </dl>

                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                            <i class="ri-eye-line text-sm"></i> Review Request
                                        </a>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                                    <i class="ri-inbox-line text-3xl text-slate-300"></i>
                                    <p class="mt-3 font-semibold text-slate-600">No pending registrations</p>
                                    <p class="text-sm text-slate-500">Adjust filters to see more</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($registrations->hasPages())
                        <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 px-5 pb-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                            <p class="text-xs text-slate-500">Page {{ $registrations->currentPage() }} of {{ $registrations->lastPage() }}</p>
                            <div class="flex justify-center sm:ml-auto sm:justify-end">
                                {{ $registrations->onEachSide(1)->links('vendor.pagination.data-limit') }}
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-layouts.admin>
