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

      #card-container {
        display: flex;
        /* justify-content: center; */
        min-height: 600px;
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

<x-jet-form-section submit="createCustomer">
    <x-slot name="title">
        {{ __('KYC') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a customer and do a KYC verification.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div id="card-container">
            <div class="panel">

                <input type="text" placeholder="First Name" id="first_name" wire:model="first_name"/>
                <x-jet-input-error for="first_name" class="mt-2" />

                <input type="text" placeholder="Last Name" id="last_name" wire:model="last_name"/>
                <x-jet-input-error for="last_name" class="mt-2" />

                <input type="date" placeholder="Date of Birth" id="date_of_birth" wire:model="date_of_birth"/>
                <x-jet-input-error for="date_of_birth" class="mt-2" />

                <input type="text" placeholder="Address Line 1" id="address" wire:model="address"/>
                <x-jet-input-error for="address" class="mt-2" />

                <input type="text" placeholder="Address Line 2" id="address2" wire:model="address2"/>
                <x-jet-input-error for="address2" class="mt-2" />

                <input type="text" placeholder="City" id="locality" wire:model="city"/>
                <x-jet-input-error for="city" class="mt-2" />

                <input type="text" placeholder="State" id="locality" wire:model="state"/>
                <x-jet-input-error for="state" class="mt-2" />

                <input type="text" placeholder="Postal Code" id="locality" wire:model="zip"/>
                <x-jet-input-error for="zip" class="mt-2" />

                <input type="text" placeholder="SSN" id="locality" wire:model="ssn"/>
                <x-jet-input-error for="ssn" class="mt-2" />

            </div>
        </div>
        </div>
        {{ $message }}

    </x-slot>
    <x-slot name="actions">
   
        
        <x-jet-button>
            {{ __('Submit') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>
