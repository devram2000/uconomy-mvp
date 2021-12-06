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
                <section id="admin">
                        <div>
                        <h1 class ="m-0"> 
                        {{ __('All Payments') }}
                        </h1>{{ __('(User ID: Amount)') }}</div></br>
                        <div class="calendar" id="total"> 
                                    </div></br></br>
                                    <script>
                                        $(document).ready(viewDates());

                                    
                                        
                                        function viewDates() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#total').fullCalendar({
                                                            events: @json($this->combined_events),
                                                            editable: false,
                                                            eventColor: '#7cd9edff',
                                                            height: 'auto',

                                                            defaultView: 'basicWeek',
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
                    <h1 class ="m-0">{{ __('Users') }}</h1>
                    (ranked by latest transaction)
                    @foreach($users as $u)
                        <h4 class="mt-2"> 
                        {{ $u->id }}{{ __(': ') }}{{ $u->name }}
                        </h4></br>
                        <div class="calendar" id="{{ $u->id }}"> </div>

                        @if ($ids[$u->id] != NULL)  
                         
                            <div class="mt-2" id = "license-images">
                                <img src="/storage/ids/{{ $ids[$u->id]['photo1'] }}" id="license-image" />
                                @if ($ids[$u->id]['photo2'] != NULL)  
                                <img src="/storage/ids/{{ $ids[$u->id]['photo2'] }}" id="license-image" />
                                @endif
                                    
                            </div>

                           

                        @endif
                                
                                </br></br>
                                    <script>
                                        $(document).ready(viewDates());

                                    
                                        
                                        function viewDates() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#{{ $u->id }}').fullCalendar({
                                                            events: @json($this->events[$u->id]),
                                                            editable: false,
                                                            eventColor: '#7cd9edff',
                                                            height: 'auto',

                                                            defaultView: 'basicWeek',
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

                    @endforeach
                </section>
            </div>
        </div>
    </div>
</div>