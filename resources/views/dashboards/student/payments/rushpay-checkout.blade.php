<x-layouts.dashboard :title="'Secure Checkout'">
    <div class="flex min-h-[60vh] flex-col items-center justify-center px-4 py-12">
        <div class="w-full max-w-md space-y-8 text-center">
            {{-- Visual Indicator --}}
            <div class="relative mx-auto h-24 w-24">
                <div class="absolute inset-0 animate-ping rounded-full bg-[#16136a]/10"></div>
                <div class="relative flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-xl shadow-[#16136a]/10 ring-1 ring-slate-100">
                    <x-heroicon-o-shield-check class="text-4xl text-[#16136a] size-5" />
                </div>
            </div>

            <div class="space-y-3">
                <h1 class="text-3xl font-semibold tracking-tight text-[#16136a]">Secure Checkout</h1>
                <p class="text-sm font-semibold text-slate-500">
                    Preparing your payment for <span class="text-slate-900">{{ $due->description }}</span>
                </p>
            </div>

            {{-- Summary Card --}}
            <div class="overflow-hidden rounded-xl border border-slate-100 bg-white p-8 shadow-2xl shadow-[#16136a]/5">
                <div class="flex flex-col items-center justify-center space-y-6">
                    <div class="text-center">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400">Total Amount</p>
                        <p class="mt-2 text-5xl font-semibold tracking-tighter text-[#16136a]">
                            <span class="text-2xl">GHS</span> {{ number_format($due->amount, 2) }}
                        </p>
                    </div>

                    <div class="h-px w-full bg-slate-50"></div>

                    <div class="w-full space-y-3 text-left">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-400 uppercase tracking-widest">Reference</span>
                            <span class="font-semibold text-slate-900">{{ $reference }}</span>
                        </div>
                    </div>

                    {{-- Action Button / Loading State --}}
                    <div id="rushpay-button-container" class="w-full">
                        <button id="start-payment-btn" class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#16136a] py-5 text-xs font-semibold uppercase tracking-[0.2em] text-white shadow-xl shadow-[#16136a]/20 transition-all hover:-translate-y-1 hover:bg-[#1c1c8a] active:scale-95 disabled:opacity-50">
                            <span id="btn-text">Initializing Widget...</span>
                            <x-heroicon-o-arrow-path id="btn-icon" class="animate-spin size-5" />
                        </button>
                    </div>
                </div>
            </div>

            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                Secured by <span class="text-[#16136a]">RushPay</span> Fintech
            </p>
        </div>
    </div>

    {{-- RushPay Widget Script --}}
    <script src="https://api.rushpay.cash/widget.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('start-payment-btn');
            const btnText = document.getElementById('btn-text');
            const btnIcon = document.getElementById('btn-icon');

            function startPayment() {
                if (typeof RushPay === 'undefined') {
                    console.error('RushPay widget script not loaded');
                    btnText.innerText = 'Error Loading Widget';
                    return;
                }

                btnText.innerText = 'Opening Widget...';

                const rushpay = new RushPay({
                    token: '{{ $token }}',
                    reference: '{{ $reference }}',
                    onClose: function() {
                        // User closed the widget
                        window.location.reload();
                    },
                    onCompleted: function(response) {
                        // Success! Redirect to callback
                        const callbackUrl = new URL('{{ route("student.payments.rushpay.callback") }}');
                        callbackUrl.searchParams.append('reference', '{{ $reference }}');
                        window.location.href = callbackUrl.href;
                    },
                    onError: function(error) {
                        console.error('RushPay Error:', error);
                        alert('Payment failed: ' + (error.message || 'Unknown error'));
                        window.location.href = '{{ route("student.dues.index") }}';
                    }
                });

                rushpay.open();
            }

            // Auto-start after a brief delay for effect
            setTimeout(() => {
                btnText.innerText = 'Pay GHS {{ number_format($due->amount, 2) }} Now';
                btnIcon.className = 'ri-arrow-right-line text-lg';
                startPayment();
            }, 1500);

            btn.addEventListener('click', startPayment);
        });
    </script>
</x-layouts.dashboard>
