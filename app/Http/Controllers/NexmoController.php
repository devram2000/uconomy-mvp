<?php

namespace App\Http\Controllers;

use Auth;
use Nexmo;
use App\Http\Livewire\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 



class NexmoController extends Controller
{

    // public function __construct(Verification $verification)
    // {
    //     parent::__construct();
    //     $this->verification = $verification;
    // }

    public function show()
    {
        return view('auth.verify-phone', [
            "phone_number"=>auth()->user()->phone_number
        ]);
    } 
    
    public function verify(Request $request)
    {

        $this->validate($request, [
            'code' => 'size:4',
        ]);
    
        try {
            $request_id = auth()->user()->phone_request;

            Nexmo::verify()->check(
                $request_id,
                $request->code
            );
            // Auth::loginUsingId($request->session()->pull('verify:user:id'));
            $date = date_create();
            DB::table('users')->where('id', Auth::id())->update(['phone_verified_at' => date_format($date, 'Y-m-d H:i:s')]);
            // session(['request_id' => null]);
            DB::table('users')->where('id', Auth::id())->update(['phone_request' => null]);


            return redirect('/dashboard?verified=2');
        } catch (Nexmo\Client\Exception\Request $e) {
            return redirect()->back()->withErrors([
                'code' => $e->getMessage()
            ]);
    
        }
    }

    public function reset(){
        $request_id = auth()->user()->phone_request;

        if ($request_id == null) {
            return redirect('/dashboard?verified=0');
        }

        $client = Verification::getClient();


        // $response = $client->verify()->search($request_id);

        // if ($response['status'] == 'IN PROGRESS') {
        try {
            $result = $client->verify()->cancel($request_id);
        } catch(\Exception $e) {
            if (Str::contains($e->getMessage(), '30 seconds')) {
                return redirect()->back()->withErrors([
                    'code' => "You cannot reset within 30 seconds"
                ]);
            } else {
                return redirect('/dashboard?verified=0');
            }
        }

        $verification = Nexmo::verify()->start([
            'number' => '+1'.(int) auth()->user()->phone_number,
            'brand'  => 'Uconomy'
        ]);

        $request_id = $verification->getRequestId();

        DB::table('users')->where('id', Auth::id())->update(['phone_request' => $request_id]);

        return redirect('/phone/verify');

    }
}


