@php($title = $title ?? 'Manage events')

<x-layouts.admin :title="$title">
    @include('components.dashboard.skeleton-styles')

    <div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
        <div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
            <header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5">
                <div class="space-y-2">
                    <div class="skeleton inline-flex h-7 w-44 items-center rounded-full bg-[#16136a]/10"></div>
                    <div class="skeleton h-8 w-72 rounded-2xl bg-slate-200"></div>
                    <div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
                    <div class="skeleton h-10 w-32 rounded-2xl bg-[#16136a]/10"></div>
                </div>
            </header>

            <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <div class="space-y-2">
                        <div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
                        <div class="skeleton h-3 w-56 rounded-full bg-slate-100"></div>
                    </div>
                    <div class="skeleton h-10 w-40 rounded-2xl bg-slate-100"></div>
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
                            <div class="skeleton h-20 w-full rounded-2xl bg-white"></div>
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
            <header class="flex flex-col gap-6 rounded-3xl border border-[#16136a]/15 bg-white/85 p-6 shadow-lg shadow-[#16136a]/10 lg:items-center lg:justify-between lg:p-8 lg:flex-row">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.25em] text-[#16136a] sm:text-xs">
                        <i class="ri-calendar-check-fill text-base" aria-hidden="true"></i>
                        Event management
                    </p>
                    <h1 class="text-2xl font-bold text-[#16136a] md:text-3xl">Manage campus events</h1>
                    <p class="text-sm text-slate-600">Curate events that appear on the student timeline and activity feeds.</p>
                </div>
                <div class="flex">
                    <a href="{{ route('admin.events.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#16136a] px-6 py-3.5 text-sm font-bold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/20 transition-all hover:-translate-y-0.5 active:scale-95 sm:w-auto">
                        <i class="ri-add-line text-lg"></i>
                        New event
                    </a>
                </div>
            </header>

        @if (session('status'))
            <div class="rounded-3xl border border-emerald-200/60 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-inner">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <i class="ri-check-line text-lg" aria-hidden="true"></i>
                    </span>
                    <p>{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <div>
                    <h2 class="text-lg font-semibold text-[#16136a]">Scheduled events</h2>
                    <p class="text-sm text-slate-500">Showing {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events.</p>
                </div>
                <form method="GET" class="flex flex-col items-center gap-2 sm:flex-row" x-data>
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="per_page" class="text-sm font-medium text-slate-600">Rows per page</label>
                    <select id="per_page" name="per_page" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a] sm:w-auto" x-on:change="$el.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                <div class="hidden md:block">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Event</th>
                                <th scope="col" class="px-6 py-3">Schedule</th>
                                <th scope="col" class="px-6 py-3">Location</th>
                                <th scope="col" class="px-6 py-3">Category</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @include('dashboards.admin.events.partials.table-rows', ['events' => $events])
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden">
                    @include('dashboards.admin.events.partials.mobile-list', ['events' => $events])
                </div>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <p class="text-xs text-slate-500">Page {{ $events->currentPage() }} of {{ $events->lastPage() }}</p>
                <div class="sm:ml-auto">
                    {{ $events->onEachSide(1)->links() }}
                </div>
            </div>
        </section>
    </div>
</x-layouts.admin>
