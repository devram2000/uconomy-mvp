<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if(request()->get('verified') == "0")
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ __('There was a problem with your verification. Please check your phone number and try again after 5 minutes. Contact us if this issue persists.') }}
                </div>
            @elseif(request()->get('verified') == "1")
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ __('Email Verified!') }}
                </div>
            @elseif(request()->get('verified') == "2")
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ __('Phone Number Verified!') }}
                </div>
            @elseif(request()->get('verified') == "3")
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ __('Identity Verification Submitted.') }}
                </div>
            @endif                
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

           
           


            <?php /* 

             <div class="mt-10 sm:mt-0">
                @livewire('add-card')
            </div>
            <x-jet-section-border />

             <div class="mt-10 sm:mt-0">
                @livewire('verification', ['user' => $user])
            </div>
            <x-jet-section-border />

            <x-jet-section-border />

           <div class="mt-10 sm:mt-0">
                @livewire('default-payments')
            </div>


            <div class="mt-10 sm:mt-0">
            @livewire('onboarding')
            </div>
            <x-jet-section-border />
           
            @livewire('default-payments')


             <div class="mt-10 sm:mt-0">
                @livewire('k-y-c')
            </div>
            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('plaid')
            </div>
            <x-jet-section-border />


            <div class="mt-10 sm:mt-0">
                @livewire('virtual-card')
            </div>
            <x-jet-section-border />

            

            <div class="mt-10 sm:mt-0">
                @livewire('verification', ['user' => $user])
            </div>
            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('zelle')
            </div>
            <x-jet-section-border />
            
            <div class="mt-10 sm:mt-0">
                @livewire('address-finder')
            </div>
            <x-jet-section-border />
            */ ?> 

           


            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
