<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <section >
     
                    <div id="identity"> 
                        <div id="identity-page" class="col-span-6 sm:col-span-4">
                            {{ __('What will you use to verify your identity?') }} </br>
                            <x-jet-input-error for="identity" class="mt-2" />

                            </br>

                            <select name="identity" id="identity-form" wire:model="identity" >
                                <option selected="selected">{{ __('') }}</option>
                                <option value="license">{{ __('Driver\'s License') }}</option>
                                <option value="identification">        {{ __('Identification Card') }}</option>
                                <option value="passport">        {{ __('Passport') }}</option>
                            </select></br>
                        </div>

                        @if($identity == "license")
                            Please upload photos of the front and back of your Driver's License.
                            <x-jet-secondary-button type="button" class="mt-4" wire:click="">
                                {{ __('Front Picture') }}
                            </x-jet-secondary-button>
                            <x-jet-secondary-button type="button" class="mt-2" wire:click="">
                                {{ __('Back Picture') }}
                            </x-jet-secondary-button>
                        @elseif($identity == "identification")
                            Please upload photos of the front and back of your Identification Card. 
                            <x-jet-secondary-button type="button" class="mt-4" wire:click="">
                                {{ __('Front Picture') }}
                            </x-jet-secondary-button>
                            <x-jet-secondary-button type="button" class="mt-2" wire:click="">
                                {{ __('Back Picture') }}
                            </x-jet-secondary-button>
                        @elseif($identity == "passport")
                            Please upload a picture of the Information Page of your passport (the page with your photo).
                            <x-jet-secondary-button type="button" class="mt-4" wire:click="">
                                {{ __('Information Page Picture') }}
                            </x-jet-secondary-button>
                        @endif
                    </div>

                    @if($identity != "selected" && $identity != NULL)
                        <div id="identity-submit">
                            <x-jet-button id="identity-submit-button" class="mt-4" wire:click="" type="button" >
                                {{ __('Submit') }}
                            </x-jet-button>          
                        <div>
                    @endif
                </section>

            </div>
        </div>
    </div>
</div>