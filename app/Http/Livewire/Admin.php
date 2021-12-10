<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use App\Models\User;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Identification;
use App\Models\Transaction;


class Admin extends Component
{
    public $users; 
    public $transactions; 
    public $events = [];
    public $ids = [];
    public $combined_events = [];

    public function render()
    {
        
        $this->users = User::where('email', '!=', "help@uconomy.com")->get();
        $this->transactions = Transaction::select('user', 'start_date')->orderByDesc('start_date')->get();

        foreach ($this->users as $u) {
            $fees = Fee::where('user', $u->id)
            ->get(['id', 'amount', 'date'])->toArray();

            $payments = Payment::where('user', $u->id)
                            ->get(['id', 'amount', 'date'])->toArray();
            $view_id = 0;
            for ($i = 0; $i < count($fees); $i++) {
                $fees[$i]["borderColor"] = "black";
                $fees[$i]["color"] = "white";
                $fees[$i]["id"] = $view_id;
                $fees[$i]["title"] = strval($u->id) . ": " . $fees[$i]["amount"];
                $fees[$i]["start"] = $fees[$i]["date"];

                $view_id++;
            }

            for ($j = 0; $j < count($payments); $j++) {
                $payments[$j]["id"] = $view_id;
                $payments[$j]["title"] = strval($u->id) . ": " . $payments[$j]["amount"];
                $payments[$j]["start"] = $payments[$j]["date"];

                $view_id++;
            }

            $payments_and_fees = array_merge($fees, $payments);

            // $photos = Identification::where('user', $u->id)->first();

            // if ($photos == NULL) {
            //     $this->ids[$u->id] = NULL;
            // } else {
            //     $identification = [];
            //     $identification["photo1"] = $photos->photo1;
            //     $identification["photo2"] = $photos->photo2;

            //     $this->ids[$u->id] = $identification;
            // }


            // $this->events[$u->id] = $payments_and_fees;

            $this->combined_events = array_merge($this->combined_events, $payments_and_fees);

        }
    

        return view('livewire.admin');

    }
}
