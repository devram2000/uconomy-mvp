@push('scripts')
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
                    <form wire:submit.prevent="submit">
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

                        @if(false)
                        <section id="">
                            Suggested payment dates:
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
                                                    editable: true,
                                                    defaultView: 'month',
                                                    header: {
                                                        left:   'title',
                                                        center: '',
                                                        right:  'today prev,next month basicWeek'
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
                                                    select: function (start, end, allDay) {
                                                        var title = prompt('How much would you like to pay on this day?');
                                                        if (title) {
                                                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                                                            var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                                                            $.ajax({
                                                                url: SITEURL + "/transactAjax",
                                                                data: {
                                                                    title: title,
                                                                    start: start,
                                                                    end: end,
                                                                    type: 'add'
                                                                },
                                                                type: "POST",
                                                                success: function (data) {
                                                                    displayMessage("Event Created Successfully");
                                
                                                                    calendar.fullCalendar('renderEvent',
                                                                        {
                                                                            id: data.id,
                                                                            title: title,
                                                                            start: start,
                                                                            end: end,
                                                                            allDay: allDay
                                                                        },true);
                                
                                                                    calendar.fullCalendar('unselect');
                                                                }
                                                            });
                                                        }
                                                    },
                                                    eventDrop: function (event, delta) {
                                                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                                                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                                
                                                        $.ajax({
                                                            url: SITEURL + '/transactAjax',
                                                            data: {
                                                                title: event.title,
                                                                start: start,
                                                                end: end,
                                                                id: event.id,
                                                                type: 'update'
                                                            },
                                                            type: "POST",
                                                            success: function (response) {
                                                                displayMessage("Event Updated Successfully");
                                                            }
                                                        });
                                                    },
                                                    eventClick: function (event) {
                                                        var deleteMsg = confirm("Do you really want to delete?");
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
                                                                    displayMessage("Event Deleted Successfully");
                                                                }
                                                            });
                                                        }
                                                    }
                                
                                                });
                                
                                });
                                
                                function displayMessage(message) {
                                    toastr.success(message, 'Event');
                                } 
                                
                            </script>

                        </section> 

                        @endif       
                        
                        <x-jet-button id="transact-button" type="submit" wire:click="">
                            {{ __('Continue') }}
                        </x-jet-button>  
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>