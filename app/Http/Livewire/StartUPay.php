<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use Livewire\Component;


class StartUPay extends Component
{
    public $transactions, $remaining_balance, $spending_amount, $has_transactions;

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
        
        return view('livewire.start-u-pay');
    }

    public function redirectUPay() {
        return redirect('transact'); 
    }
}
