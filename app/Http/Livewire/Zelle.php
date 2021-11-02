<?php

namespace App\Http\Livewire;
use Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;


class Zelle extends Component
{
    public $phone_number, $email;
    public $saved = false;
    public $zelle;

    public function __construct()
    {   
        $this->zelle = Auth::user()->zelle;
    }
    

    public function zelleUpdate() {

        $validatedData = $this->validate([
            'zelle' => "required",
        ]);

        Auth::user()->update(['zelle' => $this->zelle]);
        $this->saved = true;

    }

    public function render()
    {
        $this->phone_number = Auth::user()->phone_number;
        $this->email = Auth::user()->email;
        return view('livewire.zelle');
    }

    // @if($zelle == null) 
    //         <select name="zelle" id="zelle" placeholder='' wire:model="zelle">
    //             <option value=null selected="selected">{{ __('') }}</option>
    //             <option value="email">{{ __('Email: ') }}{{ $email }}</option>
    //             <option value="phone_number">        {{ __('Phone Number: ') }}{{ $phone_number }}</option>
    //         </select>
    //         @elseif($zelle = "phone_number")
    //         {{ __('Phone Number: ') }}{{ $phone_number }}
    //         @elseif($zelle = "email")
    //         {{ __('Email: ') }}{{ $email }}
    //         @endif
}
