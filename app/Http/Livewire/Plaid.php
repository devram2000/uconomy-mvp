<?php

namespace App\Http\Livewire;

use Auth;


use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SyncteraCalls;

class Plaid extends Component
{
    public $message = "";
    public $link_token = null;


    public function createLink() {
        $this->link_token = SyncteraCalls::createLink(Auth::user()->synctera_id);
        
    }

    public function createAccount($public_token, $metadata) {
        $response = SyncteraCalls::createAccount(Auth::user()->synctera_id, $public_token, $metadata);

        $this->message = $response->body();

        $user = Auth::user();
        $user->plaid = True;
        $user->save();

        return redirect('/user/profile'); 

    }


    public function render()
    {
        return view('livewire.plaid');
    }
}
