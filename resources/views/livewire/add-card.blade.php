@if($is_card_added == NULL)

<x-jet-form-section submit="createCard">
    <x-slot name="title">
        {{ __('Add Payment Method') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Please input your Debit/Credit Card information. Submit any inputs for testing purposes.') }}
    </x-slot>

    <x-slot name="form">
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="name" value="{{ __('Cardholder Full Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" />
                    <x-jet-input-error for="name" class="mt-2" />

                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="card_number" value="{{ __('Card Number') }}" />
                    <x-jet-input id="card_number" type="text" class="mt-1 block w-full" />
                    <x-jet-input-error for="card_number" class="mt-2" />

                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="expiry" value="{{ __('Card Expiry Date') }}" />
                    <x-jet-input id="expiry" type="date" class="mt-1 block w-full" />
                    <x-jet-input-error for="expiry" class="mt-2" />

                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="zip" value="{{ __('Card Zip Code') }}" />
                    <x-jet-input id="zip" type="text" class="mt-1 block w-full" />
                    <x-jet-input-error for="zip" class="mt-2" />

                </div>
    </x-slot>
    <x-slot name="actions">
        <x-jet-button>
            {{ __('Submit') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>


@else
<x-jet-action-section>
<x-slot name="title">
        {{ __('Add Payment Method') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Please input your Debit/Credit Card information. Submit any inputs for testing purposes.') }}
    </x-slot>

    <x-slot name="content">
        <div id="verification" id="verification" class="">
            <div class="p-2">
                    <div class="flex items-center px-6 py-4">
                        <span><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 " fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg></span>
                        <p class="ml-2 text-medium">Card Added!</p>
                    </div>            
                </div>

    </x-slot> 
    </x-jet-action-section>
@endif
