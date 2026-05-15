<footer class="mt-auto border-t border-slate-200 bg-white/50 backdrop-blur-sm">
    <div class="mx-auto w-full max-w-full px-8 py-10">
        <div class="flex flex-col gap-10 md:flex-row md:items-center md:justify-between">
            {{-- Brand & Description --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="GESA" class="h-full w-full object-contain" loading="lazy">
                    </div>
                    <span class="text-base font-semibold tracking-tighter text-slate-900">GESA <span class="font-semibold text-slate-400">Portal</span></span>
                </div>
                <p class="max-w-xs text-xs font-medium leading-relaxed text-slate-500">
                    Empowering Geomatic Engineering students with digital tools for academic excellence.
                </p>
            </div>

            {{-- Simplified Links --}}
            <nav class="flex flex-wrap items-center gap-x-8 gap-y-4">
                <a href="{{ route('student.dashboard') }}" class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 transition hover:text-[#16136a]">Dashboard</a>
                <a href="{{ route('student.announcements.index') }}" class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 transition hover:text-[#16136a]">Notices</a>
                <a href="{{ route('student.events.index') }}" class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 transition hover:text-[#16136a]">Events</a>
                <a href="{{ route('student.resources.index') }}" class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 transition hover:text-[#16136a]">Resources</a>
                <span class="hidden h-4 w-px bg-slate-200 md:block"></span>
                <a href="mailto:support@gesa.edu" class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-[#16136a] transition hover:opacity-70">
                    <i class="ri-mail-send-line text-sm"></i>
                    <span>Support</span>
                </a>
            </nav>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-6 border-t border-slate-100 pt-8 md:flex-row">
            <div class="flex items-center gap-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                <span>© {{ now()->year }} GESA Community</span>
            </div>
            
            <div class="flex items-center gap-6 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                <a href="{{ route('legal.privacy') }}" class="hover:text-[#16136a]">Privacy Policy</a>
                <a href="{{ route('legal.terms') }}" class="hover:text-[#16136a]">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>
