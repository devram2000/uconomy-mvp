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
    public $reschedule;
    public $transactions;

    public function rescheduleSubmit() {
        $this->events = Event::where('user', Auth::id()) 
            ->where('fee', false)
            ->get(['id', 'transaction', 'title', 'start'])->toArray();
        Payment::where('user', Auth::id())->where('transaction', null)->delete();

        $null_events = array();
        

        foreach($this->events as $event) {
            $transaction_id = null;
            $reschedule_number = null;
            if ($event['transaction'] != null) {
                $transaction_id = $event['transaction'];
                $reschedule_number = $this->reschedule[$transaction_id];

                if($reschedule_number == null) {
                    $reschedule_number = 0;
                }
        
                $reschedule_number++;

                // if(Transaction::where('id', $transaction_id)->get(['reschedule'])[0]['reschedule'] 
                //     != $reschedule_number) {
                //         $result = Transaction::where('id', $transaction_id)->update([
                //             'reschedule' => $reschedule_number,
                //         ]);
                // }
                Payment::create([
                    'user' => Auth::id(),
                    'transaction' => $transaction_id,
                    'amount' => $event['title'],
                    'date' => $event['start'],
                    'completed' => false,
                    'reschedule' => $reschedule_number,
                ]);
            } else {
                $null_events[] = $event;
            } 

            $transactions = Transaction::where('user', Auth::id())->get();

            foreach($transactions as $t) {
                $reschedule_number = $this->reschedule[$t['id']];

                if($reschedule_number == null) {
                    $reschedule_number = 0;
                }
        
                $reschedule_number++;

                $result = $t->update([
                    'reschedule' => $reschedule_number,
                ]);
            }
        }

        foreach($null_events as $event) {
            Payment::create([
                'user' => Auth::id(),
                'transaction' => null,
                'amount' => $event['title'],
                'date' => $event['start'],
                'completed' => false,
                'reschedule' => null,
            ]);
        }
     

        Event::where('user', Auth::id())->delete();

        return redirect('home'); 

    }


    public function render()
    {
        Event::where('user', Auth::id())->delete();
        $this->window = Auth::user()->window;

        if($this->window == null) {
            $this->window = 3;
            $user = Auth::user();
            $user->window = $this->window;
            $user->save();
    
        }

        $transacts = Transaction::where('user', Auth::id())->get();
        $this->transactions = array();
        $this->payments = array();
        $this->reschedule = array();
        $amount = 0;
        foreach ($transacts as $t) {
            if ($t->remaining_balance != 0) {
                $amount += $t->remaining_balance;
                $this->transactions[] = $t;
                $pays = Payment::where('transaction', $t->id)
                            ->where('reschedule', $t->reschedule)
                            ->where('completed', 0)
                            ->whereDate('date', '>=', date('Y-m-d'))
                            ->get(['id', 'transaction', 'reschedule', 'amount', 'date'])->toArray();
                $this->payments = array_merge($this->payments, $pays);
                $this->reschedule[$t->id] = $t->reschedule;
            }
        }

        $pays = Payment::where('transaction', null)
            ->where('user', Auth::id())
            ->where('completed', 0)
            ->get(['id', 'transaction', 'reschedule', 'amount', 'date'])->toArray();
        $this->payments = array_merge($this->payments, $pays);

        $this->remaining_balance = $amount;

        // $this->reschedule_number = $this->transaction->reschedule;

        // $payments = Payment::where('transaction', $this->transaction->id)
        //         ->where('reschedule', $this->reschedule_number)
        //         ->whereDate('date', '>=', date('Y-m-d'))
        //         ->get(['id', 'amount', 'date'])->toArray();

        
        for ($j = 0; $j < count($this->payments); $j++) {
            $event = Event::create([
                'title' => $this->payments[$j]["amount"],
                'start' => $this->payments[$j]["date"],
                'transaction' => $this->payments[$j]["transaction"],
                'user' => Auth::id(),
                'fee' => false,
            ]);
            $this->remaining_balance -=  $this->payments[$j]["amount"];

        }

        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();


        return view('livewire.reschedule-component');
    }
}
