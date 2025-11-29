@php
    use Illuminate\Support\Str;
    $title = 'Announcements';
@endphp

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10">
                <div class="space-y-2">
                    <div class="skeleton inline-flex h-7 w-52 items-center rounded-full bg-[#16136a]/10"></div>
                    <div class="skeleton h-8 w-64 rounded-2xl bg-slate-200"></div>
                    <div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
                </div>
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    <div class="skeleton h-11 w-44 rounded-2xl bg-[#16136a]/10"></div>
                </div>
            </header>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="grid gap-4 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 md:grid-cols-4">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex flex-col gap-2">
                            <div class="skeleton h-3 w-24 rounded-full bg-slate-200"></div>
                            <div class="skeleton h-11 w-full rounded-2xl bg-white"></div>
                        </div>
                    @endfor
                    <div class="md:col-span-4 flex items-center justify-end gap-3">
                        <div class="skeleton h-10 w-24 rounded-2xl bg-white"></div>
                        <div class="skeleton h-10 w-32 rounded-2xl bg-[#16136a]/10"></div>
                    </div>
                </div>

                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <div class="skeleton h-3 w-72 rounded-full bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <div class="skeleton h-3 w-40 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-9 w-20 rounded-xl bg-slate-100"></div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="hidden md:block">
                        <div class="skeleton h-10 w-full bg-slate-50/80"></div>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="skeleton h-12 w-full bg-white"></div>
                        @endfor
                    </div>
                    <div class="grid gap-4 p-4 md:hidden">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="skeleton h-28 w-full rounded-2xl bg-white"></div>
                        @endfor
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <div class="skeleton h-3 w-40 rounded-full bg-slate-200"></div>
                    <div class="skeleton h-8 w-32 rounded-2xl bg-slate-100 sm:ml-auto"></div>
                </div>
            </section>
        </div>

        <div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
                        <i class="ri-megaphone-line text-base" aria-hidden="true"></i>
                        Admin communications
                    </p>
                    <h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Announcements</h1>
                    <p class="text-sm text-slate-600">Broadcast updates to the entire student body or target specific groups.</p>
                </div>
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-add-line text-lg"></i>
                        New announcement
                    </a>
                </div>
            </header>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-4 md:grid-cols-4">
                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Search</span>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Title or content" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Type</span>
                        <div class="relative">
                            <select name="type" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All</option>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Priority</span>
                        <div class="relative">
                            <select name="priority" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All</option>
                                @foreach ($priorities as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['priority'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Audience</span>
                        <div class="relative">
                            <select name="target_type" class="h-11 w-full appearance-none rounded-2xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="">All</option>
                                @foreach ($targetTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['target_type'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </label>

                    <div class="md:col-span-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Reset
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                            <i class="ri-filter-3-line text-base"></i>
                            Apply
                        </button>
                    </div>
                </form>

                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/70 bg-white/60 p-4 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <p class="font-semibold">Showing {{ $announcements->firstItem() ?? 0 }}–{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} announcements</p>
                    <form method="GET" class="flex items-center gap-2">
                        @foreach (request()->except(['per_page', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label for="announcements_per_page" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rows per page</label>
                        <select id="announcements_per_page" name="per_page" class="h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a]" onchange="this.form.submit()">
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-[13px] text-slate-600 md:table">
                            <thead class="bg-slate-50/80 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                                <tr>
                                    <th class="px-5 py-2.5">Announcement</th>
                                    <th class="px-5 py-2.5">Type</th>
                                    <th class="px-5 py-2.5">Priority</th>
                                    <th class="px-5 py-2.5">Audience</th>
                                    <th class="px-5 py-2.5">Sent</th>
                                    <th class="px-5 py-2.5 text-right">Delivered</th>
                                    <th class="px-5 py-2.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($announcements as $announcement)
                                    <tr class="transition hover:bg-slate-50/60">
                                        <td class="px-5 py-3">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-[15px] font-semibold text-slate-900">{{ $announcement->title }}</span>
                                                <p class="text-xs text-slate-500 line-clamp-2">{{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content), 120) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $types[$announcement->type] ?? Str::headline($announcement->type) }}</td>
                                        <td class="px-5 py-3">
                                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ match($announcement->priority) {
                                                'high' => 'bg-rose-50 text-rose-600',
                                                'low' => 'bg-sky-50 text-sky-700',
                                                default => 'bg-emerald-50 text-emerald-700',
                                            } }}">
                                                {{ $priorities[$announcement->priority] ?? Str::headline($announcement->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-500">{{ $targetTypes[$announcement->target_type] ?? Str::headline($announcement->target_type) }}</td>
                                        <td class="px-5 py-3 text-[12px] text-slate-500">{{ $announcement->sent_at?->format('M j, Y · g:i A') ?? '—' }}</td>
                                        <td class="px-5 py-3 text-right text-[12px] text-slate-600">{{ number_format($announcement->delivered_count ?? 0) }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                                    <i class="ri-edit-line" aria-hidden="true"></i>
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-full border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                                        <i class="ri-delete-bin-line" aria-hidden="true"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <i class="ri-megaphone-off-line text-3xl text-slate-300"></i>
                                                <p class="font-semibold text-slate-600">No announcements yet</p>
                                                <p class="text-sm text-slate-500">Send your first update using the "New announcement" button above.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="grid gap-4 md:hidden">
                        @forelse ($announcements as $announcement)
                            <article class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                                <header class="space-y-1">
                                    <h2 class="text-base font-semibold text-slate-900">{{ $announcement->title }}</h2>
                                    <p class="text-xs text-slate-500">{{ $announcement->sent_at?->format('M j, Y · g:i A') ?? 'Pending dispatch' }}</p>
                                </header>
                                <p class="mt-2 text-sm text-slate-600">{{ $announcement->excerpt ?? Str::limit(strip_tags($announcement->content), 150) }}</p>
                                <dl class="mt-4 space-y-2 text-xs text-slate-500">
                                    <div class="flex items-center justify-between">
                                        <dt>Type</dt>
                                        <dd>{{ $types[$announcement->type] ?? Str::headline($announcement->type) }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Priority</dt>
                                        <dd>{{ $priorities[$announcement->priority] ?? Str::headline($announcement->priority) }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Audience</dt>
                                        <dd>{{ $targetTypes[$announcement->target_type] ?? Str::headline($announcement->target_type) }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt>Delivered</dt>
                                        <dd>{{ number_format($announcement->delivered_count ?? 0) }}</dd>
                                    </div>
                                </dl>
                                <footer class="mt-4 space-y-3 text-xs font-semibold">
                                    <div class="flex items-center justify-between gap-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
                                            <i class="ri-edit-line text-sm" aria-hidden="true"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-full border border-rose-200 px-3 py-1.5 text-[11px] font-semibold text-rose-600 transition hover:bg-rose-50">
                                                <i class="ri-delete-bin-line text-sm" aria-hidden="true"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </footer>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-sm text-slate-500">
                                <i class="ri-megaphone-off-line text-3xl text-slate-300"></i>
                                <p class="mt-3 font-semibold text-slate-600">No announcements yet</p>
                                <p class="text-sm text-slate-500">Send your first update using the button above.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <p class="text-xs text-slate-500">Page {{ $announcements->currentPage() }} of {{ $announcements->lastPage() }}</p>
                    <div class="flex justify-center sm:ml-auto sm:justify-end">
                        {{ $announcements->onEachSide(1)->links('vendor.pagination.data-limit') }}
                    </div>
                </div>
            </section>
        </div>
    </x-layouts.admin>
