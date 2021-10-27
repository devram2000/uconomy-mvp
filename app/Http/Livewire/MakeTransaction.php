<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use Livewire\Component;

class MakeTransaction extends Component
{
    public $amount;
    public $category;
    public $description;
    public $zelle;
  
    public function submit()
    {
        $transactions = Transaction::where('user', Auth::id())->get();

        $amount = 0;
        foreach ($transactions as $t) {
            $amount += $t->remaining_balance;
        }
        $remaining_balance = $amount;

        $spending_amount = Auth::user()->limit - $remaining_balance;

        $limit = Auth::user()->limit;
        $validatedData = $this->validate([
            'amount' => "required|numeric|min:0|max:$spending_amount",
            'category' => 'required',
            'description' => 'required',
            'zelle' => 'required',
        ]);
        $flight = Transaction::create([
            'amount' => $this->amount,
            'remaining_balance' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'zelle' => $this->zelle,
            'user' => Auth::id(),
            'start_date' => date('Y-m-d H:i:s'),
            'due_date' => date('Y-m-d H:i:s', strtotime('+3 months')),
        ]);
        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.make-transaction');
    }
}
