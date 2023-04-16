<div>
<div class="calendar" id="{{ $this->name }}"> 
                                    </div>
                                    <script>
                                        document.addEventListener('livewire:load', viewDates());
                                        document.addEventListener('livewire:update', viewDates());
                                    
                                        
                                        function viewDates() {
                                        
                                        var SITEURL = "{{ url('/') }}";
                                        
                                        $.ajaxSetup({
                                            headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        

                                    

                                        var calendar = $('#{{ $this->name }}').fullCalendar({
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
</div>