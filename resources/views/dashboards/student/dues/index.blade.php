<x-layouts.dashboard :title="$title">
    <div class="mx-auto w-full max-w-2xl px-5 py-12 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600 mx-auto">
                <i class="ri-tools-line text-2xl"></i>
            </div>
            
            <div class="space-y-3">
                <h1 class="text-2xl font-bold text-slate-900">Payment System Maintenance</h1>
                <p class="text-slate-600">Online payments are temporarily unavailable. Please contact the finance office for payment arrangements.</p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left">
                <div class="flex items-start gap-3">
                    <i class="ri-information-line text-amber-600 mt-0.5"></i>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold">Finance Office</p>
                        <p>Mon-Fri: 8:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-[#16136a] px-5 py-2 text-sm font-semibold text-white transition hover:bg-[#18188a]">
                <i class="ri-arrow-left-line"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</x-layouts.dashboard>
