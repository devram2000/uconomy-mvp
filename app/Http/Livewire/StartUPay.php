<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Fee;
use Livewire\Component;


class StartUPay extends Component
{
    public $transactions, $payments, $fees, $events_and_fees, $remaining_balance, $spending_amount, $has_transactions;

    public function render()
    {
        $this->transactions = Transaction::where('user', Auth::id())->get();

        $amount = 0;
        foreach ($this->transactions as $t) {
            $amount += $t->remaining_balance;
        }
        $this->remaining_balance = $amount;

        $this->spending_amount = Auth::user()->limit - $this->remaining_balance;

        $this->has_transactions = count($this->transactions);

        $this->fees = Fee::where('user', Auth::id())
                        ->get(['id', 'amount', 'date'])->toArray();

        $this->payments = Payment::where('user', Auth::id())
                        ->get(['id', 'amount', 'date'])->toArray();
        // $this->events_and_fees = array_merge($this->fees, $this->payments)
        // $event_count = count($this->fees) + count($this->payments)
        $view_id = 0;
        for ($i = 0; $i < count($this->fees); $i++) {
            $this->fees[$i]["borderColor"] = "black";
            $this->fees[$i]["color"] = "white";
            $this->fees[$i]["id"] = $view_id;
            $this->fees[$i]["title"] = $this->fees[$i]["amount"];
            $this->fees[$i]["start"] = $this->fees[$i]["date"];

            $view_id++;
        }

        for ($j = 0; $j < count($this->payments); $j++) {
            $this->payments[$j]["id"] = $view_id;
            $this->payments[$j]["title"] = $this->payments[$j]["amount"];
            $this->payments[$j]["start"] = $this->payments[$j]["date"];

            $view_id++;
        }

        $this->events_and_fees = array_merge($this->fees, $this->payments);

        return view('livewire.start-u-pay');
    }

    public function redirectUPay() {
        return redirect('transact'); 
    }
}
