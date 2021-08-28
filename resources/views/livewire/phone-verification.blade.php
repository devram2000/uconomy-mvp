<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            <p>{{ __('Thanks for signing up! ') }}</p> <br/>
            <p>{{ __('Before getting started, could you verify your phone number by entering the code sent to your phone number?') }}</p><br/>
            <p>{{ __('You should receive a text message to ') }} <strong>{{ $phone_number }}</strong> {{ __(' with the 4-digit verification code. You will also receive a phone call reading the code off to you after a few minutes without verification.') }}</p> <br/>
            {{-- <p>{{ __('You will also receive a phone call reading the code off to you after a few minutes without verification.') }}</p> <br/> --}}
            <p>{{ __('After 30 seconds, you can reset the verification process if you are having issues.') }}</p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form wire:submit.prevent="submit">
            @csrf

            <div class="block">
                <x-jet-label for="code" value="{{ __('Verification Code') }}" />
                <x-jet-input id="code" class="block mt-1 w-full" type="text" wire:model="code" />
            </div>

            

            <div class="flex items-center justify-end mt-3">
                <x-jet-button type="submit">
                    {{ __('Verify Code') }}
                </x-jet-button>
            </div>
        </form>

        <div class="flex items-center justify-end mt-1">
            {{-- <button wire:click="test" class="underline text-sm text-gray-600 hover:text-gray-900">
                {{ __('Reset Code') }}
            </button> --}}
            <form method="POST" action="{{ route('/reset') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Reset Code') }}
                </button>
            </form>
    
        </div>


    </x-jet-authentication-card>
    
</x-guest-layout>
