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
                <section id="transact">

             
                    @if($currentStep==1)

                    <div class="row setup-content {{ $currentStep != 1 ? 'displayNone' : '' }}" id="step-1">

                        <div class="col-xs-12">

                            <div class="col-md-12">
                                <section class="transaction-heading">
                                    <div id="button-space"></div>
                                    <x-jet-button id="upay-button" wire:click="firstStepSubmit" type="button" >Next</x-jet-button>          
                                </section>
                                <div id="payment-title"> 
                                    {{ __('Transaction Details') }} 
                                </div> 
                               

                                <div class="form-group">
                                    <label for="purchaseAmount">Enter  Amount ($)</label>
                                    <x-jet-input-error for="amount" class="mt-2" />
                                    <input type="text" id="amount" class="form-control" id="purchaseAmount" placeholder="{{ $amount }}" wire:model="amount">
                                </div>

                            
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <x-jet-input-error for="category" class="mt-2" />
                                    <select name="category" wire:model="category" 
                                        class="p-2 px-4 py-2 pr-8 leading-tight bg-white border border-gray-400 rounded  appearance-none hover:border-gray-500 focus:outline-none ">
                                        <option value=''>Choose a Category</option>
                                        @foreach($categories as $c)
                                            <option value='{{ $c }}'>{{ $c }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            
                                <div class="form-group">
                                    <label for="description">Description (what are you planning to do with the money?)</label>
                                    <x-jet-input-error for="description" class="mt-2" />
                                    <textarea id="description" class="form-control" id="description" placeholder="{{ $description }}" wire:model="description"></textarea>

                                </div>
                

                            </div>

                        </div>

                    </div>
                    @endif
                    @if($currentStep==2)

                    <div class="row setup-content {{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">

                        <div class="col-xs-12">

                            <div class="col-md-12">


                                <section id="suggested-payments">
                                    <section class="transaction-heading">
                                        <x-jet-button id="upay-button" type="button" wire:click="back(1)">Back</x-jet-button>
                                        <div id="button-space-payment"></div>
                                        <x-jet-button id="hidden-button" type="button" wire:click="secondStepSubmit">Next</x-jet-button> 
                                    </section>
                                    <div id="payment-title"> 
                                        {{ __('Pick Your Payment Dates') }} 
                                    </div> 
                                    <section id="remaining">
                                        <div>{{ __('Your remaining balance is: $') }}</div>
                                        <div id="remaining-variable">{{ $this->remaining_amount }}</div>

                                    </section>      
                                    <div id="payment-description" class="text-center"> 
                                        {{ __('Add payments until there is no more balance (click to add/remove payments)') }} 
                                    </div> </br>
                                    <div class="calendar" id="calendar"> 
                                    </div>
                                    <script>
                                        $(document).ready(viewCalendar());
                                        remainingAmountUpdate(0);

                                        function remainingAmountUpdate(amount) {
                                            var previous_amount = document.getElementById("remaining-variable").innerHTML;
                                            var current_amount = previous_amount - amount;
                                            if (current_amount == 0) {
                                                $("#hidden-button").show();
                                                $("#button-space-payment").hide();
                                                
                                            } else {
                                                $("#button-space-payment").show();
                                                $("#hidden-button").hide();
                                            }
                                            document.getElementById("remaining-variable").innerHTML = current_amount.toFixed(2);
                                        }


                                        
                                        function viewCalendar() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        var window = {{ $this->window }}
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                       
    
                                 

                                        var calendar = $('#calendar').fullCalendar({
                                                            // events: SITEURL + "/transact",
                                                            events: @json($events),
                                                            editable: false,
                                                            height: 'auto',
                                                            longPressDelay: 0,
                                                            eventColor: '#7cd9edff',
                                                            // eventBorderColor: 'black',
                                                            defaultView: 'month',
                                                            header: {
                                                                left:   'title',
                                                                center: '',
                                                                right:  'today prev,next month basicWeek'
                                                            },

                                                            validRange: function(nowDate) {
                                                                return {
                                                                    start: nowDate.clone().subtract(1, 'days'),
                                                                    end: nowDate.clone().add(window, 'months')
                                                                };
                                                            },
                                                    
                                                            eventRender: function (event, element, view) {
                                                                if (event.allDay === 'true') {
                                                                        event.allDay = true;
                                                                } else {
                                                                        event.allDay = false;
                                                                }
                                                            },
                                                            selectable: true,
                                                            selectHelper: true,
                                                            selectOverlap: false,
                                                            select: function (start, end, allDay) {
                                                                var title = prompt('How much would you like to pay on this day?');
                                                                var float_title = parseFloat(title);
                                                                Number.prototype.countDecimals = function () {
                                                                    if(Math.floor(this.valueOf()) === this.valueOf()) return 0;
                                                                    return this.toString().split(".")[1].length || 0; 
                                                                }       
                                                                if (isNaN(title) || isNaN(float_title) 
                                                                    || float_title.countDecimals() > 2 || float_title < 0) {
                                                                    displayError("Please Enter a Valid Amount");
                                                                } else if (float_title < 1) {
                                                                    displayError("Please Enter an Amount Over $1");
                                                                } else if (document.getElementById("remaining-variable").innerHTML == 0) {
                                                                    displayError("Your Remaining Balance Is $0!");
                                                                } else if (float_title > document.getElementById("remaining-variable").innerHTML) {
                                                                    displayError("Please Enter " + document.getElementById("remaining-variable").innerHTML + " or Less");
                                                                } else {
                                                                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                                                                    var half_title = document.getElementById("remaining-variable").innerHTML - parseFloat(title);
                                                                    
                                                                    if (half_title < 1) {
                                                                        title = document.getElementById("remaining-variable").innerHTML;
                                                                    }

                                                                    $.ajax({
                                                                        url: SITEURL + "/transactAjax",
                                                                        data: {
                                                                            title: title,
                                                                            start: start,
                                                                            type: 'add'
                                                                        },
                                                                        type: "POST",
                                                                        success: function (data) {
                                                                            // document.getElementById("remaining-variable").innerHTML = @this.getRemainingAmount();
                                                                            remainingAmountUpdate(title);
                                                                            displayMessage("Payment Date Created");
                                    

                                                                            calendar.fullCalendar('renderEvent',
                                                                                {
                                                                                    id: data.id,
                                                                                    title: title,
                                                                                    start: start,
                                                                                    allDay: allDay
                                                                                },true);
                                        
                                                                            calendar.fullCalendar('unselect');
                                                                        }
                                                                    });
                                                                }
                                                            },
                                                            // eventDrop: function (event, delta) {
                                                            //     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                                        
                                                            //     $.ajax({
                                                            //         url: SITEURL + '/transactAjax',
                                                            //         data: {
                                                            //             title: event.title,
                                                            //             start: start,
                                                            //             id: event.id,
                                                            //             type: 'update'
                                                            //         },
                                                            //         type: "POST",
                                                            //         success: function (response) {
                                                            //             displayMessage("Payment Date Updated");
                                                            //         }
                                                            //     });
                                                            // },
                                                            eventClick: function (event) {
                                                                var deleteMsg = confirm("Do you really want to remove this payment?");
                                                                if (deleteMsg) {
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: SITEURL + '/transactAjax',
                                                                        data: {
                                                                                id: event.id,
                                                                                type: 'delete'
                                                                        },
                                                                        success: function (response) {
                                                                            remainingAmountUpdate(event.title * -1);
                                                                            calendar.fullCalendar('removeEvents', event.id);
                                                                            displayMessage("Payment Date Removed");
                                                                        }
                                                                    });
                                                                }
                                                            }
                                        
                                                        });
                                        
                                        }
                                        
                                        function displayMessage(message) {
                                            toastr.success(message, 'Action Successful');
                                        } 

                                        function displayError(message) {
                                            toastr.warning(message, 'Action Unsuccessful');

                                        }
                                        
                                    </script>

                                </section> 



                            </div>

                        </div>

                    </div>
                    @endif
                    @if($currentStep==3)
                    <div class="row setup-content {{ $currentStep != 3 ? 'displayNone' : '' }}" id="step-3">

                        <div class="col-xs-12">

                            <div class="col-md-12">
                                <section id="confirm-payments">
                                    <section class="transaction-heading">
                                        <x-jet-button id="upay-button" type="button" wire:click="back(2)">Back</x-jet-button>
                                        <x-jet-button id="upay-button" type="button" wire:click="submitForm">Done</x-jet-button>
                                    </section> 
                                    <div id="payment-title"> 
                                            {{ __('Confirm Your Payments') }} 
                                        </div> 

                                    <section id="fee-description">
                                        <div>{{ __('Uconomy charges a $5 subscription fee per month. If you 
                                            pay off your balance early, you don\'t have to pay the fee for the next month(s)!') }}</div>

                                    </section> </br>

                                    <div class="calendar" id="paymentsCalendar"> 
                                    </div>
                                    <script>
                                        $(document).ready(viewPayments());

                                    
                                        
                                        function viewPayments() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        var window = {{ $this->window }};

                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#paymentsCalendar').fullCalendar({
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

                                                            validRange: function(nowDate) {
                                                                return {
                                                                    start: nowDate.clone().subtract(1, 'days'),
                                                                    end: nowDate.clone().add(window, 'months')
                                                                };
                                                            },
                                                    
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


                            </div>

                        </div>

                    </div>
                    @endif
                    @if($currentStep==4)
                    <section id="upay-buy">
                        <div id="upay-title"> 
                            {{ __('Your transaction has been created!') }}
                        </div>
                        <div id="transaction-agreement"> 
                            {{ __('The Uconomy Team will review this transaction within 24 hours. Once you are approved, the money will be sent to your bank account using Zelle!') }}
                        </div>
                        <div>
                            <x-jet-button id="upay-button" type="button" wire:click="redirectHome">
                                {{ __('Return Home') }}
                            </x-jet-button>  
                        </div>
                    </section>
                    @endif
                </section>
                <script type="text/javascript">
                    // window.onbeforeunload = function() {
                    //     if (confirm()) return true;
                    //     else return false;
                    // }
                    
                </script>

            </div>
        </div>
    </div>
</div>