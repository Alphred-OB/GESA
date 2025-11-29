<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? '') ? $title . ' | ' : '' }}{{ config('app.name', 'GESA Portal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-white via-slate-100 to-slate-200 text-slate-900">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[#16136a]">
                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-10 w-10 rounded-xl border border-[#16136a]/20 object-contain" loading="lazy">
                    <span class="text-base font-semibold">GESA Portal</span>
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/20 bg-white px-4 py-2 text-sm font-semibold text-[#16136a] shadow transition hover:-translate-y-0.5 hover:border-[#16136a]/40">Sign in</a>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot ?? '' }}
        </main>

        <footer class="border-t border-slate-200/80 bg-white/90 py-6">
            <div class="mx-auto w-full max-w-6xl px-5 text-center text-xs text-slate-500 sm:flex sm:items-center sm:justify-between sm:text-left">
                <p>© {{ now()->year }} GESA. All rights reserved.</p>
                <div class="mt-3 flex justify-center gap-4 sm:mt-0 sm:justify-end">
                    <a href="{{ route('legal.terms') }}" class="transition hover:text-[#16136a]">Terms</a>
                    <a href="{{ route('legal.privacy') }}" class="transition hover:text-[#16136a]">Privacy</a>
                    <a href="{{ route('legal.cookies') }}" class="transition hover:text-[#16136a]">Cookies</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
