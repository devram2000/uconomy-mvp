<?php

namespace App\Http\Livewire;

use Auth;


use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Plaid extends Component
{
    public $message = "";
    public $link_token = null;


    public function createLink() {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/link_tokens', [
            'customer_id' => Auth::user()->synctera_id,
            'client_name' => "Uconomy",
            "country_codes" => [
                "US"
              ],
              "type"  => "DEPOSITORY",
              "language" => "EN",
            //   "redirect_uri" => "https://oauth1.example.com/oauth-page.html"
        ]);

        $this->link_token = $response['link_token'];

        
    }

    public function createAccount($public_token, $metadata) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/access_tokens', [
            'customer_id' => Auth::user()->synctera_id,
            'vendor_public_token' => $public_token,
            'vendor_institution_id' => $metadata['institution']['institution_id'],
        ]);
        

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/add_vendor_accounts', [
            'customer_id' => Auth::user()->synctera_id,
            "customer_type"  => "PERSONAL",
            "vendor"  => "PLAID",
            // "verify_owner"  => true,
            'vendor_access_token' => $response['vendor_access_token'],
            "vendor_account_ids" => [
                $metadata['account_id']
              ],        
            ]);
        $this->message = $response->body();
    }


    public function render()
    {
        return view('livewire.plaid');
    }
}
