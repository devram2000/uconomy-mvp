<?php

namespace App\Http\Livewire;
use Auth;
use Livewire\Component;

class Onboarding extends Component
{
    public $kyc = null;
    public $plaid = null;
    public $debit = null;

    public function createPayment() {
        redirect('payment');
    }

    public function render()
    {
        $this->kyc = Auth::user()->kyc;
        $this->plaid = Auth::user()->plaid;
        $this->debit = Auth::user()->debit;
        return view('livewire.onboarding');
    }
}
