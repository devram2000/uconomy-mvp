<?php

namespace App\Http\Livewire;
use Auth;


use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SyncteraCalls;


class VirtualCard extends Component
{
    public $message = "";
    public $flipped = "";
    public $pinWidgetURL = null;
    public $cardWidgetURL = null;
    public $client_token = null;
    public $account_id = null;
    public $card_id = null;
    public $last_four = null;
    public $name = null;
    public $balance = 0;

    public function toggleButton() {  
        if ($this->flipped == "flipped") {
            $this->flipped = "";
        } else {
            $this->flipped = "flipped";
        }
    }

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

            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNCTERA_KEY'),
            ])->post(env('SYNCTERA_API') . '/v0/cards/transaction_simulations/authorization', [
                'card_id' => $this->card_id,
                'amount' => 10.00 * 100,
                'mid' => "12345",
            ]);
    
        }


        if ($this->flipped == "flipped") {
            $this->flipped = "";
        } else {
            $this->flipped = "flipped";
        }



        $this->message =   $response->body();
        
    }
    
    public function render()
    {
        $template_id = SyncteraCalls::getTemplateID();

        $this->name = Auth::user()->name;


        $account = SyncteraCalls::getAccount(Auth::user()->synctera_id, $template_id);

        $this->account_id = $account['id'];

        foreach ( $account['balances'] as $b) {
            if($b['type'] == 'AVAILABLE_BALANCE') {
                $this->balance = $b['balance'] / 100;
            }
        }

        $card = SyncteraCalls::getCard(Auth::user()->synctera_id, $this->account_id);

        $this->card_id = $card['id']; 
        $this->last_four = $card['last_four'];

        $this->client_token = SyncteraCalls::getClientToken($this->card_id);




        return view('livewire.virtual-card');
    }
}
