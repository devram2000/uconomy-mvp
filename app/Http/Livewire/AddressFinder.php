<?php

namespace App\Http\Livewire;
use Auth;

use Livewire\Component;
use App\Models\Address;


class AddressFinder extends Component
{
    public $address, $zipCode, $apt, $country, $state, $city, $saved;

    public function __construct() {   
        $previous_address = Address::where('user', Auth::id())->first();
        if($previous_address != null) {
            $this->address =  $previous_address->address;
            $this->zipCode =  $previous_address->zipCode;
            $this->apt =  $previous_address->apt;
            $this->country =  $previous_address->country;
            $this->state =  $previous_address->state;
            $this->city =  $previous_address->city;
        }

        $this->saved = false;
    }

    public function addressUpdate() {
        $validatedData = $this->validate([
            'address' => 'required',
            'zipCode' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);
        $previous_address = Address::where('user', Auth::id())->first();
        if($previous_address == null) {
            $address = Address::create([
                'user' => Auth::id(),
                'address' => $this->address,
                'zipCode' => $this->zipCode,
                'apt' => $this->apt,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
            ]);
        } else {
            $previous_address->update(
                [
                    'address' => $this->address,
                    'zipCode' => $this->zipCode,
                    'apt' => $this->apt,
                    'country' => $this->country,
                    'state' => $this->state,
                    'city' => $this->city,
                ]);
        }

        $this->saved = true;

      
    }

    public function render()
    {

        return view('livewire.address');
    }
}
