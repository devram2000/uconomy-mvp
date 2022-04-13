@push('scripts')
<script src="https://widgets.marqeta.com/marqetajs/1.1.0/marqeta.min.js" type="text/javascript"></script>
<style>
      body {
        margin: 0;
      }

      .sb-title {
        position: relative;
        top: -12px;
        font-family: Roboto, sans-serif;
        font-weight: 500;
      }

      #client_token {
          display: none;
      }

      .sb-title-icon {
        position: relative;
        top: -5px;
      }

      #card-container {
        display: flex;
        /* justify-content: center; */
        /* min-height: 600px; */
        width: 100%;
      }


      .panel {
        background: white;
        width: 80%;
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
      }

      .half-input-container {
        display: flex;
        justify-content: space-between;
      }

      .half-input {
        max-width: 50%;
      }

      h2 {
        margin: 0;
        font-family: Roboto, sans-serif;
      }

      input {
        height: 30px;
      }

      input {
        border: 0;
        border-bottom: 1px solid black;
        font-size: 14px;
        font-family: Roboto, sans-serif;
        font-style: normal;
        font-weight: normal;
      }

      input:focus::placeholder {
        color: white;
      }

      .button-cta {
        
        height: 40px;
        width: 100px;
        /* background: #3367d6;
        color: white;
        font-size: 15px;
        text-transform: uppercase;
        font-family: Roboto, sans-serif;
        border: 0;
        border-radius: 3px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.48); */
        cursor: pointer;
      }

      .button-holder {
          display: flex;
          justify-content: flex-end;
      }

      .together {
        display: flex;
        flex-flow: row nowrap;
        justify-content: space-between;
      }
      
    </style>
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
            {{ $message }}

            @if($pinWidgetURL != null)
                <iframe src={{ $cardWidgetURL }} title="Card Widget Url" />
            @endif

            @if($client_token != null)
            <div>
                <div id="client_token" > {{ $client_token }}</div>

                <div id="display-card-pan"></div>
                <div id="display-card-cvv"></div>
                <div id="display-card-exp"></div>
            </div>
            <script type="text/javascript">
                
                window.marqeta.bootstrap({
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

