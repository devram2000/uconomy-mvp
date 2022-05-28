<?php

namespace App\Http\Livewire;
use Auth;
use Livewire\Component;

class Onboarding extends Component
{
    public $kyc = null;
    public $plaid = null;

    public function render()
    {
        $this->kyc = Auth::user()->kyc;
        $this->plaid = Auth::user()->plaid;
        return view('livewire.onboarding');
    }
}
