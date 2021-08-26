<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RegisterForm extends Component
{
    public $password_show = "password";

    // public function show()
    // {
    //     if (
    //         $password_show == "password"
    //     ) {
    //         $password_show = "text";
    //     } else {
    //         $password_show = "password";
    //     }
    // }

    public function set()
    {
        $this->password_show = "text";


    }

    public function render()
    {
        return view('livewire.register-form');
    }
}
