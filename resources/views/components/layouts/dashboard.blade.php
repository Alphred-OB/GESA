@props(['title' => null, 'sidebar' => null, 'header' => null, 'footer' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? '') ? $title . ' | ' : '' }}{{ config('app.name', 'ACSES Portal') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    {{-- Google Fonts: Outfit (Google Sans Alternative) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900" x-data="{ adminSidebarOpen: false }" x-on:admin-sidebar:toggle.window="adminSidebarOpen = !adminSidebarOpen" x-on:admin-sidebar:open.window="adminSidebarOpen = true" x-on:admin-sidebar:close.window="adminSidebarOpen = false" x-on:keydown.escape.window="adminSidebarOpen = false" :class="{ 'overflow-hidden': adminSidebarOpen }">
    <div class="min-h-screen w-full">
        @isset($sidebar)
            {{ $sidebar }}
        @endisset

        <div class="relative z-20 flex min-h-screen flex-col @isset($sidebar) lg:pl-[280px] @endisset">
            @if ($header)
                <div class="sticky top-0 z-40 shrink-0 bg-white shadow-sm md:bg-white/95 md:backdrop-blur supports-[backdrop-filter]:md:bg-white/75">
                    {{ $header }}
                </div>
            @else
                <div class="sticky top-0 z-40 shrink-0 bg-white shadow-sm md:bg-white/95 md:backdrop-blur supports-[backdrop-filter]:md:bg-white/75">
                    <x-dashboard.header />
                </div>
            @endif

            <main class="flex-1 pb-12 pt-4 sm:pt-6">
                {{ $slot ?? '' }}
            </main>

            @if ($footer)
                <div class="mt-auto">
                    {{ $footer }}
                </div>
            @else
                <div class="mt-auto">
                    <x-dashboard.footer />
                </div>
            @endif
        </div>
    </div>

    @stack('scripts')
</body>
</html>
