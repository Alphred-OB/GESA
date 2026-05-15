@props(['cardWidth' => 'max-w-md'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? '') ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --auth-primary: #16136a;
            --auth-primary-soft: rgba(22, 19, 106, 0.05);
        }

        /* Micro-interactions */
        .auth-input-group:focus-within .auth-input-icon {
            color: var(--auth-primary);
            transform: scale(1.1) translateY(-1px);
        }

        .auth-input:focus {
            box-shadow: 0 0 0 4px rgba(22, 19, 106, 0.1), 0 10px 15px -3px rgba(22, 19, 106, 0.05);
            transform: translateY(-1px);
        }

        .auth-button-press:active {
            transform: scale(0.97);
        }

        .auth-card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.12);
        }

        /* Staggered Animations */
        @keyframes fade-slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation: fade-slide-up 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        .stagger-2 { animation: fade-slide-up 0.6s 0.1s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        .stagger-3 { animation: fade-slide-up 0.6s 0.2s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        .stagger-4 { animation: fade-slide-up 0.6s 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) both; }
        .stagger-5 { animation: fade-slide-up 0.6s 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) both; }

        .auth-glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 selection:bg-[#16136a]/10 selection:text-[#16136a]">
    <!-- Immersion Grid Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 opacity-[0.4] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_90%)]" 
             style="background-image: 
                linear-gradient(to right, #cbd5e1 1px, transparent 1px),
                linear-gradient(to bottom, #cbd5e1 1px, transparent 1px);
                background-size: 40px 40px;">
        </div>
    </div>
    
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-6 py-12">
        <!-- Logo & Title -->
        <div class="mb-10 flex flex-col items-center animate-fade-slide">
            <a href="{{ route('home') }}" class="group relative mb-6 flex h-20 w-20 items-center justify-center overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-slate-200 transition-all hover:scale-105 active:scale-95">
                <img src="{{ asset('logo.png') }}" alt="GESA" class="h-12 w-12 object-contain" width="48" height="48" fetchpriority="high">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#16136a]/5 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
            </a>
            <div class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/5 px-5 py-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-[#16136a] ring-1 ring-[#16136a]/10">
                <span class="h-1.5 w-1.5 rounded-full bg-[#16136a]"></span>
                GESA Student Portal
            </div>
        </div>

        <main class="w-full {{ $cardWidth }} animate-fade-slide-delay-200">
            <div class="overflow-hidden rounded-[2.5rem] border border-white bg-white/80 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] backdrop-blur-xl">
                {{ $slot ?? '' }}
            </div>
        </main>

        <footer class="mt-12 animate-fade-slide-delay-600 text-center">
            <p class="text-sm font-medium text-slate-400">
                &copy; {{ date('Y') }} Geomatic Engineering Students Association. All rights reserved.
            </p>
        </footer>
    </div>

    <!-- Processing Overlay -->
    <div id="auth-loading-overlay" class="hidden fixed inset-0 z-50 items-center justify-center bg-white/90 backdrop-blur-md">
        <div class="flex flex-col items-center space-y-6">
            <div class="relative flex h-20 w-20 items-center justify-center">
                <div class="absolute inset-0 animate-ping rounded-full bg-[#16136a]/10"></div>
                <div class="relative flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-xl ring-1 ring-slate-100">
                    <i class="ri-loader-4-line animate-spin text-3xl text-[#16136a]"></i>
                </div>
            </div>
            <div class="text-center">
                <p class="text-lg font-semibold text-slate-900">Processing Request</p>
                <p class="text-sm text-slate-500">Please wait while we secure your connection...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('[data-auth-form]');
            const overlay = document.getElementById('auth-loading-overlay');
            forms.forEach((form) => {
                form.addEventListener('submit', () => {
                    if (!overlay) return;
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                });
            });
        });
    </script>
</body>
</html>
