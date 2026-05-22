@php($title = 'Pending Registrations')

<x-layouts.admin :title="$title">
    <div 
        x-data="{ 
            selected: [], 
            allSelected: false,
            registrations: {{ $registrations->map(fn($r) => $r->id)->toJson() }},
            toggleAll() {
                if (this.allSelected) {
                    this.selected = [];
                    this.allSelected = false;
                } else {
                    this.selected = [...this.registrations];
                    this.allSelected = true;
                }
            },
            toggle(id) {
                if (this.selected.includes(id)) {
                    this.selected = this.selected.filter(i => i !== id);
                } else {
                    this.selected.push(id);
                }
                this.allSelected = this.selected.length === this.registrations.length && this.registrations.length > 0;
            },
            submitBulk(action) {
                if (!this.selected.length) return;
                
                let confirmMsg = '';
                let method = 'POST';
                let route = '';

                if (action === 'approve') {
                    confirmMsg = `Approve ${this.selected.length} selected registrations?`;
                    route = '{{ route("admin.pending-registrations.bulk-approve") }}';
                } else if (action === 'reject') {
                    confirmMsg = `Reject ${this.selected.length} selected registrations?`;
                    route = '{{ route("admin.pending-registrations.bulk-reject") }}';
                } else if (action === 'delete') {
                    confirmMsg = `PERMANENTLY DELETE ${this.selected.length} selected registrations?`;
                    route = '{{ route("admin.pending-registrations.bulk-delete") }}';
                    method = 'DELETE';
                }

                if (confirm(confirmMsg)) {
                    const form = document.getElementById('bulk-action-form');
                    form.action = route;
                    document.getElementById('bulk-method').value = method;
                    form.submit();
                }
            }
        }"
        class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
    >
        <div class="space-y-8">
            {{-- Header & Stats --}}
            <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
                <header class="lg:col-span-5 space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                        <x-heroicon-o-user-plus class="size-3.5" />
                        Registration Queue
                    </div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Review Requests</h1>
                    <p class="max-w-md text-sm font-medium text-slate-500">Manage student accounts waiting for administrative approval and verification.</p>
                </header>

                <div class="lg:col-span-7 grid grid-cols-3 gap-4">
                    <div class="rounded-3xl border border-amber-100 bg-amber-50/50 p-4 shadow-sm transition-all hover:shadow-md">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-amber-600 mb-1">Waiting</p>
                        <p class="text-2xl font-semibold text-slate-900">{{ \App\Models\PendingRegistration::where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-100 bg-emerald-50/50 p-4 shadow-sm transition-all hover:shadow-md">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-600 mb-1">Approved</p>
                        <p class="text-2xl font-semibold text-slate-900">{{ \App\Models\PendingRegistration::where('status', 'approved')->count() }}</p>
                    </div>
                    <div class="rounded-3xl border border-rose-100 bg-rose-50/50 p-4 shadow-sm transition-all hover:shadow-md">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-rose-600 mb-1">Rejected</p>
                        <p class="text-2xl font-semibold text-slate-900">{{ \App\Models\PendingRegistration::where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Bulk Action Panel --}}
            <div 
                x-show="selected.length > 0"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-[2rem] border border-[#16136a]/20 bg-[#16136a] p-4 flex flex-wrap items-center justify-between gap-4 shadow-2xl shadow-[#16136a]/20"
                x-cloak
            >
                <div class="flex items-center gap-4 px-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-[#16136a] shadow-lg" x-text="selected.length"></div>
                    <div>
                        <p class="text-sm font-semibold text-white">Batch Management</p>
                        <p class="text-[10px] text-white/50 font-semibold uppercase tracking-widest">Apply actions to selected</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button @click="submitBulk('approve')" class="inline-flex h-11 items-center gap-2 rounded-xl bg-emerald-500 px-5 text-xs font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <x-heroicon-o-check class="size-5" /> Approve Selected
                    </button>
                    <button @click="submitBulk('reject')" class="inline-flex h-11 items-center gap-2 rounded-xl bg-amber-500 px-5 text-xs font-semibold text-white shadow-lg shadow-amber-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <x-heroicon-o-x-mark class="size-5" /> Reject Selected
                    </button>
                    <button @click="submitBulk('delete')" class="inline-flex h-11 items-center gap-2 rounded-xl bg-rose-500 px-5 text-xs font-semibold text-white shadow-lg shadow-rose-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        <x-heroicon-o-trash class="size-5" /> Delete
                    </button>
                    <div class="mx-2 h-6 w-px bg-white/10"></div>
                    <button @click="selected = []; allSelected = false" class="px-4 text-xs font-semibold text-white/50 hover:text-white transition-colors">Cancel</button>
                </div>
            </div>

            <form id="bulk-action-form" method="POST" class="hidden">
                @csrf
                <input type="hidden" id="bulk-method" name="_method" value="POST">
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
            </form>

            {{-- Main Directory Card --}}
            <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-2 shadow-xl shadow-slate-200/40">
                {{-- Toolbar --}}
                <div class="flex flex-col gap-4 p-4 lg:flex-row lg:items-center lg:justify-between">
                    <form method="GET" class="flex flex-1 flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[200px]">
                            <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or reference..." class="h-12 w-full rounded-2xl border border-slate-100 bg-slate-50/50 pl-11 pr-4 text-sm font-medium outline-none transition-all focus:border-[#16136a] focus:bg-white focus:ring-4 focus:ring-[#16136a]/5">
                        </div>
                        
                        <div class="relative">
                            <select name="status" class="h-12 appearance-none rounded-2xl border border-slate-100 bg-slate-50/50 pl-5 pr-10 text-sm font-semibold text-slate-600 outline-none transition-all focus:border-[#16136a] focus:bg-white">
                                <option value="">All Status</option>
                                <option value="pending" @selected(request('status') === 'pending')>Waiting</option>
                                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                            </select>
                            <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 size-5" />
                        </div>

                        <button type="submit" class="flex h-12 items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold text-white transition-all hover:opacity-90 active:scale-95">
                            Apply Filters
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mx-4 mb-4 rounded-2xl bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-600 border border-emerald-100 flex items-center gap-3">
                        <x-heroicon-s-check-circle class="size-6" />
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-4 mb-4 rounded-2xl bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-600 border border-rose-100 flex items-center gap-3">
                        <x-heroicon-s-exclamation-triangle class="size-6" />
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table (Desktop) --}}
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead class="bg-slate-50/50 text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                            <tr>
                                <th class="w-12 px-6 py-4">
                                    <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="h-4 w-4 rounded border-slate-200 text-[#16136a] focus:ring-[#16136a]">
                                </th>
                                <th class="px-6 py-4 text-left">Student Information</th>
                                <th class="px-6 py-4 text-left">Academic Program</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Applied</th>
                                <th class="px-6 py-4 text-right">Review</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($registrations as $registration)
                                <tr class="group transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" :checked="selected.includes({{ $registration->id }})" @click="toggle({{ $registration->id }})" class="h-4 w-4 rounded border-slate-200 text-[#16136a] focus:ring-[#16136a]">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-sm font-semibold text-[#16136a] transition-colors group-hover:bg-[#16136a] group-hover:text-white">
                                                {{ strtoupper(substr($registration->first_name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $registration->first_name }} {{ $registration->last_name }}</p>
                                                <p class="truncate text-[11px] font-medium text-slate-400">{{ $registration->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-700">{{ $registration->class }}</p>
                                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Year {{ $registration->year }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($registration->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-amber-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Waiting
                                            </span>
                                        @elseif($registration->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-600">
                                                <x-heroicon-s-check-circle class="size-5" />
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-rose-600">
                                                <x-heroicon-s-x-circle class="size-5" />
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-700">{{ $registration->created_at->diffForHumans() }}</p>
                                        <p class="text-[10px] font-medium text-slate-400">{{ $registration->created_at->format('M d, Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-[#16136a] hover:text-white hover:shadow-lg hover:shadow-[#16136a]/20">
                                            <x-heroicon-o-arrow-right class="size-5" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="flex h-20 w-20 items-center justify-center rounded-[2rem] bg-slate-50 text-slate-200">
                                                <x-heroicon-o-inbox class="text-4xl size-5" />
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-slate-900">Queue is empty</p>
                                                <p class="text-sm font-medium text-slate-400">No pending registrations found.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View --}}
                <div class="grid gap-4 p-4 md:hidden">
                    @forelse($registrations as $registration)
                        <article class="relative flex flex-col gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm transition-all active:scale-[0.98]">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#16136a] text-sm font-semibold text-white">
                                        {{ strtoupper(substr($registration->first_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-900">{{ $registration->first_name }} {{ $registration->last_name }}</h3>
                                        <p class="truncate text-xs font-medium text-slate-400">{{ $registration->email }}</p>
                                    </div>
                                </div>
                                <input type="checkbox" :checked="selected.includes({{ $registration->id }})" @click.stop="toggle({{ $registration->id }})" class="h-6 w-6 rounded-full border-slate-200 text-[#16136a] focus:ring-[#16136a]">
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-t border-slate-50 pt-4">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-300">Program</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $registration->class }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-300">Status</p>
                                    @if($registration->status === 'pending')
                                        <span class="text-[10px] font-semibold uppercase text-amber-500">Waiting</span>
                                    @elseif($registration->status === 'approved')
                                        <span class="text-[10px] font-semibold uppercase text-emerald-500">Approved</span>
                                    @else
                                        <span class="text-[10px] font-semibold uppercase text-rose-500">Rejected</span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="flex h-12 items-center justify-center gap-2 rounded-2xl bg-slate-50 text-sm font-semibold text-[#16136a] transition-all hover:bg-slate-100">
                                Review Request
                                <x-heroicon-o-arrow-right class="size-5" />
                            </a>
                        </article>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-slate-50 text-slate-200">
                                <x-heroicon-o-inbox class="size-8" />
                            </div>
                            <p class="text-sm font-semibold text-slate-900">Empty Queue</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($registrations->hasPages())
                    <div class="border-t border-slate-50 p-6">
                        {{ $registrations->links('vendor.pagination.data-limit') }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    {{-- Live Notification --}}
    <div 
        x-data="liveRegistrationToast()"
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-8 right-8 z-50 w-full max-w-sm"
        style="display: none;"
    >
        <div class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white p-2 shadow-2xl shadow-blue-900/20">
            <div class="flex items-center gap-4 bg-blue-50/50 p-4 rounded-[1.5rem]">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                    <x-heroicon-o-user-plus class="size-6" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-blue-900">New Request</p>
                    <p class="truncate text-xs font-semibold text-blue-600" x-text="toastMessage"></p>
                </div>
                <button @click="refreshPage()" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm transition-all hover:bg-blue-600 hover:text-white">
                    <x-heroicon-o-arrow-path class="size-5" />
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('liveRegistrationToast', () => ({
            showToast: false,
            toastMessage: '',
            autoHideTimeout: null,
            init() {
                window.addEventListener('new-registration-arrived', (e) => {
                    const detail = e.detail;
                    const firstNew = detail.data?.[0];
                    this.toastMessage = firstNew ? `${firstNew.name} applied` : `${detail.count} new requests`;
                    this.showToast = true;
                    if (this.autoHideTimeout) clearTimeout(this.autoHideTimeout);
                    this.autoHideTimeout = setTimeout(() => this.showToast = false, 8000);
                });
            },
            refreshPage() { window.location.reload(); }
        }));
    });
    </script>
</x-layouts.admin>

