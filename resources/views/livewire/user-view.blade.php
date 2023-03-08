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

<x-slot name="header">
    <div class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200" id="dash">
                <section id="userView">
                    @if ($isUser) 
                    <h1>{{ $user->id }}{{ __(': ') }}{{ $user->name }}</h1>
                    <div class="calendar" id="{{ $user->id }}"> </div>
                    <script>
                                        $(document).ready(viewDates());

                                    
                                        
                                        function viewDates() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#{{ $user->id }}').fullCalendar({
                                                            events: @json($events_and_fees),
                                                            editable: false,
                                                            eventColor: '#7cd9edff',
                                                            height: 'auto',

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
                    <div id="user-information">
                        <div class = "mt-4">{{ __('Limit: $') }}{{ $user->limit }}</div>
                        <div>{{ __('Remaining Balance Owed: $') }}{{ $remaining_balance }}</div>
                        <div>{{ __('Spending Amount: $') }}{{ $spending_amount }}</div>
                        <div>
                            {{ __('Email: ') }}{{ $user->email }}
                            @if($user->email_verified_at == NULL)
                            (Not Verified)
                            @else
                            (Verified)
                            @endif
                        </div>
                        <div>
                            {{ __('Phone Number: ') }}{{ $user->phone_number }}
                            @if($user->phone_verified_at == NULL)
                            (Not Verified)
                            @else
                            (Verified)
                            @endif
                        </div>
                        <div>{{ __('Zelle: ') }}{{ $user->zelle }}</div>
                        <div>{{ __('Date of Birth: ') }}{{ $user->date_of_birth }}</div>
                        <div>
                            {{ __('Payback Window: ') }}
                            @if($user->window == NULL)
                            {{ __('3') }}
                            @else
                            {{ $user->window }}
                            @endif 
                            {{ __(' months') }}
                        </div>
                        <div>
                            {{ __('Address: ') }}
                            @if($address != NULL)
                                {{ $address->address }}
                                @if($address->apt != NULL)
                                    {{ __(', Apt. ') }} {{ $address->apt }}      
                                @endif     
                                {{ __(', ') }}{{ $address->city }}{{ __(', ') }}{{ $address->state }}{{ __(', ') }}{{ $address->zipCode }}{{ __(', ') }}{{ $address->country }}             
                            @endif
                        </div>
                        <div>
                            {{ __('Identification: ') }}
                            @if($identification != NULL)
                                {{ $identification->type }}
                                <div class="mt-2" id = "license-images">
                                    <img src="/storage/ids/{{ $identification->photo1 }}" id="license-image" />
                                    @if ($identification->photo2 != NULL)  
                                    <img src="/storage/ids/{{ $identification->photo2 }}" id="license-image" />
                                    @endif  
                                </div>
                            @endif
                        </div>


                    </div>

                    <section class="" id="userview-transactions">
                        <div id="user-information" class=" "> 
                            {{ __('Transactions:') }}
                        </div>

                        <div id="">
                            @foreach($transactions as $item)
                            <div class="">
                                <div class="transaction-sub"><b>Start Date</b>: {{ date('m/d/Y', strtotime($item->start_date)) }}</div>
                                <div class="transaction-sub"><b>Category</b>: {{ $item->category }}</div>
                                <div class="transaction-sub"><b>Description</b>: {{ $item->description}}</div>
                                <div class="transaction-sub"><b>Estimated Completion</b>: {{ date('m/d/Y', strtotime($item->due_date))}}</div>
                                <div class="transaction-sub"><b>Amount</b>: {{ __('$') }}{{ $item->amount}}</div>
                                <div class="transaction-sub"><b>Remaining Balance</b>: {{ __('$') }}{{ $item->remaining_balance}}</div>
                            </div>
                            @endforeach

                        </div>

                        @foreach($bills_payments as $bill)
                    <div class="mt-4">
                        <div class="font-bold text-xl">Bill Reference #: {{ 1000 + $bill[0]['id'] }}</div>

                    
                        <div class="transaction-sub mt-2"><b>Status</b>: 
                            @if($bill[0]['status'] == NULL)
                                Submitted
                            @else 
                                {{ $bill[0]['status'] }}
                            @endif
                        </div>
                    
                        <div class="transaction-sub mt-2"><b>Request Creation Date</b>: {{ date('m/d/Y h:i:s', strtotime($bill[0]['created_at'])) }}</div>

                        <div class="transaction-sub mt-2"><b>Picture:</b></div>

                        <iframe class="mt-2" src="/storage/bills/{{ $bill[0]['bill'] }}" width="100%" height="500"></iframe>

                        <div class="transaction-sub mt-2"><b>Preferred Dates:</b></div>

                        @livewire('view-calendar', ['events_and_fees' => $bill[1], 'name' => $bill[0]['id']])

                        <div class="transaction-sub mt-2"><b>Additional Comments:</b> {{ $bill[0]['comments'] }}</div>

                    </div>
                    @endforeach

                        

                    </div>




                    @else
                        <h1>{{ __('No user with ID ') }}{{ $userID }}<h1>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>