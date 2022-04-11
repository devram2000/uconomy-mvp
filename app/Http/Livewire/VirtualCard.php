<?php

namespace App\Http\Livewire;
use Auth;


use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class VirtualCard extends Component
{
    public $message = "";

    public function createCard() {

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

        $account_id = null;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/accounts');


        foreach ( $response['accounts'] as $account) {
            if($account['customer_ids'][0] == Auth::user()->synctera_id) {
                $account_id = $account['id'];
            }
        }

        if ($account_id == null) {
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
            $account_id = $response['id'];
        }

      

        $card_id = null;

        $response =  Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
        ])->get(env('SYNCTERA_API') . '/v0/cards', [
            'account_id' => $account_id,
        ]);

        if (!empty($response['cards'])) {
            $card_id = $response['cards'][0]['id'];
        }

        if ($card_id == null) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/cards', [
                'account_id' => $account_id,
                'customer_id' => Auth::user()->synctera_id,
                'card_product_id' => 'ed892847-8bed-4a5f-ac04-6c4dddc00e92',
                'form' => 'VIRTUAL',
                'type' => 'DEBIT',
            ]);

            $card_id = $response['id'];
        }
        


        $this->message = $card_id;

        
    }
    
    public function render()
    {
        return view('livewire.virtual-card');
    }
}
