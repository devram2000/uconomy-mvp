<x-jet-form-section submit="zelleUpdate">
    <x-slot name="title">
        {{ __('Zelle') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Uconomy Beta Testing currently transacts with Zelle.') }} </br>
        {{ __('Make sure your Zelle account is connected to the email or phone number
            inputted to your Uconomy account.') }}
    </x-slot>

    <x-slot name="form">
     

        <div class="col-span-6 sm:col-span-4">
            {{ __('Is your Zelle connected to your email or phone number?') }} </br>
            @error('zelle') <span id="error" class="text-danger">{{ $message }}</span> @enderror

            </br>

            <select name="zelle" id="zelle" placeholder='' wire:model="zelle">
                <option value=null selected="selected">{{ __('') }}</option>
                <option value="email">{{ __('Email: ') }}{{ $email }}</option>
                <option value="phone_number">        {{ __('Phone Number: ') }}{{ $phone_number }}</option>
            </select>
        </div>


    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
