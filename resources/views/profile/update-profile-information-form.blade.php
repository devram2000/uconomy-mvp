<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('View and edit your account\'s information.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif


        <!-- Name -->
    

        <div class="col-span-6 sm:col-span-4 flex space-x-0">
            <div>
                <x-jet-label for="first_name" value="{{ __('First Name') }}" />
                <x-jet-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" wire:model.defer="state.first_name" required autofocus autocomplete="first_name" />
            </div>
            <div class="ml-2">
                <x-jet-label for="last_name" value="{{ __('Last Name') }}" />
                <x-jet-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" wire:model.defer="state.last_name" required autofocus autocomplete="last_name" />
            </div>
        </div>

        <x-jet-input-error for="first_name" class="mt-2" />
        <x-jet-input-error for="last_name" class="mt-2" />

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            @if($this->user->email_verified_at == null)

            <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="mt-2" />
            @else
            {{ $this->user->email }}
            @endif

        </div> 
        
        <!-- Phone Number -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="phone_number" value="{{ __('Phone Number') }}" />
            @if($this->user->phone_verified_at == null)
            <x-jet-input id="phone_number" type="tel" class="mt-1 block w-full" wire:model.defer="state.phone_number" />
            <x-jet-input-error for="phone_number" class="mt-2" />
            @else
            {{ $this->user->phone_number }}
            @endif

        </div>


    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
