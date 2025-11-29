@props(['title' => null])

<x-layouts.dashboard :title="$title">
    <x-slot:header>
        <x-admin.header />
    </x-slot:header>

    <x-slot:sidebar>
        <x-admin.sidebar />
    </x-slot:sidebar>

    <x-slot:footer>
        <x-admin.footer />
    </x-slot:footer>

    @if (session('role_status'))
        <div class="mx-auto w-full max-w-6xl px-5 pt-4 sm:px-6 lg:px-8">
            <div class="mb-4 rounded-3xl border border-emerald-200/60 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-inner">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <i class="ri-check-line text-lg" aria-hidden="true"></i>
                    </span>
                    <p>{{ session('role_status') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{ $slot }}
</x-layouts.dashboard>
