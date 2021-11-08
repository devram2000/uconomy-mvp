
<section id="upay">
    <section id="upay-buy">
        <div id="upay-title"> 
            {{ __('UPay') }}
        </div>

        <div> 
            {{ __('Please agree to our Terms of Service and Privacy Policy') }}
        </div>

        <div class="mt-4">
            <x-jet-label for="agreements">
                <div class="flex items-center">
                    <x-jet-checkbox name="agreements" id="agreements" wire:model="agreements"/>

                    <div class="ml-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-jet-label>
        </div>

        <x-jet-input-error for="agreements"/>


        <x-jet-button id="upay-button" type="button" wire:click="termsSubmit">
            {{ __('Continue') }}
        </x-jet-button>
        
    </section>
    
</div>    



