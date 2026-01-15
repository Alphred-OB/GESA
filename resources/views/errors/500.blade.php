<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error | GESA Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .bg-mesh {
            background-color: #0c0b2d;
            background-image: 
                radial-gradient(at 0% 0%, hsla(242, 60%, 15%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(230, 40%, 12%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(242, 60%, 15%, 1) 0, transparent 50%), 
                radial-gradient(at 0% 100%, hsla(242, 60%, 15%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 100%, hsla(230, 40%, 12%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(242, 60%, 15%, 1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-6 text-white font-sans overflow-hidden">
    
    <!-- Background Accents -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-900/10 rounded-full blur-[120px] animate-pulse"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-900/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="relative w-full max-w-lg animate-fade-slide">
        <div class="glass-card rounded-[40px] p-10 md:p-16 text-center shadow-2xl overflow-hidden relative border-red-500/10">
            
            <!-- Icon Section -->
            <div class="mb-10 relative animate-fade-slide-delay-200">
                <div class="float-animation inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-red-600 to-orange-600 shadow-xl shadow-red-900/40 border border-white/10">
                    <i class="ri-error-warning-line text-5xl text-white"></i>
                </div>
                <!-- Shimmer effect -->
                <div class="absolute inset-0 bg-red-500/10 blur-3xl rounded-full scale-150 -z-10 animate-pulse"></div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight animate-fade-slide-delay-400">Server Error</h1>
            
            <p class="text-slate-400 text-lg mb-10 leading-relaxed font-medium animate-fade-slide-delay-600">
                Something went wrong on our end. We're already looking into it. Please try again in a moment.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-slide-delay-600">
                <button 
                    onclick="window.location.reload()" 
                    class="group relative w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-slate-900 px-8 py-4 rounded-2xl font-bold text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/10"
                >
                    <i class="ri-refresh-line text-lg group-hover:rotate-180 transition-transform duration-500"></i>
                    Try Again
                </button>

                <a 
                    href="{{ url('/') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border border-white/10 px-8 py-4 rounded-2xl font-bold text-sm uppercase tracking-widest transition-all hover:bg-white/20 hover:scale-105 active:scale-95"
                >
                    <i class="ri-home-4-line text-lg"></i>
                    Go Home
                </a>
            </div>

            <!-- Decorative Elements -->
            <div class="mt-12 pt-8 border-t border-white/5 opacity-50">
                <div class="flex items-center justify-center gap-2 text-slate-500 text-sm font-semibold">
                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-6 w-6 grayscale opacity-50">
                    <span>GESA Portal Systems</span>
                </div>
            </div>

        </div>

        <!-- Hint -->
        <p class="mt-8 text-center text-slate-500 text-xs font-semibold uppercase tracking-[0.3em] opacity-40">
            Error Code: 500 Internal Server Error
        </p>
    </div>

</body>
</html>
