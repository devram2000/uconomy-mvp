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

        if($response['kyc_status'] != 'ACCEPTED') {
            $response = SyncteraCalls::kyc($person_id);
        }

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

    public static function getTemplateID() {
        $template_id = null;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/accounts/templates');
        
        foreach ( $response['account_templates'] as $template) {
            if($template['name'] == "Uconomy Card Template") {
                $template_id = $template['id'];
            }
        }

        if ($template_id == null) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/accounts/templates', [
                'is_enabled' => true,
                'name' => 'Uconomy Card Template',
                'template' => [
                    'account_type' => "CHECKING",
                    'bank_country' => 'US',
                    'currency' => 'USD',
                    "is_ach_enabled" => true,
                    "is_card_enabled" => true,
                    "is_p2p_enabled" => true,            
                ],
            ]);
            $template_id = $response['id'];
        }

        return $template_id;
    }

    public static function getAccount($synctera_id, $template_id) {
        $account = null;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/accounts');


        foreach ( $response['accounts'] as $a) {
            if($a['customer_ids'][0] == $synctera_id) {
                $account = $a;
            }
        }

        if ($account == null) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/accounts', [
                'account_template_id' => $template_id,
                'relationships' => [
                    [
                        'relationship_type' => "ACCOUNT_HOLDER",
                        'person_id' => $synctera_id,    
                    ]
                ],
            ]);
            $account = $response;
        }

        return $account;

    }

    public static function getCard($synctera_id, $account_id) {
        $card = null;
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/cards', [
            'account_id' => $account_id,
        ]);

        if (!empty($response['cards'])) {
            $card = $response['cards'][0];
        }


        if ($card == null) {
            $card =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/cards', [
                'account_id' => $account_id,
                'customer_id' => $synctera_id,
                'card_product_id' => 'ed892847-8bed-4a5f-ac04-6c4dddc00e92',
                'form' => 'VIRTUAL',
                'type' => 'DEBIT',
            ]);

   
        }

        return $card;

    }

    public static function getClientToken($card_id) {
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/cards/' . $card_id . '/client_token');

        
        return $response['client_token'];

    }
}
