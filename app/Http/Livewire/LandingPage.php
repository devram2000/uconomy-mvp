<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public function redirectSignup() {
        return redirect('/register'); 
    }
    public function redirectLogin() {
        return redirect('/login'); 
    }

    public function redirectPrivacy() {
        return redirect('/privacy-policy'); 
    }
    public function redirectTerms() {
        return redirect('/terms-of-service'); 
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('layouts.home_layout');
    }
}
