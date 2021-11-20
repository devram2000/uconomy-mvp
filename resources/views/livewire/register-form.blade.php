<div >
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: space-evenly; width: auto;">
        <div  style="display: flex; flex-flow: row wrap; justify-content: space-evenly; width: 100%;">
            <div class="mt-4" >
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input style="width: 300px;" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4" style="">
                <x-jet-label for="date_of_birth" value="{{ __('Date of Birth') }}" />
                <x-jet-input style="width: 300px;" id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
            </div>

   
        </div>
        <div  style="display: flex; flex-flow: row wrap; justify-content: space-evenly; width: 100%;">
   

            <div class="mt-4" style="" class="">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input style="width: 300px;" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4" style="" class=" ">
                <x-jet-label for="phone_number" value="{{ __('Phone Number') }}" />
                <x-jet-input style="width: 300px;" id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone_number')" required />
            </div>
        </div>
        <div  style="display: flex; flex-flow: row wrap; justify-content: space-evenly; width: 100%;">

            


            <div class="mt-4" style="" class="">
                <x-jet-label for="password"  value="{{ __('Password') }}" />
                <x-jet-input style="width: 300px;" class="block mt-1 w-full" type="{{ $password_show }}" name="password"    />

            </div>


            <div  class="mt-4" style="" class="">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input style="width: 300px;" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
        </div>
    </div>


{{-- 
            @if ($password_show == 'password')
                <button class="ml-2 text-sm text-gray-600" wire:click="$set('password_show', 'text')">Show Password</button>
            @else
                <button class="ml-2 text-sm text-gray-600" wire:click="$set('password_show', 'password')">Hide Password</button>
            @endif --}}

    
    {{-- <input type="checkbox" id="checkbox">Show Password --}}



    <!-- <div class="block mt-4">
        <label for="remember_me" class="flex items-center">
            <x-jet-checkbox id="remember_me" name="remember" />
            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
    </div> -->

    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4"  style="display: flex; justify-content: flex-end">
            <x-jet-label for="terms">
                <div class="flex items-center">
                    <x-jet-checkbox name="terms" id="terms"/>

                    <div class="ml-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-jet-label>
        </div>
    @endif

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <x-jet-button class="ml-4">
            {{ __('Register') }}
        </x-jet-button>
    </div>
</form>

{{-- {{ $password_show }}

<input type="password" id="password"> 
<input type="checkbox" id="checkbox">Show Password

@livewireScripts
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#checkbox').on('change', function(){
            $('#password').attr('type',$('#checkbox').prop('checked')==true?"text":"password"); 
        });
    });
</script> --}}
</div>

