<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Auth;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class RescheduleComponent extends Component
{
    public $payments, $events, $remaining_balance;
    public $window;


        /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
           case 'add':
              $event = Event::create([
                  'title' => $request->title,
                //   'title' => $this->amount,
                  'start' => $request->start,
                  'user' => Auth::id(),
                  'fee' => false,
              ]);
  
              return response()->json($event);
             break;
  
        //    case 'update':
        //       $event = Event::find($request->id)->update([
        //         'title' => $request->title,
        //         'start' => $request->start,
        //         'user' => Auth::id(),
        //     ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Event::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }


    public function render()
    {
        Event::where('user', Auth::id())->delete();
        $this->window = Auth::user()->window;

        $this->transactions = Transaction::where('user', Auth::id())->get();

        $transaction;
        foreach ($this->transactions as $t) {
            // $amount += $t->remaining_balance;
            if ($t->remaining_balance != 0) {
                $transaction = $t;
            }
        }

        $this->remaining_balance = $t->remaining_balance;

        $payments = Payment::where('transaction', $transaction->id)
                ->whereDate('date', '>=', date('Y-m-d'))
                ->get(['id', 'amount', 'date'])->toArray();


        for ($j = 0; $j < count($payments); $j++) {
            $event = Event::create([
                'title' => $payments[$j]["amount"],
                'start' => $payments[$j]["date"],
                'user' => Auth::id(),
                'fee' => false,
            ]);

            $this->remaining_balance -=  $payments[$j]["amount"];

        }

        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();


        return view('livewire.reschedule-component');
    }
}
