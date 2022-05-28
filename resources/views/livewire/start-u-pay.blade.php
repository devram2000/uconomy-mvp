@push('scripts')
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>



    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


@endpush
@if($is_admin)
    <section id="upay">
        <section id="upay-buy">

            <div id="upay-title"> 
                {{ __('Admin Panel') }}
            </div>
            <div id="admin-buttons">
                <x-jet-button id="upay-button" type="button" wire:click="redirectAdmin">
                    {{ __('View Transactions') }}
                </x-jet-button>

                <x-jet-button class="mt-2" id="upay-button" type="button" wire:click="redirectWaitlist">
                    {{ __('View Waitlist') }}
                </x-jet-button>
            </div>
        </section>
    </section>
@else
<section id="upay">
    <section id="upay-buy">
        
        <div id="upay-title"> 
            {{ __('UPay') }}
        </div>
        <?php
    /* @if(!$is_approved)
            <div class="text-center"> 
                {{ __('Thank you for signing up!') }}</br></br>
                {{ __('To make a transaction with your $250 limit, please
                    sign up for our ') }}<a href="https://www.uconomy.com">waitlist</a>{{ __('.') }}</br>
                {{ __('If you are already signed up, you can fill out sections of your profile to boost
                    your spot!') }}</br></br>

            </div>

            <x-jet-button id="upay-button" type="button" wire:click="redirectProfile">
                {{ __('Profile') }}
            </x-jet-button>
        @else */
?>
        
        <?php
    /* @if($spending_amount >= 10)
            
            <div class="text-center"> 
                {{ __('Uconomy Beta Testing is currently closed for new transactions, but we plan on lauching soon! We apologize for the inconvenience.') }}</br></br>
                {{ __('If you have any questions or concerns, please contact us at ') }}<a href="mailto:help@uconomy.com">help@uconomy.com</a>{{ __('.') }}

            </div>
            
            @else  */
?>
            <div> 
                {{ __('Your amount available to spend is $') }}{{ $spending_amount }}

            </div>
            <div>
                @if($profile_completed && $spending_amount >= 10)
                    @livewire('virtual-card') 
                @elseif($spending_amount >= 10)
                <div id="profile-button">
                    <div> {{ __(' Please complete the ') }} {{ $profile_sections }} {{ __(' of your Profile before making your payment plan.') }} </div> </br>
                
                    <x-jet-button id="upay-button" type="button" wire:click="redirectProfile">
                        {{ __('Profile') }}
                    </x-jet-button>
                </div>
                @endif
                <?php
    /*        @endif
*/
?>
                <?php
    /*        @endif
*/
?>
        </div>
    </section>
    @if($remaining_balance)
    <section id="upay-balance">
       
        <div id="payment-title"> 
            {{ __('Your Payments') }} 
        </div> 
         <div>
            {{ __('Total Remaining Balance: $') }}{{ $remaining_balance }}
        </div>
        <x-jet-secondary-button id="upay-button" class="mt-2" type="button" wire:click="redirectPayment">
            {{ __('Make a Payment') }}
        </x-jet-secondary-button>

        <x-jet-secondary-button id="upay-button" class="mt-2" type="button" wire:click="redirectReschedule">
            {{ __('Reschedule Payment Dates') }}
        </x-jet-secondary-button>
</br>
        <div class="calendar" id="mainCalendar"> 
                                    </div>
                                    <script>
                                        $(document).ready(viewDates());

                                    
                                        
                                        function viewDates() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#mainCalendar').fullCalendar({
                                                            // events: SITEURL + "/transact",
                                                            events: @json($events_and_fees),
                                                            editable: false,
                                                            eventColor: '#7cd9edff',
                                                            height: 'auto',
                                                            // eventBorderColor: 'black',

                                                            defaultView: 'month',
                                                            header: {
                                                                left:   'title',
                                                                center: '',
                                                                right:  'today prev,next month basicWeek'
                                                            },

                                                            // validRange: function(nowDate) {
                                                            //     return {
                                                            //         start: nowDate.clone().subtract(1, 'days'),
                                                            //         end: nowDate.clone().add(3, 'months')
                                                            //     };
                                                            // },
                                                    
                                                            eventRender: function (event, element, view) {
                                                                if (event.allDay === 'true') {
                                                                        event.allDay = true;
                                                                } else {
                                                                        event.allDay = false;
                                                                }
                                                            },
                                                            selectable: false,
                                        
                                                        });
                                        
                                        }
                                        
                                        
                                        
                                    </script>

    </section>

    @endif
    @if($has_transactions)
    <section id="upay-transactions">
        <div id="transactions-title" class="text-center "> 
            {{ __('Your Transactions') }}
        </div>

        <div id="transaction-list">
            @foreach($transactions as $item)
            <div id="transaction-item">
                <div class="transaction-sub"><b>Start Date</b>: {{ date('m/d/Y', strtotime($item->start_date)) }}</div>
                <div class="transaction-sub"><b>Category</b>: {{ $item->category }}</div>
                <div class="transaction-sub"><b>Description</b>: {{ $item->description}}</div>
                <div class="transaction-sub"><b>Estimated Completion</b>: {{ date('m/d/Y', strtotime($item->due_date))}}</div>
                <div class="transaction-sub"><b>Amount</b>: {{ __('$') }}{{ $item->amount}}</div>
                <div class="transaction-sub"><b>Remaining Balance</b>: {{ __('$') }}{{ $item->remaining_balance}}</div>
            </div>
            @endforeach

        </div>
        

    </div>

    
    @endif
</div>    
@endif



<?php /* 
    <div class="p-6 border-t border-gray-200 md:border-l bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
    <div class="flex justify-between">
        <div class="p-6">
            <div class="flex items-center">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">{{ __('UPay') }}</div>
            </div>
    
            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500">
                    {{ __('Start Beta Testing with UPay today!') }}
                </div>
            </div>
            
        </div>

                                        
    </div>  
    <div id="upay">
        <x-jet-button id="upay-button" type="button" wire:click="redirectUPay">
            {{ __('Start Today') }}
        </x-jet-button>  
    
    </div>    
</div>

*/ ?> 


