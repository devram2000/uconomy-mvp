<?php

namespace App\Http\Livewire;
use Auth;
use Livewire\Component;
use App\Models\DefaultSchedule;

class Onboarding extends Component
{
    public $kyc = null;
    public $plaid = null;
    public $debit = null;
    public $schedule = null;

    public function createPayment() {
        redirect('payment');
    }

    public function redirectHome() {
        return redirect('home'); 
    }

    public function render()
    {
        $this->kyc = Auth::user()->kyc;
        $this->plaid = Auth::user()->plaid;
        $this->debit = Auth::user()->debit;
        $this->schedule = DefaultSchedule::where('user', Auth::id())->first();
        return view('livewire.onboarding');
    }
}
