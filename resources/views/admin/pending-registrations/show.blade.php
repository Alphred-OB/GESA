@extends('layouts.admin')

@section('title', 'Review Registration')

@section('content')
<div class="space-y-8">
    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pending-registrations.index') }}" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:bg-slate-50">
                <x-heroicon-o-arrow-left class="size-6" />
            </a>
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Review Registration</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $registration->first_name }} {{ $registration->last_name }}</p>
            </div>
        </div>

        {{-- Status Badge --}}
        @if($registration->status === 'pending')
            <span class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-800">
                <x-heroicon-o-clock class="size-5" /> Pending Review
            </span>
        @elseif($registration->status === 'approved')
            <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-800">
                <x-heroicon-o-check-circle class="size-5" /> Approved
            </span>
        @else
            <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-800">
                <x-heroicon-o-x-circle class="size-5" /> Rejected
            </span>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Student Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Details --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-user class="text-[#16136a] size-5" />
                    Personal Information
                </h2>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">First Name</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->first_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Last Name</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Username</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->username }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Index Number</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->index_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-users class="text-[#16136a] size-5" />
                    Contact Information
                </h2>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Email Address</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Phone Number</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->phone_number ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            {{-- Academic Information --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-academic-cap class="text-[#16136a] size-5" />
                    Academic Information
                </h2>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Program</label>
                        <p class="text-sm font-semibold text-slate-900">{{ $registration->class }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Year</label>
                        <p class="text-sm font-semibold text-slate-900">Year {{ $registration->year }}</p>
                    </div>
                </div>
            </div>

            {{-- Reason for Registration --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-question-mark-circle class="text-[#16136a] size-5" />
                    Why can't access student email?
                </h2>
                
                <div class="rounded-lg bg-slate-50 border border-slate-200 p-4">
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $registration->reason }}</p>
                </div>
            </div>

            {{-- Student ID Upload --}}
            @if($registration->student_id_path)
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-photo class="text-[#16136a] size-5" />
                        Student ID Card
                    </h2>
                    
                    <div class="rounded-lg overflow-hidden border border-slate-200">
                        <img 
                            src="{{ asset('storage/' . $registration->student_id_path) }}" 
                            alt="Student ID" 
                            class="w-full h-auto"
                        />
                    </div>
                    
                    <a 
                        href="{{ asset('storage/' . $registration->student_id_path) }}" 
                        target="_blank" 
                        class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#16136a] hover:underline"
                    >
                        <x-heroicon-o-arrow-top-right-on-square class="size-5" />
                        View Full Size
                    </a>
                </div>
            @endif

            {{-- Admin Notes (if reviewed) --}}
            @if($registration->admin_notes && $registration->status !== 'pending')
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-document-text class="text-[#16136a] size-5" />
                        Admin Notes
                    </h2>
                    
                    <div class="rounded-lg bg-slate-50 border border-slate-200 p-4">
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $registration->admin_notes }}</p>
                    </div>

                    @if($registration->reviewed_at)
                        <p class="mt-3 text-xs text-slate-500">
                            Reviewed on {{ $registration->reviewed_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Actions Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Info Card --}}
            <div class="rounded-2xl bg-gradient-to-br from-[#16136a] to-[#1a1a8a] p-6 text-white shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                        <x-heroicon-o-calendar class="size-7" />
                    </div>
                    <div>
                        <p class="text-xs font-medium text-white/80">Submitted</p>
                        <p class="text-sm font-semibold">{{ $registration->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <p class="text-xs text-white/60">{{ $registration->created_at->diffForHumans() }}</p>
            </div>

            {{-- Action Buttons (Only for pending) --}}
            @if($registration->status === 'pending')
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-4">
                    <h3 class="font-semibold text-slate-900">Actions</h3>

                    {{-- Approve Form --}}
                    <form method="POST" action="{{ route('admin.pending-registrations.approve', $registration) }}" class="space-y-3">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-2">Notes (Optional)</label>
                            <textarea 
                                name="notes" 
                                rows="3" 
                                placeholder="Add any notes for approval..."
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-green-500 focus:ring-green-500"
                            ></textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-700"
                        >
                            <x-heroicon-o-check-circle class="size-5" />
                            Approve Registration
                        </button>
                    </form>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-2 text-slate-500">or</span>
                        </div>
                    </div>

                    {{-- Reject Form --}}
                    <form method="POST" action="{{ route('admin.pending-registrations.reject', $registration) }}" class="space-y-3">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-2">Reason for Rejection</label>
                            <textarea 
                                name="notes" 
                                rows="3" 
                                required
                                placeholder="Explain why this registration is being rejected..."
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-red-500 focus:ring-red-500"
                            ></textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to reject this registration?')"
                        >
                            <x-heroicon-o-x-circle class="size-5" />
                            Reject Registration
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
