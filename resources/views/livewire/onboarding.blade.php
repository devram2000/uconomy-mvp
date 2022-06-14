<div>
    @if($kyc == null)
        @livewire('k-y-c')
    @elseif($plaid == null)
        @livewire('plaid')
    @elseif($debit == null)
        <x-jet-form-section submit="createPayment">
            <x-slot name="title">
                {{ __('Debit Card') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Link a Debit Card.') }}
            </x-slot>

            <x-slot name="form">
            

                <div class="col-span-6 sm:col-span-4">

                <div id="card-container">
                    <div class="panel">
                        <div class=""> Connect a valid debit card! </div>

                    </div>
                </div>
                </div>
                
                



            </x-slot>
            <x-slot name="actions">
        
                
                <x-jet-button>
                    {{ __('Start') }}
                </x-jet-button>

            </x-slot>
        </x-jet-form-section>

    @elseif($schedule == null)
        <div class="flex justify-center ">
            <div class="w-1/2 text-center font-semibold text-lg text-gray-800 leading-tight">
            Congrats, Uconomer! You are now verified. Fill out a default payment schedule below, and then view your virtual card on the <u><a  wire:click="redirectHome">Home</a></u> dashboard!
            </div>
        </div>
    @else
    <div class="flex justify-center ">
            <div class="w-1/2 text-center font-semibold text-lg leading-tight">
            Congrats, Uconomer! You are now verified. <br>
            View your virtual card on the <u><a  wire:click="redirectHome">Home</a></u> dashboard
            </div>
        </div>
    @endif
</div>
