@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-2 text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-slate-300">
                <i class="ri-arrow-left-line text-xs" aria-hidden="true"></i>
                Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]" aria-label="Go to previous page">
                <i class="ri-arrow-left-line text-xs" aria-hidden="true"></i>
                Prev
            </a>
        @endif

        <ul class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- Ellipsis Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-2 text-xs font-semibold text-slate-400">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span aria-current="page" class="inline-flex min-w-[2.25rem] items-center justify-center rounded-xl bg-[#16136a] px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-sm">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="inline-flex min-w-[2.25rem] items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]" aria-label="Go to next page">
                Next
                <i class="ri-arrow-right-line text-xs" aria-hidden="true"></i>
            </a>
        @else
            <span class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-slate-300">
                Next
                <i class="ri-arrow-right-line text-xs" aria-hidden="true"></i>
            </span>
        @endif
    </nav>
@endif
