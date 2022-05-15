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
                <section id="reschedule">
                <div id="payment-title"> 
                                            {{ __('Reschedule your Payment Dates') }} 
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
                                        
                                        var SITEURL = "{{ url('/') }}"
                                        var window = {{ $this->window }}

                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        })

                                        

                                    

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
</div>