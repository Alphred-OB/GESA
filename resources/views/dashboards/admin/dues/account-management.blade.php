@php($title = 'Account Management')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-5 py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <header class="space-y-3 rounded-3xl border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i class="ri-user-settings-line text-2xl"></i>
                </div>
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-rose-700">
                        <i class="ri-shield-user-line text-base" aria-hidden="true"></i>
                        Account Management
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] mt-1">Manage Student Accounts</h1>
                </div>
            </div>
            <p class="text-sm text-slate-600">
                Search for accounts, delete stuck registrations, force approve pending accounts, and manage user accounts.
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

        {{-- Stats Overview --}}
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                        <i class="ri-time-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['total_pending'] }}</p>
                        <p class="text-xs text-slate-500">Total Pending</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <i class="ri-mail-close-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['pending_unverified'] }}</p>
                        <p class="text-xs text-slate-500">Unverified Email</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="ri-mail-check-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['pending_verified'] }}</p>
                        <p class="text-xs text-slate-500">Awaiting Approval</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <i class="ri-user-line text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-slate-500">Active Students</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Section --}}
        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                    <i class="ri-search-2-line text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Search Accounts</h2>
                    <p class="text-xs text-slate-500">Find students by name, email, username, or index number</p>
                </div>
            </div>

            <form action="{{ route('admin.dues.maintenance.accounts') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400 mb-1 block">Search Query</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, username, or index number..." 
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-purple-500">
                </div>
                <div class="w-full sm:w-48">
                    <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400 mb-1 block">Search In</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Records</option>
                        <option value="pending" {{ $type === 'pending' ? 'selected' : '' }}>Pending Registrations</option>
                        <option value="users" {{ $type === 'users' ? 'selected' : '' }}>Active Users</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-700">
                    <i class="ri-search-line"></i>
                    Search
                </button>
            </form>
        </section>

        {{-- Search Results --}}
        @if ($search)
            {{-- Pending Registrations Results --}}
            @if ($pendingRegistrations->count() > 0)
                <section class="space-y-4 rounded-3xl border border-yellow-200 bg-white p-6 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                            <i class="ri-time-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Pending Registrations</h2>
                            <p class="text-xs text-slate-500">Found {{ $pendingRegistrations->count() }} result(s)</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-6 sm:mx-0">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email / Username</th>
                                    <th class="px-4 py-3">Class / Year</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3">Created</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($pendingRegistrations as $reg)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-800">{{ $reg->first_name }} {{ $reg->last_name }}</div>
                                            <div class="text-xs text-slate-400">ID: {{ $reg->id }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-slate-700">{{ $reg->email }}</div>
                                            <div class="text-xs text-slate-400">@{{ $reg->username }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $reg->class ?? 'N/A' }} · Year {{ $reg->year ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col gap-1">
                                                @if ($reg->status === 'pending')
                                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif ($reg->status === 'approved')
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                                
                                                @if ($reg->email_verified_at)
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                        <i class="ri-mail-check-line mr-1"></i> Verified
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                                        <i class="ri-mail-close-line mr-1"></i> Unverified
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $reg->created_at->format('M d, Y') }}
                                            <div class="text-xs text-slate-400">{{ $reg->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                                {{-- Force Verify Email (if not verified) --}}
                                                @if (!$reg->email_verified_at)
                                                    <form action="{{ route('admin.dues.maintenance.accounts.force-verify', $reg->id) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Force verify email for {{ $reg->first_name }} {{ $reg->last_name }}?')">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-200">
                                                            <i class="ri-mail-check-line"></i>
                                                            Verify Email
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Force Approve (if not approved) --}}
                                                @if ($reg->status !== 'approved')
                                                    <form action="{{ route('admin.dues.maintenance.accounts.force-approve', $reg->id) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Force approve {{ $reg->first_name }} {{ $reg->last_name }}? This will create their user account and sync dues.')">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700 transition hover:bg-green-200">
                                                            <i class="ri-checkbox-circle-line"></i>
                                                            Force Approve
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Delete --}}
                                                <form action="{{ route('admin.dues.maintenance.accounts.delete-pending', $reg->id) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('⚠️ DELETE pending registration for {{ $reg->first_name }} {{ $reg->last_name }}?\n\nThis cannot be undone!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                        <i class="ri-delete-bin-line"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

            {{-- Users Results --}}
            @if ($users->count() > 0)
                <section class="space-y-4 rounded-3xl border border-green-200 bg-white p-6 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-600">
                            <i class="ri-user-line text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800">Active User Accounts</h2>
                            <p class="text-xs text-slate-500">Found {{ $users->count() }} result(s)</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-6 sm:mx-0">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email / Username</th>
                                    <th class="px-4 py-3">Class / Year</th>
                                    <th class="px-4 py-3">Index Number</th>
                                    <th class="px-4 py-3">Joined</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-800">{{ $user->fullname ?? 'No Name' }}</div>
                                            <div class="text-xs text-slate-400">ID: {{ $user->user_id }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-slate-700">{{ $user->email }}</div>
                                            <div class="text-xs text-slate-400">@{{ $user->username }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $user->class ?? 'N/A' }} · Year {{ $user->year ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $user->index_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- View Profile --}}
                                                <a href="{{ route('admin.students.show', $user) }}" 
                                                    class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                                                    <i class="ri-eye-line"></i>
                                                    View
                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('admin.dues.maintenance.accounts.delete-user', $user->user_id) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('⚠️ DELETE user account for {{ $user->fullname ?? $user->username }}?\n\nThis will also delete ALL their dues records and cannot be undone!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">
                                                        <i class="ri-delete-bin-line"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

            {{-- No Results --}}
            @if ($pendingRegistrations->count() === 0 && $users->count() === 0)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
                    <i class="ri-search-line text-4xl text-slate-300"></i>
                    <p class="mt-3 font-semibold text-slate-600">No results found</p>
                    <p class="text-sm text-slate-500">Try a different search query</p>
                </div>
            @endif
        @else
            {{-- No Search Prompt --}}
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-12 text-center">
                <i class="ri-user-search-line text-5xl text-slate-300"></i>
                <p class="mt-4 font-semibold text-slate-600">Search for an account</p>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                    Enter a name, email, username, or index number to find pending registrations or active user accounts.
                </p>
            </div>
        @endif

        {{-- Back to Maintenance --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.dues.maintenance.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                <i class="ri-arrow-left-line"></i>
                Back to Dues Maintenance
            </a>
        </div>
    </div>
</x-layouts.admin>
