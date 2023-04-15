<section id="upay" class="bg-gray-100 py-8">
    <section id="upay-buy" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div id="upay-title" class="text-4xl font-bold mb-8"> 
            {{ __('Uconomy') }}
        </div>

        @if($phone_verify == NULL && $email_verify == NULL)
        <div id="profile-button" class="bg-white shadow-md rounded p-6 mb-8">
            <div class="text-red-600 mb-4"> 
                {{ __('Complete your profile verification before requesting a bill date change.') }}
            </div>

            <x-jet-button id="upay-button" type="button" wire:click="redirectProfile" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Go to Profile') }}
            </x-jet-button>
        </div>
        @elseif($bills != NULL && $is_card_added == NULL)
        <div id="profile-button" class="bg-white shadow-md rounded p-6 mb-8">
            <div class="mt-2"> 
                <div>
                    {{ __('Add a payment method to your profile to begin processing your bill date change.') }}
                </div>
                <div>
                    {{ __('You won\'t be charged if we can\'t move your date, and will only be charged 3% of the amount moved if we can.') }}
                </div>
             </div> 

            <x-jet-secondary-button id="upay-button" class="mt-4 bg-gray-300 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded" type="button" wire:click="redirectProfile">
                {{ __('Go to Profile') }}
            </x-jet-secondary-button>
        </div>
        @else
        
            <x-jet-button id="upay-button" type="button" wire:click="redirectBill" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-8">
                    {{ __('Change a Bill Payment') }}
            </x-jet-button>
        @endif
        
        @foreach($bills_payments as $bill)
        <div class="bg-white shadow-md rounded p-6 mb-8">
            <div class="font-bold text-xl">Bill Reference #: {{ 1000 + $bill[0]['id'] }}</div>

            <div class="transaction-sub mt-2 font-semibold text-gray-600"><b>Status</b>: 
                @if($is_card_added == NULL)
                    Awaiting Payment Method
                @elseif($bill[0]['status'] == NULL)
                    Submitted
                @else 
                    {{ $bill[0]['status'] }}
                @endif
            </div>
           
            <div class="transaction-sub mt-2 font-semibold text-gray-600"><b>Request Creation Date</b>: {{ date('m/d/Y h:i:s', strtotime($bill[0]['created_at'])) }}</div>

            <div class="transaction-sub mt-2 font-semibold text-gray-600"><b>Bill Image:</b></div>

            <iframe class="mt-2 border" src="/storage/bills/{{ $bill[0]['bill'] }}" width="100%"></iframe>

            <div class="transaction-sub mt-2 font-semibold text-gray-600"><b>Preferred Dates:</b></div>
            @livewire('view-calendar', ['events_and_fees' => $bill[1], 'name' => $bill[0]['id']])

        <!-- <div class="transaction-sub mt-2 font-semibold text-gray-600"><b>Additional Comments:</b> {{ $bill[0]['comments'] }}</div> -->

        </div>
        @endforeach

    </section>
</section>
