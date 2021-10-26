<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Acaronlex\LaravelCalendar\Calendar;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public $events;
    
    public function index() {

        $events[] = \Calendar::event(
            'Event One', //event title
            false, //full day event?
            '2021-09-11T0800', //start time (you can also use Carbon instead of DateTime)
            '2021-09-12T0800', //end time (you can also use Carbon instead of DateTime)
            0 //optionally, you can specify an event ID
        );

        $events[] = \Calendar::event(
            "Valentine's Day", //event title
            true, //full day event?
            new \DateTime('2021-09-14'), //start time (you can also use Carbon instead of DateTime)
            new \DateTime('2021-09-14'), //end time (you can also use Carbon instead of DateTime)
            'stringEventId' //optionally, you can specify an event ID
        );


        $calendar = new Calendar();
                $calendar->addEvents($events)
                ->setOptions([
                    'locale' => 'en',
                    'firstDay' => 0,
                    'displayEventTime' => true,
                    'selectable' => true,
                    'editable' => true,
                    'initialView' => 'dayGridMonth',
                    'headerToolbar' => [
                        'end' => 'today prev,next dayGridMonth dayGridWeek'
                    ]
                ]);
                $calendar->setId('1');
                $calendar->setCallbacks([
                    'select' => 'function(selectionInfo){
                        alert(selectionInfo.startStr);
                    }',
                    'eventClick' => 'function(info){
                        var SITEURL = "{{ url(\'/\') }}";
                        $.ajax({
                            url: \'/calendarAjax\',
                            data: {
                                id: info.event.id,
                            },
                            type: "POST",
                           
                        });
                    }'
                ]);

        return view('components.calendar', compact('calendar'));
    }

    public function ajax(Request $request) {
        Log::info("test");
        return response()->json('hello');

    }
}
