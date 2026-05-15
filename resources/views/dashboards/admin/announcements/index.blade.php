@php
    use Illuminate\Support\Str;
    $title = 'Announcements';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <header class="mb-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#16136a]/50">
                    <i class="ri-notification-3-line"></i>
                    Message Center
                </div>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Announcements</h1>
                <p class="max-w-xl text-sm font-medium text-slate-500">Create and manage updates for students.</p>
            </div>
            <div class="flex w-full md:w-auto">
                <a href="{{ route('admin.announcements.create') }}" class="flex h-14 w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 text-sm font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95 md:h-auto md:w-auto md:py-3.5 md:tracking-[0.2em]">
                    <i class="ri-add-line text-lg"></i>
                    <span class="whitespace-nowrap">New Announcement</span>
                </a>
            </div>
        </header>

        <!-- Filters Section -->
        <section class="mb-8">
            <form method="GET" class="grid grid-cols-1 gap-4 rounded-[24px] border border-slate-200/60 bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-4 lg:items-end lg:p-6">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Search</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search..." class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-11 pr-4 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Type</label>
                    <div class="relative">
                        <select name="type" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                            <option value="">All Types</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Priority</label>
                    <div class="relative">
                        <select name="priority" class="h-11 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-10 text-sm font-medium transition focus:border-[#16136a] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#16136a]/5">
                            <option value="">All Priorities</option>
                            @foreach ($priorities as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['priority'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2 lg:pt-0">
                    <button type="submit" class="flex-1 inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#16136a] px-5 text-[11px] font-semibold uppercase tracking-widest text-white transition hover:bg-[#16136a]/90 active:scale-95 lg:flex-none">
                        <i class="ri-filter-3-line text-base"></i>
                        Refine
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'priority', 'target_type']))
                        <a href="{{ route('admin.announcements.index') }}" class="flex-1 inline-flex h-11 items-center justify-center rounded-xl bg-slate-100 px-5 text-[11px] font-semibold uppercase tracking-widest text-slate-500 transition hover:bg-slate-200 lg:flex-none">Reset</a>
                    @endif
                </div>
            </form>
        </section>

        <!-- Main Content Area -->
        <div class="rounded-[32px] border border-slate-200/60 bg-white shadow-sm overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Announcement</th>
                            <th class="px-6 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 text-center">Type</th>
                            <th class="px-6 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 text-center">Priority</th>
                            <th class="px-6 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Audience</th>
                            <th class="px-6 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 text-right">Delivered</th>
                            <th class="px-8 py-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($announcements as $announcement)
                            <tr class="group transition-colors hover:bg-slate-50/50">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900 group-hover:text-[#16136a] transition-colors">{{ $announcement->title }}</span>
                                        <span class="mt-1 text-xs font-medium text-slate-400">{{ $announcement->sent_at?->format('M j, Y · g:i A') ?? 'Pending dispatch' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex rounded-lg border border-slate-200 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                                        {{ $types[$announcement->type] ?? $announcement->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $priorityClass = match($announcement->priority) {
                                            'high' => 'bg-rose-50 text-rose-600',
                                            'low' => 'bg-slate-100 text-slate-500',
                                            default => 'bg-emerald-50 text-emerald-600',
                                        };
                                    @endphp
                                    <span @class(['inline-flex rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-widest', $priorityClass])>
                                        {{ $priorities[$announcement->priority] ?? $announcement->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <i class="ri-user-received-line text-slate-400"></i>
                                        <span class="text-xs font-semibold text-slate-600">{{ $targetTypes[$announcement->target_type] ?? $announcement->target_type }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-semibold tabular-nums text-slate-700">{{ number_format($announcement->delivered_count ?? 0) }}</span>
                                        <span class="text-[10px] font-semibold uppercase tracking-tighter text-slate-400">Delivered</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-[#16136a] hover:text-[#16136a] hover:shadow-md">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Archive this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-rose-400 hover:text-rose-500 hover:shadow-md">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                            <i class="ri-notification-off-line text-3xl"></i>
                                        </div>
                                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Silence is golden</h3>
                                        <p class="mt-2 text-sm text-slate-500 max-w-xs">No announcements yet. Start a new update to reach your audience.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="grid grid-cols-1 divide-y divide-slate-100 md:hidden">
                @forelse ($announcements as $announcement)
                    <div class="p-5 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h4 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $announcement->title }}</h4>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ $announcement->sent_at?->format('M j, Y') ?? 'Pending' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400">
                                    <i class="ri-edit-line text-sm"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Archive this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-md border border-slate-200 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-widest text-slate-500">
                                {{ $types[$announcement->type] ?? $announcement->type }}
                            </span>
                            @php
                                $priorityClass = match($announcement->priority) {
                                    'high' => 'bg-rose-50 text-rose-600',
                                    'low' => 'bg-slate-100 text-slate-500',
                                    default => 'bg-emerald-50 text-emerald-600',
                                };
                            @endphp
                            <span @class(['rounded-full px-2 py-0.5 text-[9px] font-semibold uppercase tracking-widest', $priorityClass])>
                                {{ $priorities[$announcement->priority] ?? $announcement->priority }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-50 pt-3">
                            <div class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500 uppercase tracking-widest">
                                <i class="ri-user-received-line"></i>
                                {{ $targetTypes[$announcement->target_type] ?? $announcement->target_type }}
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-slate-700">{{ number_format($announcement->delivered_count ?? 0) }}</span>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-tighter">Delivered</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">No announcements found</p>
                    </div>
                @endforelse
            </div>
        </div>

            <!-- Pagination Footer -->
            <footer class="flex flex-col items-center justify-between gap-4 border-t border-slate-50 bg-slate-50/30 px-8 py-6 md:flex-row">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                    Showing {{ $announcements->firstItem() ?? 0 }}–{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }}
                </p>
                <div class="flex items-center gap-6">
                    <form method="GET" class="flex items-center gap-3">
                        @foreach (request()->except(['per_page', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Rows</label>
                        <select name="per_page" class="h-9 rounded-lg border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-600 focus:outline-none" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="dash-pagination">
                        {{ $announcements->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </footer>
        </div>
    </div>
</x-layouts.admin>
