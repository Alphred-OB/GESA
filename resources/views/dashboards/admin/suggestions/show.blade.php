@php
    $title = 'Review Suggestion';
@endphp

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">
            {{-- Header --}}
            <nav class="flex items-center gap-4">
                <a href="{{ route('admin.suggestions.index') }}" class="flex h-12 items-center gap-3 rounded-2xl bg-slate-50 px-6 text-[10px] font-semibold uppercase tracking-widest text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600">
                    <x-heroicon-o-arrow-left class="size-5" />
                    Back to Inbox
                </a>
            </nav>

            <header class="text-center lg:text-left flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/5 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-[#16136a] mb-4">
                        <x-heroicon-o-chat-bubble-left class="size-4" />
                        Student Voice
                    </div>
                    <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">{{ $suggestion->subject }}</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-400 uppercase tracking-widest">Feedback from {{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Anonymous' }}</p>
                </div>
                
                @php
                    $status = strtolower($suggestion->status ?? 'pending');
                    $statusColor = match($status) {
                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'in_review' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'resolved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'dismissed' => 'bg-rose-100 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-500 border-slate-200'
                    };
                @endphp
                <div class="inline-flex h-14 items-center gap-3 rounded-[1.5rem] border {{ $statusColor }} px-6">
                    <span class="relative flex h-2.5 w-2.5">
                        @if($status === 'pending')
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                        @endif
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-current"></span>
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-[0.2em]">{{ $statuses[$suggestion->status] ?? Str::headline($suggestion->status) }}</span>
                </div>
            </header>

            @if (session('status'))
                <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 shadow-sm">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-check-circle class="size-6" />
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-8">
                {{-- Suggestion Message --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12 relative overflow-hidden">
                    <x-heroicon-o-chat-bubble-left-right class="absolute top-8 left-8 text-6xl text-[#16136a]/5 size-5" />
                    <div class="relative z-10">
                        <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Message Content</h2>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-lg font-semibold leading-relaxed text-slate-700 whitespace-pre-wrap">
                                {{ $suggestion->message }}
                            </p>
                        </div>

                        @if ($suggestion->attachment_path)
                            <div class="mt-12 flex items-center justify-between gap-6 rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-500/20">
                                        <x-heroicon-o-paper-clip class="size-6" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#16136a]">Supporting Document</p>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Provided by student</p>
                                    </div>
                                </div>
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($suggestion->attachment_path) }}" target="_blank" class="flex h-12 items-center gap-2 rounded-2xl bg-white px-6 text-[10px] font-semibold uppercase tracking-widest text-[#16136a] shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                                    <x-heroicon-o-cloud-arrow-down class="size-5" />
                                    View File
                                </a>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Administrative Action --}}
                <section class="rounded-[2.5rem] border border-slate-200/60 bg-white p-8 shadow-2xl shadow-slate-200/40 lg:p-12">
                    <h2 class="mb-8 text-sm font-semibold uppercase tracking-widest text-[#16136a]">Management</h2>
                    
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400 font-semibold text-lg">
                                    {{ strtoupper(substr($suggestion->user?->fullname ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#16136a]">{{ $suggestion->user?->fullname ?? $suggestion->user?->username ?? 'Anonymous Student' }}</p>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">{{ $suggestion->user?->email ?? 'No email provided' }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-4 pt-2">
                                <div class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                    <x-heroicon-o-calendar class="size-4" />
                                    {{ $suggestion->created_at?->format('M j, Y') }}
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                                    <x-heroicon-o-tag class="size-4" />
                                    {{ $categories[$suggestion->category] ?? Str::headline($suggestion->category) }}
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.suggestions.update', $suggestion) }}" class="flex items-center gap-3">
                            @csrf
                            @method('PUT')
                            <select name="status" class="h-14 w-48 rounded-2xl border-none bg-slate-50 px-5 text-xs font-semibold uppercase tracking-widest text-slate-900 outline-none ring-2 ring-transparent transition-all focus:bg-white focus:ring-[#16136a]/10">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected($suggestion->status === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="flex h-14 items-center gap-3 rounded-2xl bg-[#16136a] px-8 text-xs font-semibold uppercase tracking-widest text-white shadow-xl shadow-[#16136a]/20 transition-all hover:opacity-90 active:scale-95">
                                <x-heroicon-o-arrow-down-on-square class="size-5" />
                                Update
                            </button>
                        </form>
                    </div>

                    @if ($suggestion->handled_at)
                        <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 px-5 py-4">
                            <x-heroicon-o-clock class="text-emerald-600 size-5" />
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-700">
                                Last handled {{ $suggestion->handled_at->format('M j, Y · g:i A') }}
                            </p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-layouts.admin>
