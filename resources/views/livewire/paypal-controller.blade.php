
<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <div>
                    <h1>PayPal Checkout with Livewire</h1>

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button wire:click="createPayment" class="btn btn-primary">
                        Pay with PayPal
                    </button>
                </div>

                <script>
                    document.addEventListener('livewire:load', function () {
                        window.livewire.on('paymentApproved', function (approvalLink) {
                            window.location.href = approvalLink;
                        });
                    });
                </script>

            </div>
        </div>
    </div>
</div>