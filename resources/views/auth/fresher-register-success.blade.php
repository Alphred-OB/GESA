@php($title = 'Registration Submitted')

<x-layouts.auth :title="$title" card-width="max-w-2xl">
    <x-slot:hero>
        <div class="mx-auto w-full max-w-lg text-center">
            <div class="mx-auto flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg">
                <i class="ri-checkbox-circle-line text-4xl text-green-600"></i>
            </div>
            <h1 class="mt-8 text-3xl font-semibold tracking-tight text-white lg:text-4xl">Request Submitted</h1>
            <p class="mt-4 max-w-md text-base text-white/80 mx-auto">
                Your registration request has been received and is awaiting approval.
            </p>
        </div>
    </x-slot:hero>

    <div class="mx-auto w-full max-w-3xl rounded-3xl bg-white/95 p-10 shadow-xl ring-1 ring-black/5 backdrop-blur">
        <div class="space-y-6">
            <div class="rounded-xl border border-green-200 bg-green-50 p-6">
                <div class="flex gap-4">
                    <i class="ri-information-line text-3xl text-green-600 flex-shrink-0"></i>
                    <div class="space-y-3">
                        <h2 class="text-lg font-semibold text-green-900">What happens next?</h2>
                        <ol class="space-y-2 text-sm text-green-800 list-decimal list-inside">
                            <li>An administrator will review your registration request within <strong>24-48 hours</strong></li>
                            <li>Please visit the GESA office with:
                                <ul class="ml-6 mt-1 space-y-1 list-disc list-inside">
                                    <li><strong>Continuing students:</strong> Your Student ID card</li>
                                    <li><strong>Freshers:</strong> Your admission letter/forms</li>
                                </ul>
                            </li>
                            <li>Once verified, the admin will approve your account</li>
                            <li>You'll receive an <strong>email notification</strong> when your account is approved</li>
                            <li>After approval, you can log in using your username and password</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6">
                <div class="flex gap-4">
                    <i class="ri-question-line text-3xl text-blue-600 flex-shrink-0"></i>
                    <div class="space-y-2">
                        <h3 class="font-semibold text-blue-900">Need help?</h3>
                        <p class="text-sm text-blue-800">
                            If you have any questions about your registration, please contact the GESA executive committee or visit the departmental office.
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-6">
                <div class="flex gap-4">
                    <i class="ri-time-line text-3xl text-yellow-600 flex-shrink-0"></i>
                    <div class="space-y-2">
                        <h3 class="font-semibold text-yellow-900">Request taking too long?</h3>
                        <p class="text-sm text-yellow-800">
                            If your request hasn't been processed after 48 hours, please visit the GESA office during office hours to follow up.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center pt-4">
                <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 rounded-xl bg-[#16136a] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/30 transition duration-200 ease-out hover:-translate-y-0.5 hover:bg-[#18188a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#16136a]">
                    <i class="ri-home-line text-lg"></i>
                    <span>Back to Login</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.auth>
