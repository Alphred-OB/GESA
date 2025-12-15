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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.456.0/dist/umd/lucide.min.js" defer></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="relative flex min-h-screen flex-col">
        <div class="grid flex-1 gap-0 lg:grid-cols-2">
            <!-- Aurora 3D Animated Background -->
            <div class="relative hidden overflow-hidden bg-[#16136a] lg:flex" id="aurora-container">
                <!-- Ambient Background Glows -->
                <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-[#3b82f6] opacity-20 blur-[100px] filter"></div>
                <div class="absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-[#8b5cf6] opacity-20 blur-[100px] filter"></div>

                <!-- Animated Geometric SVG Shapes - Wrapped for robustness -->
                <style>
                    @keyframes float-slow {
                        0%, 100% { transform: translateY(0px) rotate(0deg); }
                        50% { transform: translateY(-15px) rotate(3deg); }
                    }
                    @keyframes float-medium {
                        0%, 100% { transform: translateY(0px) rotate(0deg); }
                        50% { transform: translateY(-10px) rotate(-2deg); }
                    }
                    @keyframes float-fast {
                        0%, 100% { transform: translateY(0px) scale(1); }
                        50% { transform: translateY(-8px) scale(1.05); }
                    }
                    @keyframes spin-slow {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    .animate-float-slow {
                        animation: float-slow 8s ease-in-out infinite;
                    }
                    .animate-float-medium {
                        animation: float-medium 6s ease-in-out infinite;
                    }
                    .animate-float-fast {
                        animation: float-fast 4s ease-in-out infinite;
                    }
                    .animate-spin-slow {
                        animation: spin-slow 20s linear infinite;
                    }
                    .parallax-shape {
                        transition: transform 0.1s ease-out;
                    }
                    /* Ensure SVGs are visible */
                    .parallax-shape svg {
                        display: block;
                        width: 100%;
                        height: 100%;
                        overflow: visible;
                    }
                </style>

                <div class="parallax-shape absolute" style="left: 5%; top: 10%; width: 5rem; height: 5rem; z-index: 5; display: block;" data-speed="2">
                    <svg class="animate-float-slow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="none">
                        <path d="M 128 192 C 92.654 192 64 220.654 64 256 L 0 256 C 0 185.308 57.308 128 128 128 Z M 256 128 C 256 198.692 198.692 256 128 256 L 128 192 C 163.346 192 192 163.346 192 128 Z M 128 64 C 92.654 64 64 92.654 64 128 L 0 128 C 0 57.308 57.308 0 128 0 Z M 256 0 C 256 70.692 198.692 128 128 128 L 128 64 C 163.346 64 192 35.346 192 0 Z" fill="#60a5fa"/>
                    </svg>
                </div>
                
                <div class="parallax-shape absolute" style="right: 8%; top: 25%; width: 6rem; height: 6rem; z-index: 5; display: block;" data-speed="-1.5">
                    <svg class="animate-float-medium" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="none">
                        <path d="M 64 192 L 128 192 L 128 256 L 64 256 C 28.654 256 0 227.346 0 192 L 0 128 L 64 128 Z M 192 192 L 256 192 L 256 256 L 192 256 C 156.654 256 128 227.346 128 192 L 128 128 L 192 128 Z M 64 64 L 128 64 L 128 0 L 192 0 C 227.346 0 256 28.654 256 64 L 256 128 L 192 128 L 192 64 L 128 64 L 128 128 L 64 128 C 28.654 128 0 99.346 0 64 L 0 0 L 64 0 Z" fill="#38bdf8"/>
                    </svg>
                </div>
                
                <div class="parallax-shape absolute" style="left: 40%; top: 5%; width: 4rem; height: 4rem; z-index: 5; display: block;" data-speed="1.5">
                    <svg class="animate-float-medium" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="none">
                        <path d="M 144 256 L 27.598 256 L 144 139.598 Z M 256 207.5 L 200 256 L 200 56 L 0 56 L 48 0 L 256 0 Z M 0 204.402 L 0 112 L 92.402 112 Z" fill="#a78bfa"/>
                    </svg>
                </div>
                
                <div class="parallax-shape absolute" style="left: 15%; bottom: 20%; width: 4.5rem; height: 4.5rem; z-index: 5; display: block;" data-speed="3">
                    <svg class="animate-spin-slow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="none">
                        <path d="M 60 136 C 93.137 136 120 162.863 120 196 C 120 229.137 93.137 256 60 256 C 26.863 256 0 229.137 0 196 C 0 162.863 26.863 136 60 136 Z M 196 136 C 229.137 136 256 162.863 256 196 C 256 229.137 229.137 256 196 256 C 162.863 256 136 229.137 136 196 C 136 162.863 162.863 136 196 136 Z M 128 104 C 141.255 104 152 114.745 152 128 C 152 141.255 141.255 152 128 152 C 114.745 152 104 141.255 104 128 C 104 114.745 114.745 104 128 104 Z M 60 0 C 93.137 0 120 26.863 120 60 C 120 93.137 93.137 120 60 120 C 26.863 120 0 93.137 0 60 C 0 26.863 26.863 0 60 0 Z M 196 0 C 229.137 0 256 26.863 256 60 C 256 93.137 229.137 120 196 120 C 162.863 120 136 93.137 136 60 C 136 26.863 162.863 0 196 0 Z" fill="#818cf8"/>
                    </svg>
                </div>
                
                <div class="parallax-shape absolute" style="right: 15%; bottom: 10%; width: 7rem; height: 7rem; z-index: 5; display: block;" data-speed="1">
                    <svg class="animate-float-fast" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="none">
                        <path d="M 128 0 C 198.692 0 256 57.308 256 128 C 256 198.692 198.692 256 128 256 C 57.308 256 0 198.692 0 128 C 0 57.308 57.308 0 128 0 Z M 128 32 C 74.98 32 32 74.98 32 128 C 32 181.019 74.98 224 128 224 C 181.019 224 224 181.019 224 128 C 224 74.98 181.019 32 128 32 Z M 128 56 C 167.765 56 200 88.236 200 128 C 200 167.765 167.765 200 128 200 C 88.236 200 56 167.765 56 128 C 56 88.236 88.236 56 128 56 Z M 128 88 C 105.909 88 88 105.909 88 128 C 88 150.091 105.909 168 128 168 C 150.091 168 168 150.091 168 128 C 168 105.909 150.091 88 128 88 Z M 128 112 C 136.837 112 144 119.163 144 128 C 144 136.837 136.837 144 128 144 C 119.163 144 112 136.837 112 128 C 112 119.163 119.163 112 128 112 Z" fill="#22d3ee"/>
                    </svg>
                </div>

                <!-- Content Container (Z-Index to stay on top) -->
                <div class="relative z-10 flex w-full flex-col items-center justify-center px-8 py-16 text-center text-white xl:px-16">
                    @if (isset($hero) && ! $hero->isEmpty())
                        {{ $hero }}
                    @else
                        <div class="space-y-7">
                            <div class="flex flex-col items-center gap-3 text-white/90">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 shadow-lg backdrop-blur-md border border-white/10">
                                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-8 w-8 object-contain" loading="lazy">
                                </div>
                                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-slate-100 backdrop-blur-sm border border-white/5">
                                    Secure access • Step 2 of 2
                                </div>
                            </div>

                            <h2 class="text-3xl font-semibold leading-snug text-white md:text-4xl">
                                Check your inbox for the GESA verification code.
                            </h2>
                            <p class="mx-auto max-w-xl text-base text-white/80">
                                We just emailed a 6-digit One-Time Passcode to finish signing in. Look for the message with subject
                                <span class="font-semibold text-slate-100">"GESA verification code"</span>. It usually arrives instantly—if you don’t see it, check your spam or promotions folder.
                            </p>

                            <div class="grid w-full gap-4 text-left md:grid-cols-2">
                                <div class="rounded-2xl border border-white/15 bg-white/5 p-5 backdrop-blur-md transition hover:-translate-y-1 hover:bg-white/10">
                                    <p class="text-sm font-semibold text-slate-100">Tip — Search your mailbox</p>
                                    <p class="mt-2 text-sm text-white/85">Search for <span class="font-semibold">"ACSES"</span> or <span class="font-semibold">{{ config('mail.from.address') }}</span> to surface the code email quickly.</p>
                                </div>
                                <div class="rounded-2xl border border-white/15 bg-white/5 p-5 backdrop-blur-md transition hover:-translate-y-1 hover:bg-white/10">
                                    <p class="text-sm font-semibold text-slate-100">Didn’t receive it?</p>
                                    <p class="mt-2 text-sm text-white/85">Use "Resend verification code" below, then stay on this page—the latest email always contains the valid OTP.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Parallax Script -->
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const container = document.getElementById('aurora-container');
                    if (!container) return;

                    const shapes = container.querySelectorAll('.parallax-shape');

                    container.addEventListener('mousemove', (e) => {
                        const { clientX, clientY } = e;
                        const centerX = container.offsetWidth / 2;
                        const centerY = container.offsetHeight / 2;

                        shapes.forEach(shape => {
                            const speed = parseFloat(shape.getAttribute('data-speed'));
                            const x = (clientX - centerX) * speed / 100;
                            const y = (clientY - centerY) * speed / 100;
                            
                            // Preserve existing transforms (like rotate) while adding translation
                            const currentTransform = shape.style.transform || '';
                            // Remove existing translations if any to avoid stacking
                            const cleanTransform = currentTransform.replace(/translate\([^)]*\)/g, '').trim();
                            
                            shape.style.transform = `${cleanTransform} translate(${x}px, ${y}px)`;
                        });
                    });
                    
                // Cleaned up broken script tag
                    });
                });
            </script>

            <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-white via-slate-100 to-slate-200 px-4 py-12 sm:px-6 lg:px-12">
                <div class="w-full {{ $cardWidth }} space-y-8">
                    <div class="flex justify-center lg:hidden">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 shadow-sm">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#16136a]/5">
                                <img src="{{ asset('logo.png') }}" alt="GESA logo" class="h-6 w-6 object-contain" loading="lazy">
                            </span>
                            <span class="text-xs font-semibold uppercase tracking-[0.22em] text-[#16136a]">GESA Student Portal</span>
                        </a>
                    </div>
                    {{ $slot ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('[data-auth-form]');
            const overlay = document.getElementById('auth-loading-overlay');
            forms.forEach((form) => {
                form.addEventListener('submit', () => {
                    if (!overlay) {
                        return;
                    }
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                });
            });
        });
    </script>
</body>
</html>
