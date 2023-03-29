<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;

class AddCard extends Component
{
    public $is_card_added;
    public $name;
    public $card_number;
    public $expiry;
    public $zip;

    public function createCard() {
        // $validatedData = $this->validate([
        //     'name' => 'required',
        //     'card_number' => 'required',
        //     'expiry' => 'required',
        //     'zip' => 'required|digits:5',
        // ]);
        $user = Auth::user();
        $user->stripe_id = ".";
        $user->save();
    }

    public function render()
    {
        $this->is_card_added = Auth::user()->stripe_id;
        return view('livewire.add-card');
    }
}
