<div>
  <div class="calendar" id="{{ $this->name }}"></div>
  <script>
    document.addEventListener('livewire:load', viewDates);
    document.addEventListener('livewire:update', viewDates);

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
      });

        var buttonStyles = 'font-size: 12px; padding-left: 0px; padding-right: 0px; padding-top:4px;';
        var buttonStylesLargerScreens = '@media (min-width: 640px) { font-size: 16px; padding-left: 8px; padding-right: 8px; }';
        var cellStyles = '.fc-event-container { white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }';

        $('.fc-today-button').attr('style', buttonStyles);
        $('.fc-month-button').attr('style', buttonStyles);
        $('.fc-basicWeek-button').attr('style', buttonStyles);

        $('head').append('<style>' + buttonStylesLargerScreens + '</style>');
        $('head').append('<style>' + cellStyles + '</style>');
    }
  </script>
</div>
