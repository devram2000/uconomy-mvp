@push('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>



@endpush
<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <section id="card-payment">
                    @if(!$payment_completed)
                    <div>
                        <div id="payment-title"> 
                            {{ __('Make a Payment') }} 
                        </div> 
                        <div id="payment-text">
                            Fill out the form below to make a debit card payment
                        </div>
                        <div id="error"></div>


                    </div>


                    

                    <div id="card-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="card-holder-name" class="form-control" value="{{ Auth::user()->name }}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="payment_amount">Payment Amount ($)</label>
                            <input type="number" step="0.01"  placeholder='0.00'  id="payment_amount" class="form-control" wire:model="payment_amount">
                        </div>

                        <div class="form-group">
                            <label for="card_info">Debit Card Information</label>
                            <div id="card-element" class="form-control"  wire:ignore></div>
                        </div>


                        <div id="payment-button" class="mt-4">
                            <div>
                            <x-jet-button id="card-button" data-secret="{{ $intent->client_secret }}">
                                Confirm Payment
                            </x-jet-button></div>
                            <div><div id="loader" class="loader"></div></div>

                        </div>
                        
                    </div>

                    <div id="stripe-use" class="mt-2">
                        <a href="https://stripe.com/" target="_blank"> <img 
                        src="https://thumbs.bfldr.com/at/rvgw5pc69nhv9wkh7rw8ckv/v/34943915?expiry=1639713391&fit=bounds&height=800&sig=ZDRlYjU3MDU1Y2MzNTdiMmRkNTQwNmY0OTY3NzRjNzEwNDFhZjUxYQ%3D%3D&width=1100"
                        width=100px alt="Powered by Stripe" ></a>
                    </div>
                 

                    <script src="https://js.stripe.com/v3/"></script>

                    <script>
                        const stripe = Stripe("{{ env('STRIPE_KEY') }}");

                        const elements = stripe.elements();
                        const cardElement = elements.create('card');

                        cardElement.mount('#card-element');

                        const cardHolderName = document.getElementById('card-holder-name');
                        const cardButton = document.getElementById('card-button');
                        const loader = document.getElementById('loader');
                        const errorBox = document.getElementById('error');
                        // const errorCharge = document.getElementById('chargeError');
                        const clientSecret = cardButton.dataset.secret;

                        var getUrlParameter = function getUrlParameter(sParam) {
                            var sPageURL = window.location.search.substring(1),
                                sURLVariables = sPageURL.split('&'),
                                sParameterName,
                                i;

                            for (i = 0; i < sURLVariables.length; i++) {
                                sParameterName = sURLVariables[i].split('=');

                                if (sParameterName[0] === sParam) {
                                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                                }
                            }
                            return false;
                        };

                        var tech = getUrlParameter('message');
                        if (tech) {
                            errorBox.innerHTML = tech;
                        }





                        cardButton.addEventListener('click', async (e) => {
                            cardButton.style.display = 'none'; 
                            loader.style.display = 'block'; 
                            const { setupIntent, error } = await stripe.confirmCardSetup(
                                clientSecret, {
                                    payment_method: {
                                        card: cardElement,
                                        billing_details: { name: cardHolderName.value }
                                    }
                                }
                            );

                            if (error) {
                                cardButton.style.display = 'block'; 
                                loader.style.display = 'none'; 
                                errorBox.innerHTML = error.message;

                            } else {
                                Livewire.emit('setPaymentMethod', setupIntent.payment_method);
                                Livewire.emit('chargeCard');
                            }
                        });

                    </script>
                    @endif
                    @if($payment_completed)
                        <section id="upay-buy">
                            <div id="upay-title"> 
                                {{ __('You\'ve created a payment of $') }}{{ $payment_amount }}
                            </div>
                            <div id="transaction-agreement"> 
                                {{ __('Thank you!') }}</br>
                                {{ __('The payment you made is currently being processed.') }}
                            </div>
                            <div>
                                <x-jet-button id="upay-button" type="button" wire:click="redirectHome">
                                    {{ __('Return Home') }}
                                </x-jet-button>  
                            </div>
                        </section>
                        <div id="stripe-use">
                                <a href="https://stripe.com/" target="_blank"> <img 
                                src="https://thumbs.bfldr.com/at/rvgw5pc69nhv9wkh7rw8ckv/v/34943915?expiry=1639713391&fit=bounds&height=800&sig=ZDRlYjU3MDU1Y2MzNTdiMmRkNTQwNmY0OTY3NzRjNzEwNDFhZjUxYQ%3D%3D&width=1100"
                                width=100px alt="Powered by Stripe" ></a>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>