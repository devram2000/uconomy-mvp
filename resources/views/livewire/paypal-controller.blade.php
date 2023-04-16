<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <div class="text-center">
                    <h1 class="text-3xl font-bold mb-4">Checkout</h1>

                    <p class="mb-2">
                        A 3% fee will apply to the payment amount moved.
                    </p>
                    <p class="mb-4">
                        Rest assured, if we are unable to accommodate your date change, you will receive a complete refund.
                    </p>

                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @elseif (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                        <button wire:click="redirectHome" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Home
                        </button>
                    
                    @endif

                    <button wire:click="createPayment" wire:loading.remove @if($isButtonDisabled) style="display:none;" @endif class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Pay with PayPal
                    </button>
                    <div class="mt-2 text-gray-700" wire:loading>
                        {{ __('Loading...') }}
                    </div>

                </div>

                <script>
                    document.addEventListener('livewire:load', function () {
                        window.livewire.on('paymentApproved', function (approvalLink) {
                            window.location.href = approvalLink;
                        });

                        window.livewire.on('paymentCancelled', function () {
                            alert('Payment cancelled.');
                        });
                    });


                </script>
              


            </div>
        </div>
    </div>
</div>
