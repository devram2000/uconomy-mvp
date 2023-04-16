<section id="upay">
    <section id="upay-buy">
        
        <div id="upay-title"> 
            {{ __('Uconomy') }}
        </div>

        <!-- <div> 
            {{ __('Change a Bill Payment') }}

        </div> -->
        @if($phone_verify == NULL && $email_verify == NULL)
        <div id="profile-button">
            
            <div> {{ __(' Please complete the verification section of your Profile before changing your bill date.') }} </div> </br>

            <x-jet-button id="upay-button" type="button" wire:click="redirectProfile">
                {{ __('Profile') }}
            </x-jet-button>
        </div>
        @elseif($bills != NULL && $is_card_added == NULL)
        <div id="profile-button">
            <div class="mt-2"> 
                <div>
                    {{ __(' Your bill date change will start processing when you add a payment method. Please contact us.') }}
                </div>
                <div>
                    {{ __('You won\'t be charged if we can\'t move your date, and will only be charged 3% of the amount moved if we can.') }}
                </div>
             </div> 

            <!-- <x-jet-secondary-button id="upay-button" class="mt-4" type="button" wire:click="redirectProfile">
                {{ __('Profile') }}
            </x-jet-secondary-button> -->
        </div>
        @else
        
                <x-jet-button id="upay-button" type="button" wire:click="redirectBill">
                        {{ __('Change a Bill Payment') }}
                    </x-jet-button>
        @endif
        
        @foreach($bills_payments as $bill)
        <div class="mt-4">
            <div class="font-bold text-xl">Bill Reference #: {{ 1000 + $bill[0]['id'] }}</div>

           
            <div class="transaction-sub mt-2"><b>Status</b>: 
                @if($bill[0]['status'] == NULL)
                    <a href="/paypal/{{$bill[0]['id']}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Waiting on Payment
                    </a>

                @else 
                    {{ $bill[0]['status'] }}
                @endif
            </div>
           
            <div class="transaction-sub mt-2"><b>Request Creation Date</b>: {{ date('m/d/Y h:i:s', strtotime($bill[0]['created_at'])) }}</div>

            <div class="transaction-sub mt-2"><b>Picture:</b></div>

            <iframe class="mt-2" src="/storage/bills/{{ $bill[0]['bill'] }}" width="100%"></iframe>

            <div class="transaction-sub mt-2"><b>Preferred Dates:</b></div>

            @livewire('view-calendar', ['events_and_fees' => $bill[1], 'name' => $bill[0]['id']])

            <!-- <div class="transaction-sub mt-2"><b>Additional Comments:</b> {{ $bill[0]['comments'] }}</div> -->

        </div>
        @endforeach


    </section>
</section>