@props(['cardWidth' => 'max-w-md'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($title ?? '') ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
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

                <!-- Interactive Glass Shapes -->
                <div class="parallax-shape absolute left-20 top-40 z-0 h-32 w-32 rounded-full border border-white/10 bg-gradient-to-br from-white/10 to-transparent shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] backdrop-blur-md" data-speed="2"></div>
                
                <div class="parallax-shape absolute bottom-40 right-20 z-0 h-48 w-48 rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/10 shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] backdrop-blur-sm" data-speed="-1.5" style="transform: rotate(45deg);"></div>
                
                <div class="parallax-shape absolute bottom-20 left-1/3 z-0 h-16 w-16 rounded-full border border-white/20 bg-gradient-to-b from-white/20 to-transparent shadow-lg backdrop-blur-md" data-speed="3"></div>

                <div class="parallax-shape absolute top-20 right-1/3 z-0 h-24 w-24 rounded-lg border border-white/5 bg-white/5 backdrop-blur-[2px]" data-speed="1" style="transform: rotate(-15deg);"></div>

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
                    
                    // Add subtle floating animation via CSS
                    const style = document.createElement('style');
                    style.innerHTML = `
                        @keyframes float {
                            0% { transform: translateY(0px) rotate(0deg); }
                            50% { transform: translateY(-10px) rotate(2deg); }
                            100% { transform: translateY(0px) rotate(0deg); }
                        }
                        .parallax-shape {
                            transition: transform 0.1s ease-out;
                            animation: float 6s ease-in-out infinite;
                        }
                        .parallax-shape:nth-child(2n) {
                            animation-duration: 8s;
                            animation-delay: -2s;
                        }
                        .parallax-shape:nth-child(3n) {
                            animation-duration: 10s;
                            animation-delay: -5s;
                        }
                    `;
                    document.head.appendChild(style);
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
