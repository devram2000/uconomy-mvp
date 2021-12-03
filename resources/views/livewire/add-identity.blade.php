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
     
                    <div id="identity" x-data=""> 
                        <div id="identity-page" class="col-span-6 sm:col-span-4">
                            {{ __('What will you use to verify your identity?') }} </br>
                            <x-jet-input-error for="identity" class="mt-2" />

                            </br>

                            <select name="identity" id="identity-form" wire:model="identity" wire:change="removePhotos">
                                <option selected="selected">{{ __('') }}</option>
                                <option value="license">{{ __('Driver\'s License') }}</option>
                                <option value="identification">        {{ __('Identification Card') }}</option>
                                <option value="passport">        {{ __('Passport') }}</option>
                            </select></br>
                        </div>

                        @if($identity == "license")
                            
                            Please upload photos of the front and back of your Driver's License.
                            <input type="file" class="hidden" class="mt-4" wire:model="front_photo" x-ref="dl1" >
                            </input>
                            <x-jet-secondary-button class="mt-4" type="button" x-on:click.prevent="$refs.dl1.click()">
                                {{ __('Front Picture') }}
                            </x-jet-secondary-button>
                            @if ($front_photo != NULL )
                                <div class="mt-2">
                                    {{ __('Front Picture Uploaded!') }}
                                </div>
                            @endif
                            <x-jet-input-error for="front_photo" class="mt-2" />
                            
                            <input type="file" class="hidden" class="mt-4" wire:model="back_photo" x-ref="dl2" >
                            </input>
                            <x-jet-secondary-button class="mt-2" type="button" x-on:click.prevent="$refs.dl2.click()">
                                {{ __('Back Picture') }}
                            </x-jet-secondary-button>
                            @if ($back_photo != NULL ) 
                                <div class="mt-2">
                                    {{ __('Back Picture Uploaded!') }}
                                </div>
                            @endif

                            <x-jet-input-error for="back_photo" class="mt-2" />


                        @elseif($identity == "identification")
                            Please upload photos of the front and back of your Identification Card. 
                            <input type="file" class="hidden" class="mt-4" wire:model="front_photo" x-ref="id1" >
                            </input>
                            <x-jet-secondary-button class="mt-4" type="button" x-on:click.prevent="$refs.id1.click()">
                                {{ __('Front Picture') }}
                            </x-jet-secondary-button>
                            @if ($front_photo != NULL )
                                <div class="mt-2">
                                    {{ __('Front Picture Uploaded!') }}
                                </div>
                            @endif
                            <x-jet-input-error for="front_photo" class="mt-2" />
                            
                            <input type="file" class="hidden" class="mt-4" wire:model="back_photo" x-ref="id2" >
                            </input>
                            <x-jet-secondary-button class="mt-2" type="button" x-on:click.prevent="$refs.id2.click()">
                                {{ __('Back Picture') }}
                            </x-jet-secondary-button>
                            @if ($back_photo != NULL ) 
                                <div class="mt-2">
                                    {{ __('Back Picture Uploaded!') }}
                                </div>
                            @endif

                            <x-jet-input-error for="back_photo" class="mt-2" />

                        @elseif($identity == "passport")
                            Please upload a picture of the Information Page of your passport (the page with your photo).
                            <input type="file" class="hidden" class="mt-4" wire:model="front_photo" x-ref="pp1" >
                            </input>
                            <x-jet-secondary-button class="mt-4" type="button" x-on:click.prevent="$refs.pp1.click()">
                                {{ __('Information Page Picture') }}
                            </x-jet-secondary-button>
                            @if ($front_photo != NULL )
                                <div class="mt-2">
                                    {{ __('Information Page Picture Uploaded!') }}
                                </div>
                            @endif
                            <x-jet-input-error for="front_photo" class="mt-2" />
                        @endif
                    </div>

                    @if($identity != "selected" && $identity != NULL)
                        <div id="identity-submit">
                            <x-jet-button id="identity-submit-button" class="mt-4" wire:click="submitIdentification" type="button" >
                                {{ __('Submit') }}
                            </x-jet-button>          
                        <div>
                    @endif
                </section>

            </div>
        </div>
    </div>
</div>