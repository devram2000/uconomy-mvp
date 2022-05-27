<?php

namespace App\Http\Livewire;
use Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SyncteraCalls;


class KYC extends Component
{
    public $address, $city, $state, $zip, $ssn;
    public $address2 = "";
    public $message = "";

    public function createPerson() {
        $validatedData = $this->validate([
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'ssn' => 'required|regex:/^\d{3}-\d{2}-\d{4}$/',
        ]);

        $person_id = Auth::user()->synctera_id;


        SyncteraCalls::updateAddressSSN($person_id, $this->address, $this->address2, $this->city, $this->state, $this->zip, $this->ssn);

        $this->message = SyncteraCalls::kyc($person_id)->body();

    }

    public function render()
    {
        return view('livewire.k-y-c');
    }
}
