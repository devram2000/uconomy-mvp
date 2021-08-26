<x-jet-action-section>
    <x-slot name="title">
        {{ __('Verification') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Verify your email and/or phone number.') }}
    </x-slot>

    <x-slot name="content">
        <div id="verification" class="flex flex-wrap items-center">
            <div class="p-2">
                @if($this->user->email_verified_at == null) 
                    <x-jet-button type="button" wire:click="verifyEmail">
                        Verify Email
                    </x-jet-button>
                @else 
                <div class="flex justify-center mt-2">
                    <div class="flex items-center w-1/2 px-6 py-4 text-green-700 bg-green-100 rounded">
                        <span><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 " fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg></span>
                        <p class="ml-2 text-medium">Email Verified!</p>
                    </div>
                </div>
                
                
                    {{-- <div class="w-1/2 px-4 py-2 text-green-600 bg-green-100 rounded">
                        Email verified! 
                    </div> --}}
                @endif
            </div>

            <div class="p-2">
                @if($this->user->phone_verified_at == null) 
                    <x-jet-button type="button" wire:click="verifyPhone">
                        Verify Phone Number
                    </x-jet-button>
                @else 
                <div class="flex justify-center mt-2">
                    <div class="flex items-center w-1/2 px-6 py-4 text-green-700 bg-green-100 rounded">
                        <span><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 " fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg></span>
                        <p class="ml-2 text-medium">Phone Number Verified!</p>
                    </div>
                </div>
                @endif
            </div>

            
        </div>

      

    </x-slot>
    
</x-jet-action-section>
