<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddIdentity extends Component
{
    public $identity;
    
    public function render()
    {
        return view('livewire.add-identity');
    }
}
