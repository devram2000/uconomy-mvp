@push('scripts')
<script src="https://widgets.marqeta.com/marqetajs/1.1.0/marqeta.min.js" type="text/javascript"></script>
@endpush
<link rel="stylesheet" href="{{ asset('css/card.css') }}">

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

            <div class="m-2 flex flex-col">
                <div id="cardContainer">
                    <div id="cardInner" class="" >
                    <div id="cardFront" style="background-Color: #FFD100;" >
                        <div id="cardChipContainer">
                            <div id="cardVirtualChip">
                                <svg width="54" height="42" viewBox="0 0 54 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4 0C1.79086 0 0 1.79086 0 4V6V12V18V24V30V36V38C0 40.2091 1.79086 42 4 42H6H12H18H24H30H36H42H48V36H54V30H48V36H42V30H36V24H30V18L36 18V12H30V6H24H18V0H12H6H4ZM18 6H12V12H18V6ZM24 18L30 18V12H24V18ZM24 18H18V24H24V18ZM36 30H30V36L36 36V30Z" fill="#F3F4F6"/>
                                </svg>
                            </div>
                        </div>
                        <div id="cardType"  class="bg-black">
                            <span style="color: #FFD100;" >VIRTUAL CARD</span>
                        </div>
                        <div id="cardLastFour">
                        <span class="text-black">**** **** **** {{ $last_four }}</span>
                        </div>
                        <div id="cardEmbossName" >
                        <div class="flex5">
                            <span class="text-black">Test Test</span>
                            <span class="text-black">Uconomy</span>
                        </div>
                        </div>
                        <div id="cardNetwork">
                        <div id="mCLogo">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 152 108">
                                <g>
                                    <rect width="152.407" height="108" style="fill: none"/>
                                <g>
                                <rect x="60.4117" y="25.6968" width="31.5" height="56.6064" style="fill: #ff5f00"/>
                                <path d="M382.20839,306a35.9375,35.9375,0,0,1,13.7499-28.3032,36,36,0,1,0,0,56.6064A35.938,35.938,0,0,1,382.20839,306Z" transform="translate(-319.79649 -252)" style="fill: #eb001b"/>
                                <path d="M454.20349,306a35.99867,35.99867,0,0,1-58.2452,28.3032,36.00518,36.00518,0,0,0,0-56.6064A35.99867,35.99867,0,0,1,454.20349,306Z" transform="translate(-319.79649 -252)" style="fill: #f79e1b"/>
                                <path d="M450.76889,328.3077v-1.1589h.4673v-.2361h-1.1901v.2361h.4675v1.1589Zm2.3105,0v-1.3973h-.3648l-.41959.9611-.41971-.9611h-.365v1.3973h.2576v-1.054l.3935.9087h.2671l.39351-.911v1.0563Z" transform="translate(-319.79649 -252)" style="fill: #f79e1b"/>
                                </g>
                            </g>
                            </svg>
                        </div>
                    </div>
                    </div>
                    <div id="cardBack" class="bg-white">
                    <div id="cardStripe"></div>
                        <div id="cardPAN">
                        <div class="flex5">
                            <span class="text-gray-800 text-xs font-bold">PAN</span>
                            
                            <div id="display-card-pan"></div>
         
                        </div>
                    </div>
                        <div id="cardCVV">
                        <div class="flex5">
                            <span class="text-gray-800 text-xs font-bold">CVV</span>
                      
                            <div id="display-card-cvv"></div>
                        </div>
                    </div>
                        <div id="cardExpiryDate">
                        <div class="flex5">
                            <span class="text-gray-800 text-xs  font-bold">
                            Expiry Date
                            </span>
                            <div id="display-card-exp"></div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>

                <div class="mt-8">
                    <button id="flippedBtn" type="button" onClick="switchClass()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Toggle
                    </button>
                </div>
                </div>

                <script>

                    function switchClass() {
                        console.log('hello');
                        if ( document.getElementById("cardInner").className == "") {
                            document.getElementById("cardInner").className = "flipped";
                        } else {
                            document.getElementById("cardInner").className = "";
                            console.log('bye');
                        }
                    }
                </script>



            @if($pinWidgetURL != null)
                <iframe src={{ $cardWidgetURL }} title="Card Widget Url" />
            @endif

            <div>
                <div id="client_token" > {{ $client_token }}</div>

                Balance: ${{ $balance }}
            </div>
            <script>
                
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

