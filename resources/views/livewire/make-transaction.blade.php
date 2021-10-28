@push('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="{{ asset('wizard.css') }}" rel="stylesheet" id="bootstrap-css">



    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


@endpush

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Uconomy') }}
    </h2>
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


                                <div class="form-group">
                                    <label for="purchaseAmount">Enter  Amount: </label>
                                    <input type="text" id="amount" class="form-control" id="purchaseAmount" placeholder="{{ $amount }}" wire:model="amount">
                                    @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" wire:model="category" 
                                        class="p-2 px-4 py-2 pr-8 leading-tight bg-white border border-gray-400 rounded shadow appearance-none hover:border-gray-500 focus:outline-none focus:shadow-outline">
                                        <option value=''>Choose a Category</option>
                                        @foreach($categories as $c)
                                            <option value='{{ $c }}'>{{ $c }}</option>
                                        @endforeach 
                                    </select>
                                    @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control" id="description" placeholder="{{ $description }}" wire:model="description"></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                
                                <button class="btn btn-primary nextBtn btn-lg pull-right" wire:click="firstStepSubmit" type="button" >Next</button>

                            </div>

                        </div>

                    </div>
                    @endif
                    @if($currentStep==2)

                    <div class="row setup-content {{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">

                        <div class="col-xs-12">

                            <div class="col-md-12">


                                <section id="suggested-payments">
                                    <div id="payment-title"> 
                                        {{ __('Suggested Payment Dates:') }} 
                                    </div> 
                                    {{ __('Your remaining balance is: $') }}{{ $remaining_amount }}
                                    <div id="calendar"> 
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        
                                        var calendar = $('#calendar').fullCalendar({
                                                            events: SITEURL + "/transact",
                                                            editable: false,
                                                            defaultView: 'month',
                                                            header: {
                                                                left:   'title',
                                                                center: '',
                                                                right:  'today prev,next month basicWeek'
                                                            },

                                                            validRange: function(nowDate) {
                                                                return {
                                                                    start: nowDate,
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
                                                                if (!isNaN(title) && !isNaN(parseFloat(title)) && parseFloat(title) != 0 ) {
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
                                                                            displayMessage("Payment Date Created Successfully");
                                        
                                                                            calendar.fullCalendar('renderEvent',
                                                                                {
                                                                                    id: data.id,
                                                                                    title: "$" + title,
                                                                                    start: start,
                                                                                    allDay: allDay
                                                                                },true);
                                        
                                                                            calendar.fullCalendar('unselect');
                                                                        }
                                                                    });
                                                                } else {
                                                                    displayError("Please Enter a Valid Payment Amount");
                                                                }
                                                            },
                                                            eventDrop: function (event, delta) {
                                                                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                                        
                                                                $.ajax({
                                                                    url: SITEURL + '/transactAjax',
                                                                    data: {
                                                                        title: event.title,
                                                                        start: start,
                                                                        id: event.id,
                                                                        type: 'update'
                                                                    },
                                                                    type: "POST",
                                                                    success: function (response) {
                                                                        displayMessage("Payment Date Updated Successfully");
                                                                    }
                                                                });
                                                            },
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
                                                                            calendar.fullCalendar('removeEvents', event.id);
                                                                            displayMessage("Payment Date Removed Successfully");
                                                                        }
                                                                    });
                                                                }
                                                            }
                                        
                                                        });
                                        
                                        });
                                        
                                        function displayMessage(message) {
                                            toastr.success(message, 'Event');
                                        } 

                                        function displayError(message) {
                                            toastr.warning(message, 'Event');

                                        }
                                        
                                    </script>

                                </section> 

                                <button class="btn btn-danger nextBtn btn-lg pull-right" type="button" wire:click="back(1)">Back</button>

                                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" wire:click="secondStepSubmit">Next</button>


                            </div>

                        </div>

                    </div>
                    @endif
                    @if($currentStep==3)
                    <div class="row setup-content {{ $currentStep != 3 ? 'displayNone' : '' }}" id="step-3">

                        <div class="col-xs-12">

                            <div class="col-md-12">


                          

                                <button class="btn btn-danger nextBtn btn-lg pull-right" type="button" wire:click="back(2)">Back</button>

                                <button class="btn btn-success btn-lg pull-right" wire:click="submitForm" type="button">Finish!</button>


                            </div>

                        </div>

                    </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>