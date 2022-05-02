<?php

namespace App\Http\Livewire;
use Auth;


use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class VirtualCard extends Component
{
    public $message = "";
    public $pinWidgetURL = null;
    public $cardWidgetURL = null;
    public $client_token = null;
    public $account_id = null;
    public $card_id = null;
    public $balance = 0;

    public function createCard() {      

        
        // $card =  Http::withHeaders([
        //     'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        // ])->get(env('SYNCTERA_API') . '/v0/cards/:' . $card_id);

        // dd($card->body());
        
        // if (!$card['is_pin_set']) {
        if (false) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->get(env('SYNCTERA_API') . '/v0/cards/card_widget_url', [
                'account_id' => $account_id,
                'card_id' => $this->card_id,
                'customer_id' => Auth::user()->synctera_id,
                'widget_type' => 'set_pin',
            ]);
    
            $this->pinWidgetURL = $response['url'];
    
        }



       


        if (false) {

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts/link_tokens', [
            'customer_id' => Auth::user()->synctera_id,
            'client_name' => "Test Test",
            "country_codes" => [
                "US"
              ],
              "type"  => "DEPOSITORY",
              "language" => "EN",
              "redirect_uri" => "https://oauth1.example.com/oauth-page.html"
        ]);

        dd($response->body());
        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/external_accounts', [
             "account_identifiers" => [
                "number" => "78277122"
             ],
             "account_owner_names" => [
                "Test Test"
              ],
              'customer_id' => Auth::user()->synctera_id,
              'customer_type' => "PERSONAL",
              'type' => "CHECKING",
              "verification" => [
                "creation_time" => "2022-01-01T00:00:00.000Z",
                "last_updated_time" => "2022-01-01T00:00:00.000Z",            
                "status" => "VERIFIED",
                "vendor" => "MANUAL",
             ],
             "routing_identifiers"  => [
                "ach_routing_number" => "756392185",
                "bank_countries" => [
                "US"
                ],
                "bank_name" => "Chase",
             ],
        ]);

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->patch(env('SYNCTERA_API') . '/v0/external_accounts/' . '33bc1a8b-f8b5-453b-afa6-dca41a6e13d0', [
            "verification" => [
                "creation_time" => "2022-01-01T00:00:00.000Z",
                "last_updated_time" => "2022-01-01T00:00:00.000Z",            
                "status" => "VERIFIED",
                "vendor" => "MANUAL",
             ],
        ]);


        
    }

    $response =  Http::withHeaders([
        'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
    ])->post(env('SYNCTERA_API') . '/v0/cards/transaction_simulations/authorization', [
        'card_id' => $this->card_id,
        'amount' => 10.00 * 100,
        'mid' => "12345",
    ]);

   

       



        $this->message =   $response->body();
        
    }
    
    public function render()
    {
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

        $account = null;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/accounts');


        foreach ( $response['accounts'] as $a) {
            if($a['customer_ids'][0] == Auth::user()->synctera_id) {
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
                        'person_id' => Auth::user()->synctera_id,    
                    ]
                ],
            ]);
            $account = $response;
        }

        $this->account_id = $account['id'];

        foreach ( $account['balances'] as $b) {
            if($b['type'] == 'AVAILABLE_BALANCE') {
                $this->balance = $b['balance'] / 100;
            }
        }

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/cards', [
            'account_id' => $this->account_id,
        ]);

        if (!empty($response['cards'])) {
            $this->card_id = $response['cards'][0]['id'];
        }


        if ($this->card_id == null) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/cards', [
                'account_id' => $account_id,
                'customer_id' => Auth::user()->synctera_id,
                'card_product_id' => 'ed892847-8bed-4a5f-ac04-6c4dddc00e92',
                'form' => 'VIRTUAL',
                'type' => 'DEBIT',
            ]);

            $this->card_id = $response['id'];    
        }

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->post(env('SYNCTERA_API') . '/v0/cards/' . $this->card_id . '/client_token');

        $this->client_token = $response['client_token'];




        return view('livewire.virtual-card');
    }
}
