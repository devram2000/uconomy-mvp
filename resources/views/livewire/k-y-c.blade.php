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
        height: 42px;
      }

      select {
        height: 42px;
      }

      #disabled {
        color: grey;
      }

      input {
        /* border: 0; */
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

<x-jet-form-section submit="createPerson">
    <x-slot name="title">
        {{ __('Identity Verification') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Please input your information to have your identity verified using Socure\'s secure KYC verification.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">

        <div id="card-container">
            <div class="panel">

                <input type="text" placeholder="Address Line 1" id="address" wire:model="address"/>
                <x-jet-input-error for="address" class="mt-2" />

                <input type="text" placeholder="Address Line 2" id="address2" wire:model="address2"/>
                <x-jet-input-error for="address2" class="mt-2" />

                <input type="text" placeholder="City" id="locality" wire:model="city"/>
                <x-jet-input-error for="city" class="mt-2" />

                <select type="text" name="state" id="" wire:model="state">
                  <option value="" id='disabled' class='text-gray'>Select Your State...</option>
                  <option value="AK">Alaska</option>
                  <option value="AL">Alabama</option>
                  <option value="AR">Arkansas</option>
                  <option value="AS">American Samoa</option>
                  <option value="AZ">Arizona</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="CT">Connecticut</option>
                  <option value="DC">District of Colombia</option>
                  <option value="DE">Delaware</option>
                  <option value="FL">Florida</option>
                  <option value="FM">Federated States</option>
                  <option value="GA">Georgia</option>
                  <option value="GU">Guam</option>
                  <option value="HI">Hawaii</option>
                  <option value="IA">Iowa</option>
                  <option value="ID">Idaho</option>
                  <option value="IL">Illinois</option>
                  <option value="IN">Indiana</option>
                  <option value="KS">Kansas</option>
                  <option value="KY">Kentucky</option>
                  <option value="LA">Louisiana</option>
                  <option value="MA">Massachusetts</option>
                  <option value="MD">Maryland</option>
                  <option value="ME">Maine</option>
                  <option value="MI">Michigan</option>
                  <option value="MN">Minnesota</option>
                  <option value="MO">Missouri</option>
                  <option value="MS">Mississippi</option>
                  <option value="MT">Montana</option>
                  <option value="NC">North Carolina</option>
                  <option value="ND">North Dakota</option>
                  <option value="NE">Nebraska</option>
                  <option value="NH">New Hampshire</option>
                  <option value="NJ">New Jersey</option>
                  <option value="NM">New Mexico</option>
                  <option value="NV">Nevada</option>
                  <option value="NY">New York</option>
                  <option value="OH">Ohio</option>
                  <option value="OK">Oklahoma</option>
                  <option value="OR">Oregon</option>
                  <option value="PA">Pennsylvania</option>
                  <option value="PR">Puerto Rico</option>
                  <option value="RI">Rhode Island</option>
                  <option value="SC">South Carolina</option>
                  <option value="SD">South Dakota</option>
                  <option value="TN">Tennessee</option>
                  <option value="TX">Texas</option>
                  <option value="UT">Utah</option>
                  <option value="VI">Virgin Islands</option>
                  <option value="VT">Vermont</option>
                  <option value="VA">Virginia</option>
                  <option value="WA">Washington</option>
                  <option value="WI">Wisconsin</option>
                  <option value="WV">West Virginia</option>
                  <option value="WY">Wyoming</option>    
                  </select>
                <x-jet-input-error for="state" class="mt-2" />

                <input type="text" placeholder="Postal Code" id="locality" wire:model="zip"/>
                <x-jet-input-error for="zip" class="mt-2" />

                <input type="text" placeholder="SSN (XXX-XX-XXXX)" id="locality" wire:model="ssn" pattern="[0-9]{3}-[0-9]{2}-[0-9]{4}"/>
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
