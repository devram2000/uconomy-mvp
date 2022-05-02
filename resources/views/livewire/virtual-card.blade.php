@push('scripts')
<script src="https://widgets.marqeta.com/marqetajs/1.1.0/marqeta.min.js" type="text/javascript"></script>
@endpush

<x-jet-form-section submit="createCard">
    <x-slot name="title">
        {{ __('Virtual Card') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a Virtual Card.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div id="card-container">
            <div class="panel">

            @if($pinWidgetURL != null)
                <iframe src={{ $cardWidgetURL }} title="Card Widget Url" />
            @endif

            @if($client_token != null)
            <div>
                <div id="client_token" > {{ $client_token }}</div>

                Card Number: <div id="display-card-pan"></div>
                Card CVV: <div id="display-card-cvv"></div>
                Card Expiration: <div id="display-card-exp"></div>
                Balance: ${{ $balance }}
            </div>
            <script type="text/javascript">
                
                window.marqeta.bootstrap({
                    // clientAccessToken: @this.client_token,
                    clientAccessToken: document.getElementById("client_token").innerHTML,
                    integrationType: "custom",
                    showPan: {
                        cardPan: { 
                            domId: "display-card-pan", 
                            format: true, 
                        },
                        cardExp: { domId: "display-card-exp", format: true },
                        cardCvv: { domId: "display-card-cvv" },
                    },
                    callbackEvents: {
                        onSuccess: () => console.log("Widget loaded!"),
                        onFailure: () => console.warn("Widget failed to load."),
                    },
                });


            </script>
            @endif

            {{ $message }}


            </div>
        </div>
        </div>
        
        



    </x-slot>
    <x-slot name="actions">
   
        
        <x-jet-button>
            {{ __('Submit') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>

