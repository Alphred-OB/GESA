@extends('layouts.admin')

@section('title', 'Pending Registrations')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Pending Registrations</h1>
            <p class="mt-2 text-sm text-slate-600">Review and approve student registration requests</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-2xl bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-100">Pending Review</p>
                    <p class="mt-2 text-4xl font-semibold">{{ $registrations->where('status', 'pending')->count() }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-4">
                    <i class="ri-time-line text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100">Approved</p>
                    <p class="mt-2 text-4xl font-semibold">{{ $registrations->where('status', 'approved')->count() }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-4">
                    <i class="ri-checkbox-circle-line text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-red-500 to-red-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Rejected</p>
                    <p class="mt-2 text-4xl font-semibold">{{ $registrations->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-4">
                    <i class="ri-close-circle-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter and Search --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="GET" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Name, email, index..."
                            class="w-full rounded-xl border-slate-300 pl-10 pr-4 py-2 text-sm focus:border-[#16136a] focus:ring-[#16136a]"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 py-2 text-sm focus:border-[#16136a] focus:ring-[#16136a]">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Program</label>
                    <select name="class" class="w-full rounded-xl border-slate-300 py-2 text-sm focus:border-[#16136a] focus:ring-[#16136a]">
                        <option value="">All Programs</option>
                        <option value="Geomatic Engineering" {{ request('class') === 'Geomatic Engineering' ? 'selected' : '' }}>Geomatic Engineering</option>
                        <option value="Land Administration" {{ request('class') === 'Land Administration' ? 'selected' : '' }}>Land Administration</option>
                        <option value="Spatial Planning" {{ request('class') === 'Spatial Planning' ? 'selected' : '' }}>Spatial Planning</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-[#16136a] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#18188a]">
                        <i class="ri-filter-3-line mr-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4">
            <div class="flex items-center gap-3">
                <i class="ri-checkbox-circle-fill text-2xl text-green-600"></i>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4">
            <div class="flex items-center gap-3">
                <i class="ri-error-warning-fill text-2xl text-red-600"></i>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Registrations Table --}}
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($registrations as $registration)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#16136a] text-white font-semibold text-sm">
                                        {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $registration->first_name }} {{ $registration->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $registration->index_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">{{ $registration->class }}</p>
                                <p class="text-xs text-slate-500">Year {{ $registration->year }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-900">{{ $registration->email }}</p>
                                @if($registration->phone_number)
                                    <p class="text-xs text-slate-500">{{ $registration->phone_number }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-900">{{ $registration->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-slate-500">{{ $registration->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a 
                                    href="{{ route('admin.pending-registrations.show', $registration) }}" 
                                    class="inline-flex items-center gap-2 rounded-lg bg-[#16136a] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#18188a]"
                                >
                                    <i class="ri-eye-line"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="ri-inbox-line text-5xl text-slate-300 mb-3"></i>
                                <p class="text-slate-500">No pending registrations found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($registrations->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
