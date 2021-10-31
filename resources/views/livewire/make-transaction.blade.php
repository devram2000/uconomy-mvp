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
                                <section id="transaction-heading">
                                    <!-- <button class="btn btn-primary nextBtn btn-lg pull-right" wire:click="firstStepSubmit" type="button" >Back</button> -->
                                    <div id="button-space"></div>
                                    <div id="payment-title"> 
                                        {{ __('Transaction Details') }} 
                                    </div> 
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" wire:click="firstStepSubmit" type="button" >Next</button>
                                </section>
                               

                                <div class="form-group">
                                    <label for="purchaseAmount">Enter  Amount </label>
                                    <input type="text" id="amount" class="form-control" id="purchaseAmount" placeholder="{{ $amount }}" wire:model="amount">
                                    @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" wire:model="category" 
                                        class="p-2 px-4 py-2 pr-8 leading-tight bg-white border border-gray-400 rounded  appearance-none hover:border-gray-500 focus:outline-none ">
                                        <option value=''>Choose a Category</option>
                                        @foreach($categories as $c)
                                            <option value='{{ $c }}'>{{ $c }}</option>
                                        @endforeach 
                                    </select>
                                    @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            
                                <div class="form-group">
                                    <label for="description">Description (what are you planning to do with the money?)</label>
                                    <textarea id="description" class="form-control" id="description" placeholder="{{ $description }}" wire:model="description"></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
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
                                    <section id="transaction-heading">
                                        <button class="btn btn-danger nextBtn btn-lg pull-right" type="button" wire:click="back(1)">Back</button>
                                        <div id="payment-title"> 
                                            {{ __('Pick Your Payment Dates:') }} 
                                        </div> 
                                        <div id="button-space-payment"></div>
                                        <button id="hidden-button" class="btn btn-primary nextBtn btn-lg pull-right" type="button" wire:click="secondStepSubmit">Next</button>
                                    </section>
                                    <section id="remaining">
                                        <div>{{ __('Your remaining balance is: $') }}</div>
                                        <div id="remaining-variable">{{ $this->remaining_amount }}</div>
                                        <!-- <div>{{ __('(add payments until there is no balance)') }}</div> -->
                                      <!-- <div class="form-group">
                                            <label for="purchaseAmount">Enter  Amount: </label>
                                            <input type="text" id="amount" class="form-control" id="purchaseAmount"  wire:model="amount">
                                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div> -->

                                        <!-- @if($errors->any())
                                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                                        @endif -->
                                        <!-- @error('firstname')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror -->

                                    </section>      
                                    <div> 
                                        {{ __('Add payments until there is no more balance') }} 
                                    </div> </br>
                                    <div id="calendar"> 
                                    </div>
                                    <script>
                                        //  document.getElementById("calendar").addEventListener('click', function() {
                                        //     document.getElementById("remaining-variable").innerHTML = "hello";
                                        // });
                                    </script>
                                    <script>
                                        $(document).ready(viewCalendar());
                                        remainingAmountUpdate(0);

                                        function remainingAmountUpdate(amount) {
                                            document.getElementById("remaining-variable").innerHTML -= amount;
                                            if (document.getElementById("remaining-variable").innerHTML == 0) {
                                                $("#hidden-button").show();
                                                $("#button-space-payment").hide();
                                                
                                            } else {
                                                $("#button-space-payment").show();
                                                $("#hidden-button").hide();
                                            }
                                        }


                                        
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
                                                                if (!isNaN(title) && !isNaN(parseFloat(title)) && parseFloat(title) > 0 
                                                                    && parseFloat(title) <= document.getElementById("remaining-variable").innerHTML) {
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
                                                                            // document.getElementById("remaining-variable").innerHTML = @this.getRemainingAmount();
                                                                            remainingAmountUpdate(title);
                                                                            displayMessage("Payment Date Created Successfully");
                                    

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
                                                                            remainingAmountUpdate(event.title * -1);
                                                                            calendar.fullCalendar('removeEvents', event.id);
                                                                            displayMessage("Payment Date Removed Successfully");
                                                                        }
                                                                    });
                                                                }
                                                            }
                                        
                                                        });
                                        
                                        }
                                        
                                        function displayMessage(message) {
                                            toastr.success(message, 'Event');
                                        } 

                                        function displayError(message) {
                                            toastr.warning(message, 'Event');

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


                          

                                <button class="btn btn-danger nextBtn btn-lg pull-right" type="button" wire:click="back(2)">Back</button>

                                <button class="btn btn-success btn-lg pull-right" wire:click="submitForm" type="button">Finish!</button>


                            </div>

                        </div>

                    </div>
                    @endif
                </section>
                <script type="text/javascript">
                    window.onbeforeunload = function() {
                        if (confirm()) return true;
                        else return false;
                    }
                </script>

            </div>
        </div>
    </div>
</div>