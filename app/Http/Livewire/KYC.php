<?php

namespace App\Http\Livewire;
use Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class KYC extends Component
{
    public $first_name, $last_name, $date_of_birth, $address, $city, $state, $zip, $ssn;
    public $address2 = "";
    public $message = "";

    public function createCustomer() {
        $validatedData = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'ssn' => 'required|regex:/^\d{3}-\d{2}-\d{4}$/',
        ]);

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/customers', [
            'status' => 'ACTIVE',
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dob' => $this->date_of_birth,
            'legal_address' => [
                'address_line_1' => $this->address,
                'address_line_2' => $this->address2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->zip,
                'country_code' => 'US',
            ],
            'ssn' => $this->ssn,
            'email' => Auth::user()->email,
            'phone_number' => "+1" . Auth::user()->phone_number,
        ]);

        if($response->failed()) {
            dd($response->body());
        }

        $customer_id = $response['id'];

        // $customer_id = "800baad1-e3d5-4794-b1b0-d924a2939d72";
        // $response =  Http::withHeaders([
        //     'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        // ])->get('https://api-sandbox.synctera.com/v0/customers/' . $customer_id);
        // dd($response->body());

        // $customer_id = "800baad1-e3d5-4794-b1b0-d924a2939d72";
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/customers/' . $customer_id . '/verify', [
            'verification_type' => ['kyc'],
            'customer_consent' => true,
        ]);

        DB::table('users')->where('id', Auth::id())->update(['synctera_id' => $customer_id]);

        $this->message = $response->body();

    }

    public function render()
    {
        return view('livewire.k-y-c');
    }
}
