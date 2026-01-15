<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired | GESA Portal</title>
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
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-[#16136a]/30 rounded-full blur-[120px] animate-pulse"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#4f46e5]/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

    <div class="relative w-full max-w-lg animate-fade-slide">
        <div class="glass-card rounded-[40px] p-10 md:p-16 text-center shadow-2xl overflow-hidden relative">
            
            <!-- Abstract background pattern -->
            <div class="absolute inset-0 opacity-5 pointer-events-none grayscale invert" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23bbafff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <!-- Icon Section -->
            <div class="mb-10 relative animate-fade-slide-delay-200">
                <div class="float-animation inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-[#16136a] to-blue-600 shadow-xl shadow-blue-900/40 border border-white/10">
                    <i class="ri-time-line text-5xl text-white"></i>
                </div>
                <!-- Shimmer effect -->
                <div class="absolute inset-0 bg-white/5 blur-3xl rounded-full scale-150 -z-10 animate-pulse"></div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight animate-fade-slide-delay-400">Page Expired</h1>
            
            <p class="text-slate-400 text-lg mb-10 leading-relaxed font-medium animate-fade-slide-delay-600">
                Your session has timed out due to inactivity or your browser's security token has expired.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-slide-delay-600">
                <button 
                    onclick="window.location.reload()" 
                    class="group relative w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-[#16136a] px-8 py-4 rounded-2xl font-bold text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/10"
                >
                    <i class="ri-refresh-line text-lg group-hover:rotate-180 transition-transform duration-500"></i>
                    Refresh Page
                </button>

                <a 
                    href="{{ route('login') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#16136a]/40 backdrop-blur-md border border-white/10 px-8 py-4 rounded-2xl font-bold text-sm uppercase tracking-widest transition-all hover:bg-[#16136a]/60 hover:scale-105 active:scale-95"
                >
                    <i class="ri-login-box-line text-lg"></i>
                    Go to Login
                </a>
            </div>

            <!-- Decorative Elements -->
            <div class="mt-12 pt-8 border-t border-white/5 opacity-50">
                <div class="flex items-center justify-center gap-2 text-slate-500 text-sm font-semibold">
                    <img src="{{ asset('logo.png') }}" alt="GESA" class="h-6 w-6 grayscale opacity-50">
                    <span>GESA Portal Security</span>
                </div>
            </div>

        </div>

        <!-- Hint -->
        <p class="mt-8 text-center text-slate-500 text-xs font-semibold uppercase tracking-[0.3em] opacity-40">
            Error Code: 419 Page Expired
        </p>
    </div>

</body>
</html>
