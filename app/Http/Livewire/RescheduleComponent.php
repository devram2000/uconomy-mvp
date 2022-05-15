<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Auth;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;


class RescheduleComponent extends Component
{
    public $payments, $fees, $events_and_fees, $remaining_balance;
    public $window;


    public function render()
    {
        Event::where('user', Auth::id())->delete();
        $this->window = Auth::user()->window;

        $fees = Fee::where('user', Auth::id())
        ->get(['id', 'amount', 'date'])->toArray();

        $payments = Payment::where('user', Auth::id())
                ->get(['id', 'amount', 'date'])->toArray();

        for ($i = 0; $i < count($fees); $i++) {
            $event = Event::create([
                'title' => $fees[$i]["amount"],
                'start' => $fees[$i]["date"],
                'user' => Auth::id(),
                'fee' => true,
            ]);

        }

        for ($j = 0; $j < count($payments); $j++) {
            $event = Event::create([
                'title' => $payments[$j]["amount"],
                'start' => $payments[$j]["date"],
                'user' => Auth::id(),
                'fee' => false,
            ]);

        }

        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();

        $this->fees = Event::where('user', Auth::id()) 
                                ->where('fee', true)
                                
                                ->get(['id', 'title', 'start'])->toArray();

        $fee_view =$this->fees;

        for ($i = 0; $i < count($fee_view ); $i++) {
            $fee_view [$i]["borderColor"] = "black";
            $fee_view [$i]["color"] = "white";
        }
       

        $this->events_and_fees = array_merge($fee_view , $this->events);

        return view('livewire.reschedule-component');
    }
}
