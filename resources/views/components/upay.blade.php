<x-app-layout>
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
            {{ __('UPay') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
               
                <div class="md:p-6 sm:px-20 bg-white border-b border-gray-200">


                    <div id='calendar'></div>

                    
                    <script>
                        $(document).ready(function () {
                           
                        var SITEURL = "{{ url('/') }}";
                          
                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                          
                        var calendar = $('#calendar').fullCalendar({
                                            events: SITEURL + "/upay",
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
                                                var title = prompt('Event Title:');
                                                if (title) {
                                                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                                                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                                                    $.ajax({
                                                        url: SITEURL + "/upayAjax",
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
                                                    url: SITEURL + '/upayAjax',
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
                                                        url: SITEURL + '/upayAjax',
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
                    
                </div>
            </div>
        </div>
    </div>

       
</x-app-layout>
