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
                <section id="reschedule" >
                <section class="transaction-heading">
                    <div id="button-space-payment"></div>
                    <x-jet-button id="hidden-button" class="hidden" type="button" wire:click="rescheduleSubmit">Submit</x-jet-button> 
                </section>
                <div id="payment-title"> 
                    {{ __('Reschedule your Payment Dates') }} 

                </div> 
                <section id="remaining" class="flex justify-center">
                    <div class="text-center">{{ __('Your remaining balance is: $') }}</div>
                    <div id="remaining-variable">{{ $this->remaining_balance }}</div>

                </section>      
                <div id="payment-description" class="text-center"> 
                    {{ __('Add payments until there is no more balance (click to add/remove payments)') }} 
                </div> </br>
                <div class="calendar" id="calendar" > 
                </div>


                </div>
                <script>
                                        $(document).ready(viewCalendar());


                                        function remainingAmountUpdate(amount) {
                                            var previous_amount = document.getElementById("remaining-variable").innerHTML;
                                            var current_amount = previous_amount - amount;
                                            if (current_amount == 0) {
                                                $("#hidden-button").show();
                                                
                                            } else {
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
                                                                    start: nowDate.clone(),
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
                                                                    || float_title.countDecimals() > 2) {
                                                                    displayError("Please Enter a Valid Amount");
                                                                } else if (float_title < 10 && document.getElementById("remaining-variable").innerHTML >= 10) {
                                                                    displayError("Please Enter an Amount Over $10");
                                                                // } else if (document.getElementById("remaining-variable").innerHTML <= 10
                                                                //             && float_title != float_title) {
                                                                //     displayError("Please Enter an Amount Equal to Your remaining ");
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
</div>