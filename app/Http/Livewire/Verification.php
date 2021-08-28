<?php

namespace App\Http\Livewire;

use Auth;
use Nexmo;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



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

        try {
            $request_id = auth()->user()->phone_request;
            if ($request_id  == null) {
                $verification = Nexmo::verify()->start([
                    'number' => '+1'.(int) auth()->user()->phone_number,
                    'brand'  => 'Uconomy'
                ]);

                // session(['request_id' => $verification->getRequestId()]);
                $request_id = $verification->getRequestId();

                DB::table('users')->where('id', Auth::id())->update(['phone_request' => $request_id]);

                // dd(session('request_id'));
    
            } else {
                $client = $this->getClient();

                $response = $client->verify()->search($request_id);
                // dd($response);

                if ($response['status'] != 'IN PROGRESS') {
                    // dd($response['status']);
                    $this->cancel($client, $request_id);
                    // session(['request_id' => null]);
                    DB::table('users')->where('id', Auth::id())->update(['phone_request' => null]);

                    return $this->verifyPhone();
                }

                // dd($client);

            }
        
            return redirect('/phone/verify');
            
        } catch(\Exception $e){
            // if ($request_id == null) {
            // dd($e);
            return redirect('/dashboard?verified=0');
            // }
            // $client = $this->getClient();
            // $request_id = session('request_id');
            // $this->cancel($client, $request_id);
            // session(['request_id' => null]);
            
            // return $this->verifyPhone();
        } 

    }

    public static function getClient()
    {
        $basic  = new \Vonage\Client\Credentials\Basic(
            function_exists('env') ? env('NEXMO_KEY', '') : '', 
            function_exists('env') ? env('NEXMO_SECRET', '') : ''
        );
        
        $client = new \Vonage\Client(new \Vonage\Client\Credentials\Container($basic));

        return $client;
    }

    public function cancel($client, $request_id) {
        try {
            $result = $client->verify()->cancel($request_id);
        } catch(\Exception $e) {
            // dd($e);
            return redirect('/dashboard?verified=0');
            // sleep(30);
            // try {
            //     $result = $client->verify()->cancel($request_id);
            // } catch(\Exception $e2) {
            //     return redirect('/dashboard')->withFail('There was a problem with your verification. Please contact us if this issue persists.');
            // }
        }
    }


    public function render()
    {
        return view('livewire.verification');
    }
}
