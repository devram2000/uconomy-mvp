<div class="calendar w-full" id="{{ $this->name }}"> 
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
            events: @json($events_and_fees),
            editable: false,
            eventColor: '#7cd9edff',
            height: 'auto',

            defaultView: 'month',
            header: {
                left: 'title',
                center: '',
                right: 'today prev,next month basicWeek'
            },

            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: false,
            windowResize: function(view) {
                $('#{{ $this->name }}').fullCalendar('option', 'height', 'auto');
            },
            eventBorderColor: 'black',
            eventTextColor: 'black',
            eventBackgroundColor: 'white',
            eventBorderWidth: 1,
        });
    }
</script>
