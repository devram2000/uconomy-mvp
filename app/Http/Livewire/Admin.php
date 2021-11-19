<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use App\Models\User;
use App\Models\Fee;
use App\Models\Payment;


class Admin extends Component
{
    public $users; 
    public $events = [];
    public $combined_events = [];

    public function render()
    {
        if (!(Auth::user()->email == "help@uconomy.com")) {
            $this->redirect('home');
        } else {
            $this->users = User::where('email', '!=', "help@uconomy.com")->get();
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

                $this->events[$u->id] = $payments_and_fees;

                $this->combined_events = array_merge($this->combined_events, $payments_and_fees);

            }
        }

        return view('livewire.admin');

    }
}
