<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            <p>{{ __('Thanks for signing up! ') }}</p> <br/>
            <p>{{ __('Before getting started, could you verify your phone number by entering the code sent to your phone number?') }}</p><br/>
            <p>{{ __('If you didn\'t receive the verification code, we will gladly send you another.') }}</p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('nexmo') }}">
            @csrf

            <div class="block">
                {{$phone_number}}
                <x-jet-label for="code" value="{{ __('Verification Code') }}" />
                <x-jet-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Verify Code') }}
                </x-jet-button>
            </div>
        </form>
        
        @if($error)
            <h4>Incorrect code. Please try again!</h4>
        @endif

    </x-jet-authentication-card>
</x-guest-layout>
