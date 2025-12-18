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
                        <ul class="space-y-3 text-sm text-green-800 list-none">
                            <li class="flex gap-3">
                                <i class="ri-checkbox-circle-fill text-green-600"></i>
                                <span>Your documentation has been received and will be reviewed by an administrator within <strong>24 to 48 hours</strong>.</span>
                            </li>
                            <li class="flex gap-3">
                                <i class="ri-mail-send-fill text-green-600"></i>
                                <span>You will receive an <strong>email notification</strong> immediately once your account is approved.</span>
                            </li>
                            <li class="flex gap-3">
                                <i class="ri-login-circle-fill text-green-600"></i>
                                <span>Once approved, you can log in to access your dashboard and manage your dues.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6">
                <div class="flex gap-4">
                    <i class="ri-question-line text-3xl text-blue-600 flex-shrink-0"></i>
                    <div class="space-y-2">
                        <h3 class="font-semibold text-blue-900">Need help or follow-up?</h3>
                        <p class="text-sm text-blue-800">
                            If your request hasn't been approved after <strong>48 hours</strong>, please reach out to the GESA executive committee or visit the departmental office for assistance.
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
