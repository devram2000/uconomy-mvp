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

                <section id="upay" class = "min-h-48">
                    <!-- @if($submitted == NULL) -->

                    <div id="identity" x-data=""> 
                        <div id="identity-page" class="col-span-6 sm:col-span-4">
                            Please upload a picture of your bill.
                            @if ($bill == NULL )
                            <input type="file" class="hidden" wire:loading.attr="disabled" class="mt-4" wire:model="bill" x-ref="pp1" >
                            </input>
                            <x-jet-secondary-button class="mt-2" type="button" wire:loading.remove x-on:click.prevent="$refs.pp1.click()">
                                {{ __('Add File') }}
                            </x-jet-secondary-button>
                            <div class="mt-2" wire:loading>
                                {{ __('Uploading...') }}
                            </div>
                            @else
                                <div class="mt-2">
                                    {{ __('File Uploaded!') }}
                                </div>
                                <x-jet-button class="" id="remove-bill-button" wire:click="removeBill" onclick="this.disabled=true" type="button">
                                    {{ __('Remove Bill File') }}
                                </x-jet-button>

                            @endif
                            <x-jet-input-error for="bill" class="mt-2" />

                    </div>

                    @if($bill != NULL)
                    <div id="payment-description" class="mt-2 text-center"> 
                                {{ __('Enter your preferred payment date(s):') }} 
                            </div> 
                            <div class="mt-2 calendar" id="calendar" wire:ignore> 
                                        </div>
                            <script>
                                $(document).ready(viewCalendar());
            
                                function viewCalendar() {
                                
                                var SITEURL = "{{ url('/') }}";
                                
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
                                                            end: nowDate.clone().add(3, 'months')
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
                                                        } else if (float_title < 10) {
                                                            displayError("Please Enter an Amount Over $10");
                                                        // } else if (document.getElementById("remaining-variable").innerHTML <= 10
                                                        //             && float_title != float_title) {
                                                        //     displayError("Please Enter an Amount Equal to Your remaining ");
                                                        } else {
                                                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD");

                                                            $.ajax({
                                                                url: SITEURL + "/transactAjax",
                                                                data: {
                                                                    title: title,
                                                                    start: start,
                                                                    type: 'add'
                                                                },
                                                                type: "POST",
                                                                success: function (data) {
                                                                    displayMessage("Payment Date Selected");
                            

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
                                                    eventClick: function (event) {
                                                        var deleteMsg = confirm("Do you want to remove this payment?");
                                                        if (deleteMsg) {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: SITEURL + '/transactAjax',
                                                                data: {
                                                                        id: event.id,
                                                                        type: 'delete'
                                                                },
                                                                success: function (response) {
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

                            <!-- <div class="form-group">
                                <label class="mt-8" for="comments">Would you like to make any additional comments?</label>
                                <x-jet-input-error for="comments" class="mt-2" />
                                <textarea id="comments" class="form-control mt-2" wire:model="comments"></textarea>

                            </div> -->


                        <div id="identity-submit">
                            <x-jet-button id="identity-submit-button" class="mt-4" wire:click="submitBill" type="button" >
                                {{ __('Upload') }}
                            </x-jet-button>          
                        <div>
                    @endif
                    <!-- @else
                    <div id="identity" x-data=""> 
                        <div id="identity-page" class="col-span-6 sm:col-span-4">
                            Thank you for submitting your bill.
Thank you for submitting your bill. </br> Our negotiations team will start once you send $7 to XXX-XXX-XXXX via Zelle for the reschedule fee. If we are not able to delay your dates, you will get a full refund.
                        <div class="form-group">
                            <label class="mt-8" for="comments">Would you like to make any additional comments for our negotiation team?</label>
                            <x-jet-input-error for="comments" class="mt-2" />
                            <textarea id="comments" class="form-control mt-2" wire:model="comments"></textarea> 

                        </div>
                        </div>
                        </div>
                        <div id="identity-submit">
                            <x-jet-button id="identity-submit-button" class="mt-4" wire:click="submitComment" type="button" >
                                {{ __('Submit') }}
                            </x-jet-button>          
                        <div>
                    @endif -->
                </section>

            </div>
        </div>
    </div>
</div>