@push('scripts')

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

      .sb-title-icon {
        position: relative;
        top: -5px;
      }

      .card-container {
        display: flex;
        /* justify-content: center; */
        height: 400px;
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
      
    </style>
    <script>
    "use strict";

    function initMap() {
      const componentForm = [
        'location',
        'locality',
        'administrative_area_level_1',
        'country',
        'postal_code',
      ];
      const autocompleteInput = document.getElementById('location');
      const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
        fields: ["address_components", "geometry", "name"],
        types: ["address"],
      });
      autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
          // User entered the name of a Place that was not suggested and
          // pressed the Enter key, or the Place Details request failed.
          window.alert('No details available for input: \'' + place.name + '\'');
          return;
        }
        fillInAddress(place);
      });

      function fillInAddress(place) {  // optional parameter
        const addressNameFormat = {
          'street_number': 'short_name',
          'route': 'long_name',
          'locality': 'long_name',
          'administrative_area_level_1': 'short_name',
          'country': 'long_name',
          'postal_code': 'short_name',
        };
        const getAddressComp = function (type) {
          for (const component of place.address_components) {
            if (component.types[0] === type) {
              return component[addressNameFormat[type]];
            }
          }
          return '';
        };
        document.getElementById('location').value = getAddressComp('street_number') + ' '
                  + getAddressComp('route');
        for (const component of componentForm) {
          // Location field is handled separately above as it has different logic.
          if (component !== 'location') {
            document.getElementById(component).value = getAddressComp(component);
          }
        }
      }
    }
    </script>

@endpush


<x-jet-form-section submit="addressUpdate">
    <x-slot name="title">
        {{ __('Address') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add your home address.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div class="card-container">
            <div class="panel">
                <!-- <div>
                    <img class="sb-title-icon" src="https://fonts.gstatic.com/s/i/googlematerialicons/location_pin/v5/24px.svg" alt="">
                    <span class="sb-title">Address Selection</span>
                </div> -->
                <input type="text" placeholder="Address" id="location" wire:model="address"/>
                @error('address') <span id="error" class="text-danger">{{ $message }}</span> @enderror

                <input type="text" placeholder="Apt, Suite, etc (optional)" wire:model="apt"/>
                
                <input type="text" placeholder="City" id="locality" wire:model="city"/>
                @error('city') <span id="error" class="text-danger">{{ $message }}</span> @enderror

                <div class="half-input-container">
                    <input type="text" class="half-input" placeholder="State/Province" id="administrative_area_level_1" wire:model="state"/>

                    <input type="text" class="half-input" placeholder="Zip/Postal code" id="postal_code" wire:model="zipCode"/>

                </div>
                @error('state') <span id="error" class="text-danger">{{ $message }}</span> @enderror

                @error('zipCode') <span id="error" class="text-danger">{{ $message }}</span> @enderror

                <input type="text" placeholder="Country" id="country" wire:model="country"/>
                @error('country') <span id="error" class="text-danger">{{ $message }}</span> @enderror

                

            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIug4lpstwXZ6M6I4mVdV2S7vZ3fEGUXY&libraries=places&callback=initMap&channel=GMPSB_addressselection_v1_cAC" async defer></script>
        </div>

    </x-slot>
    <x-slot name="actions">

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>
