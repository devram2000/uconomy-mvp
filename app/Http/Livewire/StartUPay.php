<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Identification;
use App\Models\Fee;
use App\Models\Address;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class StartUPay extends Component
{
    public $transactions, $payments, $fees, $events_and_fees, $remaining_balance, $spending_amount, $has_transactions;
    public $profile_completed = false;
    public $profile_sections;
    public $terms;
    public $agreements;
    public $approved;
    public $is_approved;
    public $is_admin;

    public function __construct() {   
        $this->terms = Auth::user()->terms != NULL;
        $this->agreements = $this->terms;
        $this->approved = array_map(function ($o) {
            return $o->email;
        }, DB::select('select email from approved'));
    }


    public function render()
    {    
        $this->is_approved = in_array(Auth::user()->email, $this->approved);    

        $this->is_admin = Auth::user()->email == "help@uconomy.com";    

        if (!$this->terms) {
            return view('livewire.terms-agreement');
        }

        $this->transactions = Transaction::where('user', Auth::id())->get();

        $this->spending_amount = Auth::user()->limit;

        $amount = 0;
        foreach ($this->transactions as $t) {
            $amount += $t->remaining_balance;
            if ($t->remaining_balance != 0) {
                $this->spending_amount -= $t->amount;
            }
        }
        $this->remaining_balance = $amount;

        if ($this->spending_amount < 0) {
            $this->spending_amount = 0;
        }


        // $this->spending_amount = Auth::user()->limit - $this->remaining_balance;

        $this->has_transactions = count($this->transactions);

        $this->fees = Fee::where('user', Auth::id())
                        ->get(['id', 'amount', 'date'])->toArray();

        $this->payments = Payment::where('user', Auth::id())
                        ->get(['id', 'amount', 'date'])->toArray();
        // $this->events_and_fees = array_merge($this->fees, $this->payments)
        // $event_count = count($this->fees) + count($this->payments)
        $view_id = 0;
        for ($i = 0; $i < count($this->fees); $i++) {
            $this->fees[$i]["borderColor"] = "black";
            $this->fees[$i]["color"] = "white";
            $this->fees[$i]["id"] = $view_id;
            $this->fees[$i]["title"] = $this->fees[$i]["amount"];
            $this->fees[$i]["start"] = $this->fees[$i]["date"];

            $view_id++;
        }

        for ($j = 0; $j < count($this->payments); $j++) {
            $this->payments[$j]["id"] = $view_id;
            $this->payments[$j]["title"] = $this->payments[$j]["amount"];
            $this->payments[$j]["start"] = $this->payments[$j]["date"];

            $view_id++;
        }

        $this->events_and_fees = array_merge($this->fees, $this->payments);

        $zelle = Auth::user()->zelle;
        $phone_verify = Auth::user()->phone_verified_at;
        $email_verify = Auth::user()->email_verified_at;
        $address = Address::where('user', Auth::id())->first();
        $date_of_birth = Auth::user()->date_of_birth;

        $sections_needed = array();

        $identity_verify = count(Identification::where('user', Auth::id())->get());

        
        if($zelle == null) {
            $sections_needed[] = "Zelle";
        }
        if($email_verify == null || $phone_verify == null || $identity_verify == 0) {
            $sections_needed[] = "Verification";
        }
        if($address == null) {
            $sections_needed[] = "Address";
        }
        if($date_of_birth == null) {
            $sections_needed[] = "Date of Birth";
        }

        if(count($sections_needed) == 0) {
            $this->profile_completed = true;
        } else if(count($sections_needed) == 1) {
            $this->profile_sections = $sections_needed[0] . " section";
        } else if(count($sections_needed) == 2) {
            $this->profile_sections = $sections_needed[0] . " and " . $sections_needed[1] . " sections";
        } else {
            $str = "";
            for ($i = 0; $i < count($sections_needed); $i++) {
                if ($i == count($sections_needed) - 1) {
                    $str = $str . "and " . $sections_needed[$i] . " sections";
                } else {
                    $str = $str . $sections_needed[$i] . ", ";
                }
            }
            $this->profile_sections = $str;
        }

        return view('livewire.start-u-pay');
    }

    public function redirectUPay() {
        return redirect('transact'); 
    }

    public function redirectProfile() {
        return redirect('/user/profile'); 
    }

    public function redirectAdmin() {
        return redirect('/admin'); 
    }

    public function redirectWaitlist() {
        return redirect('/waitlist'); 
    }

    public function redirectPayment() {
        return redirect('/payment'); 
    }


    public function termsSubmit() {
        $validatedData = $this->validate([
            'agreements' => ['required', 'accepted'],

        ]);
        $date = date_format(date_create(), 'Y-m-d H:i:s');
        Auth::user()->update(['terms' => $date]);

        $this->terms = true;
    }
}
