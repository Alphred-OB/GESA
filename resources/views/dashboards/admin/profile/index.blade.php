@php($title = 'Account Settings')

<x-layouts.admin :title="$title">
    <div x-data="{ 
        activeTab: 'profile',
        loading: true 
    }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        
        {{-- Loading Skeleton --}}
        <div x-show="loading" class="space-y-10 animate-pulse">
            <div class="h-48 w-full rounded-[2.5rem] bg-slate-100"></div>
            <div class="grid gap-8 lg:grid-cols-4">
                <div class="space-y-4">
                    <div class="h-12 rounded-2xl bg-slate-100"></div>
                    <div class="h-12 rounded-2xl bg-slate-50"></div>
                    <div class="h-12 rounded-2xl bg-slate-50"></div>
                </div>
                <div class="lg:col-span-3 h-96 rounded-[2.5rem] bg-slate-50"></div>
            </div>
        </div>

        <div x-show="!loading" x-cloak class="space-y-10">
            {{-- Premium Header --}}
            <header class="relative overflow-hidden rounded-[2.5rem] bg-[#16136a] p-8 text-white shadow-2xl shadow-[#16136a]/20 sm:p-12">
                <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-4">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.3em] text-white/70">
                            <i class="ri-settings-3-line"></i> Control Center
                        </span>
                        <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">Account Settings</h1>
                        <p class="max-w-xl text-sm font-semibold text-white/50 leading-relaxed">
                            Configure your administrative profile, provision workspace access, and manage critical system snapshots.
                        </p>
                    </div>
                    <div class="flex items-center gap-6 rounded-[2rem] border border-white/10 bg-white/5 p-6 backdrop-blur-md">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/10 text-2xl font-semibold">
                            {{ substr($admin->fullname ?? $admin->username, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-white/40">Logged in as</p>
                            <p class="text-lg font-semibold">{{ $admin->fullname ?? $admin->username }}</p>
                            <p class="text-xs font-semibold text-white/40">{{ $admin->email }}</p>
                        </div>
                    </div>
                </div>
                <i class="ri-shield-user-line absolute -right-20 -bottom-20 text-[20rem] text-white/5 rotate-12"></i>
            </header>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-10 lg:grid-cols-4">
                {{-- Sidebar Navigation --}}
                <aside class="space-y-2">
                    <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-2xl px-6 py-4 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-user-settings-line text-lg"></i>
                        My Profile
                    </button>
                    <button @click="activeTab = 'invites'" :class="activeTab === 'invites' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-2xl px-6 py-4 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-user-add-line text-lg"></i>
                        Team Access
                    </button>
                    <button @click="activeTab = 'snapshots'" :class="activeTab === 'snapshots' ? 'bg-[#16136a] text-white shadow-xl shadow-[#16136a]/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'" 
                        class="flex w-full items-center gap-4 rounded-2xl px-6 py-4 text-xs font-semibold uppercase tracking-widest transition-all">
                        <i class="ri-database-2-line text-lg"></i>
                        System Snapshots
                    </button>
                </aside>

                {{-- Tab Content --}}
                <main class="lg:col-span-3">
                    {{-- Profile Section --}}
                    <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40 lg:p-12">
                            <h2 class="text-xl font-semibold text-[#16136a]">Profile Configuration</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 italic">Update your administrative credentials and security</p>

                            <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-10 space-y-8">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                                        <input type="text" name="fullname" value="{{ old('fullname', $admin->fullname) }}" 
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Username</label>
                                        <input type="text" name="username" value="{{ old('username', $admin->username) }}" 
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" 
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Phone Number</label>
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $admin->phone_number) }}" 
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                </div>

                                <div class="rounded-3xl bg-slate-50/50 p-8 border border-slate-100">
                                    <h3 class="text-sm font-semibold text-[#16136a] uppercase tracking-widest">Update Password</h3>
                                    <p class="mt-1 text-[11px] font-semibold text-slate-400">Leave blank to maintain current credentials</p>
                                    
                                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">New Password</label>
                                            <input type="password" name="password" 
                                                class="h-12 w-full rounded-2xl border-none bg-white px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:ring-[#16136a]/10">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Confirm Password</label>
                                            <input type="password" name="password_confirmation" 
                                                class="h-12 w-full rounded-2xl border-none bg-white px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:ring-[#16136a]/10">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="flex h-14 items-center gap-3 rounded-2xl bg-[#16136a] px-10 text-[10px] font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                        <i class="ri-save-3-line text-lg"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>

                    {{-- Invites Section --}}
                    <div x-show="activeTab === 'invites'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40 lg:p-12">
                            <h2 class="text-xl font-semibold text-[#16136a]">Team Management</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 italic">Provision new administrator accounts for the GESA portal</p>

                            <form method="POST" action="{{ route('admin.profile.admins.store') }}" class="mt-10 space-y-8">
                                @csrf
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                                        <input type="text" name="fullname" value="{{ old('fullname') }}" required
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Username</label>
                                        <input type="text" name="username" value="{{ old('username') }}" required
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div class="space-y-2 lg:col-span-1">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Phone Number</label>
                                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" 
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Workspace Position</label>
                                        <select name="admin_role" required class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                            <option value="" disabled selected>Select position</option>
                                            <option value="president">President</option>
                                            <option value="financial_secretary">Financial Secretary</option>
                                            <option value="general_secretary">General Secretary</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Temporary Password</label>
                                        <input type="password" name="password" required
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" required
                                            class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="flex h-14 items-center gap-3 rounded-2xl bg-[#16136a] px-10 text-[10px] font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                        <i class="ri-user-add-line text-lg"></i> Provision Account
                                    </button>
                                </div>
                            </form>

                            {{-- Active Admins List --}}
                            @if ($others->isNotEmpty())
                                <div class="mt-16 space-y-6">
                                    <h3 class="text-xs font-semibold uppercase tracking-[0.3em] text-[#16136a]">Active Workspace Team</h3>
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        @foreach ($others as $other)
                                            <div class="flex items-center gap-4 rounded-3xl border border-slate-100 bg-slate-50/50 p-4 transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/50">
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white font-semibold text-[#16136a] shadow-sm">
                                                    {{ substr($other->fullname ?? $other->username, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $other->fullname ?? $other->username }}</p>
                                                    <p class="truncate text-[10px] font-semibold text-slate-400 uppercase tracking-tight">{{ $other->email }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </section>
                    </div>

                    {{-- Snapshots Section --}}
                    <div x-show="activeTab === 'snapshots'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-200/40 lg:p-12">
                            <h2 class="text-xl font-semibold text-[#16136a]">System Snapshots</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-400 italic">Generate or download point-in-time workspace backups</p>

                            <form method="POST" action="{{ route('admin.profile.snapshots.store') }}" class="mt-10 grid gap-6 lg:grid-cols-3 lg:items-end">
                                @csrf
                                <div class="space-y-2 lg:col-span-1">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Snapshot Context</label>
                                    <input type="text" name="notes" placeholder="e.g. Pre-deployment backup" 
                                        class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 ml-1">Capture Type</label>
                                    <select name="type" class="h-12 w-full rounded-2xl border-none bg-slate-50 px-4 text-xs font-semibold text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                        <option value="system">System Overview (JSON)</option>
                                        <option value="database">Database Structure (SQL)</option>
                                    </select>
                                </div>
                                <button type="submit" class="flex h-12 w-full items-center justify-center gap-3 rounded-2xl bg-[#16136a] text-[10px] font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95">
                                    <i class="ri-refresh-line"></i> Generate Snapshot
                                </button>
                            </form>

                            <div class="mt-16 overflow-hidden rounded-[2rem] border border-slate-100 bg-slate-50/30">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-slate-50/50">
                                            <th class="px-6 py-4 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Fingerprint</th>
                                            <th class="px-6 py-4 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Timestamp</th>
                                            <th class="px-6 py-4 text-right text-[10px] font-semibold uppercase tracking-widest text-slate-400">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse ($snapshots as $snap)
                                            <tr class="transition-colors hover:bg-white">
                                                <td class="px-6 py-5">
                                                    <p class="text-sm font-semibold text-slate-800">{{ $snap['filename'] }}</p>
                                                    <p class="text-[10px] font-semibold text-slate-400 uppercase">{{ $snap['content']['notes'] ?? 'System Backup' }}</p>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <p class="text-xs font-semibold text-slate-600">{{ $snap['last_modified']?->format('M j, Y · g:i A') }}</p>
                                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-tighter">{{ $snap['last_modified']?->diffForHumans() }}</p>
                                                </td>
                                                <td class="px-6 py-5 text-right">
                                                    <a href="{{ route('admin.profile.snapshots.download', base64_encode($snap['path'])) }}" 
                                                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-white px-4 text-[9px] font-semibold uppercase tracking-widest text-[#16136a] shadow-sm ring-1 ring-slate-200 transition-all hover:bg-[#16136a] hover:text-white hover:ring-transparent">
                                                        <i class="ri-download-2-line"></i> Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-10 text-center italic text-slate-400 text-xs">No system fingerprints found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-layouts.admin>
