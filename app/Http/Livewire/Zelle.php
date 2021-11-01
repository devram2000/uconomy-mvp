<?php

namespace App\Http\Livewire;
use Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;


class Zelle extends Component
{
    public $phone_number, $email, $zelle, $pzelle, $saved;

    public function zelleUpdate() {

        $validatedData = $this->validate([
            'zelle' => "required",
        ]);

        Auth::user()->update(['zelle' => $this->zelle]);
        $this->saved = true;

    }

    public function render()
    {
        $this->saved = false;
        $this->phone_number = Auth::user()->phone_number;
        $this->email = Auth::user()->email;
        $this->zelle = Auth::user()->zelle;
        return view('livewire.zelle');
    }
}
