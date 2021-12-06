<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Identification;
use App\Models\Address;

use Livewire\Component;
use Illuminate\Http\Request;


class UserView extends Component
{
    public $userID;
    public $user;
    public $isUser;
    public $address;
    public $transactions;
    public $identification;
    public $remaining_balance;
    public $events_and_fees;

    public function render(Request $request)
    {
        if (!(Auth::user()->email == "help@uconomy.com")) {
            $this->redirect('home');
        } else {
            $url = $request->path();
            $this->userID = substr($url, 11);

            $userList = User::where('id', $this->userID)->get();
            $this->isUser = !$userList->isEmpty();

            if($this->isUser) {
                $this->user = $userList[0];

                $this->transactions = Transaction::where('user', $this->user->id)->get();

                $amount = 0;
                foreach ($this->transactions as $t) {
                    $amount += $t->remaining_balance;
                }
                $this->remaining_balance = $amount;
        
                
                $addressList = Address::where('user', $this->user->id)->get();
                if ($addressList->isEmpty()) {
                    $this->address = NULL; 
                } else {
                    $this->address = $addressList[0];
                }

                $this->identification = Identification::where('user', $this->user->id)->first();


                $fees = Fee::where('user', $this->user->id)
                                ->get(['id', 'amount', 'date'])->toArray();
        
                $payments = Payment::where('user', $this->user->id)
                                ->get(['id', 'amount', 'date'])->toArray();
                // $this->events_and_fees = array_merge($this->fees, $this->payments)
                // $event_count = count($this->fees) + count($this->payments)
                $view_id = 0;
                for ($i = 0; $i < count($fees); $i++) {
                    $fees[$i]["borderColor"] = "black";
                    $fees[$i]["color"] = "white";
                    $fees[$i]["id"] = $view_id;
                    $fees[$i]["title"] = $fees[$i]["amount"];
                    $fees[$i]["start"] = $fees[$i]["date"];
        
                    $view_id++;
                }
        
                for ($j = 0; $j < count($payments); $j++) {
                    $payments[$j]["id"] = $view_id;
                    $payments[$j]["title"] = $payments[$j]["amount"];
                    $payments[$j]["start"] = $payments[$j]["date"];
        
                    $view_id++;
                }
        
                $this->events_and_fees = array_merge($fees, $payments);
        
        


            }


        }

        return view('livewire.user-view');
    }
}
