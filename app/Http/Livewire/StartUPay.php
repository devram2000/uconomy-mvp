<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StartUPay extends Component
{
    public function render()
    {
        return view('livewire.start-u-pay');
    }

    public function redirectUPay() {
        return redirect('/upay'); 
    }
}
