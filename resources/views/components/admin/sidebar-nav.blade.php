@props(['navItems'])

<nav {{ $attributes->class(['flex flex-col gap-1']) }}>
    @foreach ($navItems as $item)
        <a href="{{ $item['href'] }}" @class([
            'flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition',
            'bg-[#16136a] text-white shadow-lg shadow-[#16136a]/20' => $item['active'],
            'text-slate-600 hover:bg-[#16136a]/5 hover:text-[#16136a]' => ! $item['active'],
        ])>
            <span @class([
                'flex h-9 w-9 items-center justify-center rounded-xl border transition',
                'border-white/50 bg-white/10 text-white' => $item['active'],
                'border-[#16136a]/15 bg-white text-[#16136a]/70 hover:border-[#16136a]/40' => ! $item['active'],
            ])>
                <i class="{{ $item['icon'] }} text-lg" aria-hidden="true"></i>
            </span>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
