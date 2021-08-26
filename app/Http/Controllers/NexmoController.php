<?php

namespace App\Http\Controllers;

use Auth;
use Nexmo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;


class NexmoController extends Controller
{
    private function getClient()
    {
        $minutes = 10; // set here
        $basic  = new \Vonage\Client\Credentials\Basic(
            function_exists('env') ? env('NEXMO_KEY', '') : '', 
            function_exists('env') ? env('NEXMO_SECRET', '') : ''
        );
        
        $client = new \Vonage\Client(new \Vonage\Client\Credentials\Container($basic));

        return $client;
    }

    public function show()
    {
        if(auth()->user()->phone_verified_at != null) {
            return redirect('/dashboard');
        }
        try {
            
            $client = $this->getClient();
           
            $request = new \Vonage\Verify\Request('+1'.(int) auth()->user()->phone_number, "Uconomy");
            $response = $client->verify()->start($request);
            $request_id = $response->getRequestId();


            session(['request_id' => $request_id]);

        } catch(\Exception $e){
            
            $request_id = session('request_id');

            if ($request_id == null) {
                return redirect('/dashboard')->withErrors(['There was a problem with your verification. Please contact us if this issue persists.']);
            }

            // $this->cancel($client, $request_id);
            // $this->show();
        } 
        return view('auth.verify-phone', [
            "phone_number"=>auth()->user()->phone_number,
            "error" => false
        ]);
    } 

    public function showError() {
        return view('auth.verify-phone', [
            "phone_number" => auth()->user()->phone_number,
            "error" => true
        ]);

    }
    
    public function verify(Request $request)
    {
        try {

            $validated = $request->validate([
                'code' => 'size:10'
            ]);
        

            $request_id = session('request_id');
            $client = $this->getClient();


            $validated = $request->validate([
                'title' => 'required|unique:posts|max:255',
                'body' => 'required',
            ]);
            
            try {
                $result = $client->verify()->check($request_id, $request->code);
                echo "Request has a status of " . $result->getStatus() . PHP_EOL;
            }  catch(\Exception $e){
                this->showError();
                // return Redirect::back()->withErrors(['Incorrect code. Please try again!']);
            } catch (\Vonage\Client\Exception\Request $e) {
                this->showError();
            } catch (\Vonage\Client\Exception\Server $e) {
                this->showError();
            }

            // dd($e);
            // return Redirect::back()->withErrors(['Incorrect code. Please try again!']);
            

            $date = date_create();
            DB::table('users')->where('id', Auth::id())->update(['phone_verified_at' => date_format($date, 'Y-m-d H:i:s')]);
        
        }  catch(\Exception $e){
            // dd($e);
            $client = $this->getClient();
            $request_id = session('request_id');

            $this->cancel($client, $request_id);
            $this->show();
        }

        return redirect('/dashboard')->withErrors(['Phone Verification Complete!']);
    }

    public function cancel($client, $request_id) {
        try {
            $result = $client->verify()->cancel($request_id);
        } catch(\Exception $e) {
            sleep(30);
            try {
                $result = $client->verify()->cancel($request_id);
            } catch(\Exception $e2) {
                return redirect('/dashboard')->withErrors(['There was a problem with your verification. Please contact us if this issue persists.']);
            }
        }
    }
}


