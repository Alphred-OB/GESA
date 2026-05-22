<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="space-y-10">
            {{-- Simplified Bento Hero --}}
            <section class="relative isolate overflow-hidden rounded-2xl bg-[#16136a] p-6 sm:p-10 text-white shadow-xl shadow-[#16136a]/20">
                <div class="relative z-10 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/80 ring-1 ring-white/20 backdrop-blur-md">
                                <x-heroicon-o-light-bulb class="size-5" /> Feedback
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-none text-white">Suggestion Box</h1>
                            <p class="text-sm font-medium text-white/70 leading-relaxed max-w-xl">
                                Share ideas, highlight concerns, or request improvements.
                            </p>
                        </div>
                    </div>
                    
                    <div class="shrink-0 rounded-xl border border-white/10 bg-white/5 px-6 py-4 text-sm shadow-inner backdrop-blur-md max-w-[300px]">
                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-white/50">Response Window</p>
                        <p class="mt-2 text-xs font-semibold leading-relaxed text-white/70">
                            Typically <span class="text-white font-semibold">2 business days</span>.
                        </p>
                    </div>
                </div>

                <!-- Subtle background depth -->
                <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-white/5 blur-3xl"></div>
                <x-heroicon-o-light-bulb class="absolute -right-10 -bottom-10 text-[240px] text-white/[0.03] -rotate-12 select-none pointer-events-none size-5" />
            </section>

            @if (session('status'))
                <div class="animate-fade-slide rounded-xl border border-blue-200 bg-blue-50 p-4 text-blue-900 shadow-lg shadow-blue-100/60">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold">Submission received</p>
                            <p class="text-sm text-blue-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="animate-fade-slide rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-900 shadow-lg shadow-rose-100/60">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M12 8v4" />
                            <path d="M12 16h.01" />
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold">Please fix the highlighted fields</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm text-rose-800">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <section class="lg:col-span-2">
                    <article class="animate-fade-slide rounded-xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <header>
                            <h2 class="text-lg font-semibold text-[#16136a]">Submit a suggestion</h2>
                            <p class="text-sm text-slate-500">All fields marked with * are required. Attach relevant screenshots or files if available (max 4&nbsp;MB).</p>
                        </header>

                        <form method="POST" action="{{ route('student.suggestions.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf

                            <div class="grid gap-6 sm:grid-cols-2">
                                <label class="flex flex-col gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Category *</span>
                                    <div class="relative">
                                        <select name="category" class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-12 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                                            @foreach ($categories as $value => $label)
                                                <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </div>
                                </label>

                                <label class="flex flex-col gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Subject *</span>
                                    <input type="text" name="subject" value="{{ old('subject') }}" maxlength="160" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40" placeholder="Give a short headline">
                                </label>
                            </div>

                            <label class="flex flex-col gap-2">
                                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Message *</span>
                                <textarea name="message" rows="6" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-[#16136a]/60 focus:outline-none focus:ring-2 focus:ring-[#16136a]/40" placeholder="Explain the idea, improvement, or issue in detail. Include specific examples or references.">{{ old('message') }}</textarea>
                            </label>

                            <label class="flex flex-col gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50/60 px-4 py-6 text-sm text-slate-600">
                                <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Attachment (optional)</span>
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium text-slate-700">Add supporting files</p>
                                        <p class="text-xs text-slate-500">Accepted formats: PNG, JPG, PDF, DOCX (max 4&nbsp;MB)</p>
                                    </div>
                                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-[#16136a]/40 hover:text-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path d="M9 14 15 8" />
                                            <path d="M9.5 8.5 9 9a4 4 0 0 0 0 6 4 4 0 0 0 5.66 0l3.12-3.12a4 4 0 1 0-5.66-5.66L10.5 7.5" />
                                        </svg>
                                        <span>Upload file</span>
                                        <input type="file" name="attachment" class="sr-only" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx">
                                    </label>
                                </div>
                            </label>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-6 py-3 text-sm font-semibold text-white shadow transition hover:-translate-y-0.5 hover:bg-[#18188a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path d="m5 12 5 5L20 7" />
                                    </svg>
                                    Submit suggestion
                                </button>
                            </div>
                        </form>
                    </article>
                </section>

                <aside class="space-y-6">
                    <article class="animate-fade-slide rounded-xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">What to include</h3>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li class="flex gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 flex-none items-center justify-center rounded-full bg-[#16136a]/10 text-xs font-semibold text-[#16136a]">1</span>
                                <div>
                                    <p class="font-medium text-slate-800">Clear context</p>
                                    <p class="text-xs text-slate-500">Where did you spot the issue or what area will this suggestion improve?</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 flex-none items-center justify-center rounded-full bg-[#16136a]/10 text-xs font-semibold text-[#16136a]">2</span>
                                <div>
                                    <p class="font-medium text-slate-800">Desired outcome</p>
                                    <p class="text-xs text-slate-500">Explain the benefit to students, staff, or campus operations.</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 flex-none items-center justify-center rounded-full bg-[#16136a]/10 text-xs font-semibold text-[#16136a]">3</span>
                                <div>
                                    <p class="font-medium text-slate-800">Supporting detail</p>
                                    <p class="text-xs text-slate-500">Attach files, screenshots, or references that provide further clarity.</p>
                                </div>
                            </li>
                        </ul>
                    </article>

                    <article class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Need immediate help?</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                            <p class="flex items-center gap-2 text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#16136a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M22 16.92a4 4 0 0 1-4 4 12 12 0 0 1-10.65-6A12 12 0 0 1 1.08 6a4 4 0 0 1 4-4h1.26a2 2 0 0 1 2 1.72 12.05 12.05 0 0 0 .7 2.7 2 2 0 0 1-.45 2.11L7.91 9.91a10 10 0 0 0 6.18 6.18l1.39-1.39a2 2 0 0 1 2.11-.45 12.05 12.05 0 0 0 2.7.7 2 2 0 0 1 1.72 2Z" />
                                </svg>
                                Call the student services hotline on <span class="font-semibold">055 935 9824</span> (08:00–20:00 GMT)
                            </p>
                            <p class="flex items-center gap-2 text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#16136a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M22 4H2" />
                                    <path d="M22 10H2" />
                                    <path d="m2 4 10 8L22 4" />
                                    <path d="M2 16h20" />
                                    <path d="M2 20h20" />
                                </svg>
                                Email <a href="mailto:acsesrepos@gmail.com" class="font-semibold text-[#16136a] underline-offset-4 hover:underline">acsesrepos@gmail.com</a>
                            </p>
                        </div>
                    </article>
                </aside>
            </div>

            <section class="space-y-4">
                <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-[#16136a]">Your submissions</h2>
                        <p class="text-sm text-slate-500">Track suggestions you have sent. Status updates appear here once the team reviews them.</p>
                    </div>
                    @if ($suggestions->hasPages())
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <span>Page {{ $suggestions->currentPage() }} of {{ $suggestions->lastPage() }}</span>
                            <div class="flex items-center gap-1">
                                {{ $suggestions->onEachSide(1)->links('vendor.pagination.simple-tailwind') }}
                            </div>
                        </div>
                    @endif
                </header>

                @if ($suggestions->isEmpty())
                    <article class="rounded-xl border border-dashed border-slate-300 bg-white/70 p-8 text-center text-sm text-slate-500">
                        <p>No suggestions submitted yet. Share your first idea using the form above.</p>
                    </article>
                @else
                    <div class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-lg shadow-[#16136a]/5">
                        <div class="hidden lg:block">
                            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs uppercase tracking-[0.2em] text-slate-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left">Subject</th>
                                        <th scope="col" class="px-6 py-4 text-left">Category</th>
                                        <th scope="col" class="px-6 py-4 text-left">Submitted</th>
                                        <th scope="col" class="px-6 py-4 text-left">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left">Attachment</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($suggestions as $suggestion)
                                        <tr class="transition hover:bg-slate-50">
                                            <td class="px-6 py-4 align-top">
                                                <p class="font-medium text-slate-800">{{ $suggestion->subject }}</p>
                                                <p class="mt-1 text-xs text-slate-500">{{ Str::limit($suggestion->message, 120) }}</p>
                                            </td>
                                            <td class="px-6 py-4 align-top text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}</td>
                                            <td class="px-6 py-4 align-top text-sm text-slate-500">{{ $suggestion->created_at?->diffForHumans() }}</td>
                                            <td class="px-6 py-4 align-top">
                                                @php
                                                    $status = Str::headline($suggestion->status);
                                                    $statusStyles = [
                                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'in review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'resolved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    ];
                                                    $badgeClass = $statusStyles[strtolower($status)] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                                @endphp
                                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $status }}</span>
                                            </td>
                                            <td class="px-6 py-4 align-top text-sm">
                                                @if ($suggestion->attachment_path)
                                                    <a href="{{ Storage::disk('public')->url($suggestion->attachment_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-[#16136a] transition hover:underline">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                            <path d="M4 4v12a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V8l-6-6H8a4 4 0 0 0-4 4Z" />
                                                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-slate-200 text-sm text-slate-600 lg:hidden">
                            @foreach ($suggestions as $suggestion)
                                <div class="space-y-3 px-4 py-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $suggestion->subject }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ Str::limit($suggestion->message, 140) }}</p>
                                        </div>
                                        <span class="text-xs text-slate-400">{{ $suggestion->created_at?->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-400">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-500">{{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}</span>
                                        @php
                                            $status = Str::headline($suggestion->status);
                                            $statusStyles = [
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'in review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'resolved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            ];
                                            $badgeClass = $statusStyles[strtolower($status)] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $status }}</span>
                                    </div>
                                    <div>
                                        @if ($suggestion->attachment_path)
                                            <a href="{{ Storage::disk('public')->url($suggestion->attachment_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-[#16136a] transition hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                                    <path d="M4 4v12a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V8l-6-6H8a4 4 0 0 0-4 4Z" />
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                </svg>
                                                Download attachment
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">No attachment</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <div class="pt-4">
                        {{ $suggestions->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-layouts.dashboard>
