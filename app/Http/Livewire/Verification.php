<?php

namespace App\Http\Livewire;

use Livewire\Component;


class Verification extends Component
{
    public $user;

    public function verifyEmail()
    {
        // $user->sendEmailVerificationNotification();
        // route('verification.send');
        if (auth()->user()->email_verified_at == null) {
            auth()->user()->sendEmailVerificationNotification();
        }
        return redirect()->to('/email/verify');
    }
    
    public function verifyPhone()
    {
        // $user->sendEmailVerificationNotification();
        return redirect()->to('/phone/verify');
    }


    public function render()
    {
        return view('livewire.verification');
    }
}
