<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SyncteraCalls extends Controller
{
    public static function createPerson($first_name, $last_name, $dob, $email, $phone_number) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/persons', [
            'status' => 'PROSPECT',
            "is_customer" => true,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'dob' => $dob,
            'email' => $email,
            'phone_number' => $phone_number,

        ]);

        if($response->failed()) {
            dd($response->body());
        }

        return $response['id'];

    }

    public static function updateAddressSSN($person_id, $address, $address2, $city, $state, $zip, $ssn) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->patch(env('SYNCTERA_API') . '/v0/persons/' . $person_id, [
            'status' => 'ACTIVE',
            'legal_address' => [
                'address_line_1' => $address,
                'address_line_2' => $address2,
                'city' => $city,
                'state' => $state,
                'postal_code' => $zip,
                'country_code' => 'US',
            ],
            'ssn' => $ssn,
        ]);

        if($response->failed()) {
            dd($response->body());
        } 



        return $response;

    }

    public static function kyc($person_id) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/customers/' . $person_id . '/verify', [
            'verification_type' => [
                'fraud', 'synthetic', 'emailrisk', 'phonerisk', 'addressrisk', 'kyc', 
                //   'documentverification', 
                'social', 'watchliststandard', 'alertlist', 'decision'
            ],
            'customer_consent' => true,
        ]);

        return $response;

    }

    public static function createLink($person_id) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/link_tokens', [
            'customer_id' => $person_id,
            'client_name' => "Uconomy",
            "country_codes" => [
                "US"
              ],
              "type"  => "DEPOSITORY",
              "language" => "EN",
            //   "redirect_uri" => "https://oauth1.example.com/oauth-page.html"
        ]);

        return $response['link_token'];
    }

    public static function createAccount($person_id, $public_token, $metadata) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/access_tokens', [
            'customer_id' => $person_id,
            'vendor_public_token' => $public_token,
            'vendor_institution_id' => $metadata['institution']['institution_id'],
        ]);
        

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/add_vendor_accounts', [
            'customer_id' => $person_id,
            "customer_type"  => "PERSONAL",
            "vendor"  => "PLAID",
            // "verify_owner"  => true,
            'vendor_access_token' => $response['vendor_access_token'],
            "vendor_account_ids" => [
                $metadata['account_id']
              ],        
            ]);

        return $response;
    }
}
