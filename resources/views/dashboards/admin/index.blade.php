<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Dashboard Header -->
        <header class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Overview</p>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    Welcome, <span class="text-[#16136a]">{{ explode(' ', $adminName)[0] }}</span>
                </h1>
                <p class="max-w-xl text-sm font-medium text-slate-500 leading-relaxed">
                    {{ $hero['message'] }}
                </p>
            </div>
            
            <div class="flex items-center gap-2 rounded-2xl bg-slate-50 px-4 py-2 border border-slate-200">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">{{ now()->format('l, M d') }}</span>
            </div>
        </header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Primary Stats -->
            <div class="lg:col-span-2 space-y-6">
                <!-- KPI Row -->
                <div class="grid gap-6 sm:grid-cols-2">
                    <!-- Registrations -->
                    <article class="rounded-[24px] border border-slate-200 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Pending Approvals</h3>
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <x-heroicon-o-user class="size-6" />
                            </span>
                        </div>
                        <div class="mt-4">
                            <p class="text-4xl font-semibold tabular-nums tracking-tight text-slate-900">{{ number_format($registrationSummary['pending'] ?? 0) }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold text-slate-400">+{{ $registrationSummary['submittedToday'] ?? 0 }} today</p>
                                <a href="{{ route('admin.pending-registrations.index') }}" class="text-xs font-semibold text-[#16136a] hover:underline">Manage</a>
                            </div>
                        </div>
                    </article>

                    <!-- Students -->
                    <article class="rounded-[24px] border border-slate-200 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Students</h3>
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-[#16136a]">
                                <x-heroicon-o-users class="size-6" />
                            </span>
                        </div>
                        <div class="mt-4">
                            <p class="text-4xl font-semibold tabular-nums tracking-tight text-slate-900">{{ $overviewCards[0]['value'] ?? 0 }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold text-slate-400">Total Enrollment</p>
                                <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-[#16136a] hover:underline">View All</a>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Lists Section -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Events -->
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="font-semibold text-slate-900">Upcoming Events</h3>
                            <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-slate-400 hover:text-[#16136a]">View Schedule</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($upcomingEvents as $event)
                                <div class="flex items-center gap-4 rounded-xl border border-slate-50 bg-slate-50/50 p-3">
                                    <div class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-lg bg-white border border-slate-200 text-[#16136a]">
                                        <span class="text-[9px] font-semibold uppercase opacity-40">{{ Str::of($event['schedule'])->explode(' ')->first() }}</span>
                                        <span class="text-sm font-semibold">{{ Str::of($event['schedule'])->explode(' ')->get(1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-800">{{ $event['title'] }}</p>
                                        <p class="truncate text-[11px] text-slate-400">{{ $event['location'] ?? 'Online' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="py-4 text-center text-xs text-slate-400">No events scheduled</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="rounded-[24px] border border-slate-200 bg-white p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="font-semibold text-slate-900">Recent Suggestions</h3>
                            <a href="{{ route('admin.suggestions.index') }}" class="text-xs font-semibold text-slate-400 hover:text-[#16136a]">View All</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($recentSuggestions as $suggestion)
                                <div class="flex items-center gap-3 rounded-xl border border-slate-50 bg-white p-3">
                                    <div @class([
                                        'h-2 w-2 shrink-0 rounded-full',
                                        'bg-amber-400' => $suggestion['status'] === 'Pending',
                                        'bg-emerald-400' => $suggestion['status'] === 'Resolved',
                                    ])></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-800">{{ $suggestion['subject'] }}</p>
                                        <p class="truncate text-[11px] text-slate-400">{{ $suggestion['owner'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="py-4 text-center text-xs text-slate-400">No recent messages</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Actions -->
            <div class="space-y-6">
                <!-- Academic Info -->
                <article class="rounded-[24px] border border-slate-200 bg-white p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-6">Quick Links</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.timeline.index') }}" class="flex items-center justify-between rounded-xl bg-slate-50 p-4 transition hover:bg-slate-100">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-clock class="size-5 text-[#16136a]" />
                                <span class="text-sm font-semibold text-slate-700">Academic Timeline</span>
                            </div>
                            <x-heroicon-o-chevron-right class="text-slate-300 size-5" />
                        </a>
                        <a href="{{ route('admin.profile') }}" class="flex items-center justify-between rounded-xl bg-slate-50 p-4 transition hover:bg-slate-100">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-cog-8-tooth class="size-5 text-slate-400" />
                                <span class="text-sm font-semibold text-slate-700">Account Settings</span>
                            </div>
                            <x-heroicon-o-chevron-right class="text-slate-300 size-5" />
                        </a>
                    </div>
                </article>

                <!-- Status Card -->
                <article class="rounded-[24px] bg-[#16136a] p-6 text-white">
                    <h3 class="text-xs font-semibold uppercase tracking-widest opacity-50 mb-4">Academic Year</h3>
                    <p class="text-lg font-semibold">2023/2024</p>
                    <p class="mt-1 text-sm opacity-70">First Semester</p>
                </article>
            </div>
        </div>
    </div>
</x-layouts.admin>
