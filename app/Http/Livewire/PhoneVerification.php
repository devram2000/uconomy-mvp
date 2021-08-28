<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use Nexmo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class PhoneVerification extends Component
{
    public $code;

    protected $rules = [
        'code' => 'size:4',
    ];


    public function render()
    {
        return view('livewire.phone-verification', [
            "phone_number"=>auth()->user()->phone_number
        ]);
    }
    
    public function submit()
    {

        // $this->validate($request, [
        //     'code' => 'size:4',
        // ]);

        $this->validate();

    
        try {
            Nexmo::verify()->check(
                session('request_id'),
                $this->code
            );
            // Auth::loginUsingId($request->session()->pull('verify:user:id'));
            $date = date_create();
            DB::table('users')->where('id', Auth::id())->update(['phone_verified_at' => date_format($date, 'Y-m-d H:i:s')]);
            session(['request_id' => null]);

            return redirect('/dashboard?verified=2');
        } catch (Nexmo\Client\Exception\Request $e) {
            dd($e);
            return redirect()->back()->withErrors([
                'code' => $e->getMessage()
            ]);
    
        }
    }

    public function test() {
        $hi = "true";
        dd($hi);
    }
}


