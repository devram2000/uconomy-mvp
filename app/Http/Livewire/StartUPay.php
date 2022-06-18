<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Identification;
use App\Models\Fee;
use App\Models\Address;
use App\Models\DefaultSchedule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SyncteraCalls;



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
    public $kyc;
    public $plaid;
    public $flipped = "";
    public $pinWidgetURL = null;
    public $cardWidgetURL = null;
    public $client_token = null;
    public $account_id = null;
    public $card_id = null;
    public $last_four = null;
    public $balance = 0;
    public $simulated_amount = null;
    public $transaction_info = [];
    public $categories = ['Retail', 'Service', 'Peer-to-Peer Marketplace', 'Bill', 'Other'];


    public function __construct() {   
        $this->terms = Auth::user()->terms != NULL;
        $this->agreements = $this->terms;
        $this->approved = array_map(function ($o) {
            return $o->email;
        }, DB::select('select email from approved'));
        $this->transactions = Transaction::where('user', Auth::id())->get();
        $i = 1;
        foreach ($this->transactions as $t) {
            $this->transaction_info[$t->id] = [$t->category, $t->description, false, $i];
            $i++;
        }

        // dd($this->transaction_info);


        
    }

    public function saveTransaction($t_id) {
        $transaction = Transaction::where('id', $t_id)->first();
        $transaction->category = $this->transaction_info[$t_id][0];
        $transaction->description = $this->transaction_info[$t_id][1];
        $transaction->save();
        $this->transaction_info[$t_id][2] = true;
    }

    public function toggleButton() {  
        if ($this->flipped == "flipped") {
            $this->flipped = "";
        } else {
            $this->flipped = "flipped";
        }
    }

    public function simulateSubmit(){
        $validatedData = $this->validate([
            'simulated_amount' => "required|numeric|min:10|max:$this->spending_amount",
        ]);

        $start_date = date('Y-m-d');
        $schedule = DefaultSchedule::where('user', Auth::id())->first();
        

        $transaction = Transaction::create([
            'amount' => $this->simulated_amount,
            'remaining_balance' => $this->simulated_amount,
            // 'category' => 'Other',
            // 'description' => 'Test',
            'user' => Auth::id(),
            'start_date' => $start_date,
            'due_date' => date('Y.m.d', strtotime('+1 week')),
        ]);
        $a = $this->simulated_amount;
        $date = $start_date;

        if ($schedule['payment_length'] == 'monthly') {
            $i = $schedule['payment_months'];
            $payamo = floor(($a / $i) * 100) / 100;
            while ($payamo < 10 && $i > 1) {
                $i--;
                $payamo = floor(($a / $i) * 100) / 100;
            }
            $remainder = $a - ($payamo * $i);
            // dd($payamo . ': ' . $remainder);
            $dayX = null;
            while ($i > 0) {
                if($dayX == null) {
                    if($schedule['date'] == -1) {
                        // $dayX = date('t', strtotime('+1 day', strtotime($date)));
                        $x = date('t', strtotime(date('Y-m-d')));
                        $dayX = date('Y-m-' . $x, strtotime('+1 day', time()));
        
                    } else {
                        $dayX = date('Y-m-' . $schedule['date'], time());
                        if(strtotime($dayX) <= strtotime("today")) {
                            $dayX = date('Y-m-d', strtotime('+1 month', strtotime($dayX)));
                            $i--;
                            $payamo = floor(($a / $i) * 100) / 100;
                            $remainder = $a - ($payamo * $i);
                        }
                    }
                 
                } else {
                    if($schedule['date'] == -1) {
                        $x = date('t', strtotime('+1 day', strtotime($dayX)));
                        $dayX = date('Y-m-' . $x, strtotime('+1 day', strtotime($dayX)));
        
                    } else {
                        $dayX = date('Y-m-' . $schedule['date'], strtotime('+1 month', strtotime($dayX)));
                    }
                }

                $payment_amount = $payamo;
                if($i == 1) {
                    $payment_amount += $remainder;
                }

                $date = $dayX;
                Payment::create([
                    'user' => Auth::id(),
                    'transaction' => $transaction->id,
                    'amount' => $payment_amount,
                    'date' => $date,
                    'completed' => false,
                ]);

                $i--;
            }
            
            
        } else if ($schedule['payment_length'] == 'biweekly') {
            $i = $schedule['payment_months'] * 2;
            $payamo = floor(($a / $i) * 100) / 100;
            while ($payamo < 10 && $i > 1) {
                $i--;
                $payamo = floor(($a / $i) * 100) / 100;
            }
            $remainder = $a - ($payamo * $i);
            // dd($payamo . ': ' . $remainder);
            $weekday = null;
            if($schedule['monday'] == true) {
                $weekday = 'Monday';
            } else if($schedule['tuesday'] == true) {
                $weekday = 'Tuesday';
            } else if($schedule['wednesday'] == true) {
                $weekday = 'Wednesday';
            }  else if($schedule['thursday'] == true) {
                $weekday = 'Thursday';
            }  else if($schedule['friday'] == true) {
                $weekday = 'Friday';
            }  else if($schedule['saturday'] == true) {
                $weekday = 'Saturday';
            }  else if($schedule['sunday'] == true) {
                $weekday = 'Sunday';
            }  
            $dayX = date( 'Y-m-d', strtotime( 'next ' . $weekday ) );


            while ($i > 0) {
                

                $payment_amount = $payamo;
                if($i == 1) {
                    $payment_amount += $remainder;
                }

                $date = $dayX;
                Payment::create([
                    'user' => Auth::id(),
                    'transaction' => $transaction->id,
                    'amount' => $payment_amount,
                    'date' => $date,
                    'completed' => false,
                ]);

                $dayX = date( 'Y-m-d', strtotime( 'second ' . $weekday, strtotime($dayX) ) );

                $i--;
            }

        } else if ($schedule['payment_length'] == 'weekly') {
            $weekdays = [$schedule['sunday'], $schedule['monday'], $schedule['tuesday'], $schedule['wednesday'], $schedule['thursday'], $schedule['friday'], $schedule['saturday']];
            $true_count = 0;

            foreach ($weekdays as $d) {
                if ($d == true) {
                    $true_count++;
                }
            }

            $i = $schedule['payment_months'] * 4 * $true_count;

            $payamo = floor(($a / $i) * 100) / 100;

            while ($payamo < 10 && $i > 1) {
                $i--;
                $payamo = floor(($a / $i) * 100) / 100;
            }

            $remainder = $a - ($payamo * $i);

            $dayX = date('Y-m-d', strtotime('+1 day', time()));;
            $start_weekday = date('w') + 1;
            // dd($dayX . ': ' . $start_weekday);
            if ($start_weekday == 7) {
                $start_weekday = 0;
            }
            $j = $start_weekday;

            while ($i > 0) {
                
                if ($weekdays[$j] == true) {

                    $payment_amount = $payamo;
                    if($i == 1) {
                        $payment_amount += $remainder;
                    }

                    $date = $dayX;
                    Payment::create([
                        'user' => Auth::id(),
                        'transaction' => $transaction->id,
                        'amount' => $payment_amount,
                        'date' => $date,
                        'completed' => false,
                    ]);
                    $i--;
                }
                if ($j == 6) {
                    $j = 0;
                } else {
                    $j++;
                }
                $dayX = date('Y-m-d', strtotime('+1 day', strtotime($dayX)));;
            }

        }



        $transaction->due_date = $date;
        $transaction->save();

        // foreach($this->events as $event) {
            // Payment::create([
            //     'user' => Auth::id(),
            //     'transaction' => $transaction->id,
            //     'amount' => $this->simulated_amount,
            //     'date' => date('Y.m.d', strtotime('+1 week')),
            //     'completed' => false,
            // ]);
        // }
        // foreach($this->fees as $fee) {
            $this->last_payment_date = $date;
        
            $fee_date = $start_date;
            $fee_payments = [];
    
            $current_fees = Fee::where('user', Auth::id()) 
                    ->orderBy('date', 'DESC')->first();
            if ($current_fees != null) {
                $last_fee_date = date("Y-m-d", strtotime("+1 month", strtotime($current_fees->date)));
                // dd($last_fee_date);
                // if ($last_fee_date <= $this->last_payment_date) {
                $fee_date = $last_fee_date;
                // }
            }
            while($fee_date <= $this->last_payment_date) {
                // array_push($fee_payments, [ "id" => $i, "title" => 5, "start" => $fee_date ]);
                // // $fee_payments += [[ "title" => 5, "start" => $fee_date ]];
                Fee::create([
                    'user' => Auth::id(),
                    'transaction' => $transaction->id,
                    'amount' => '9.99',
                    'date' => $fee_date,
                    'completed' => false,
                ]);
                $fee_date = date("Y-m-d", strtotime("+1 month", strtotime($fee_date)));
            }
    
            
        // }
        return redirect('home'); 


    }


    public function render()
    {    
        $this->is_approved = in_array(Auth::user()->email, $this->approved);    

        $this->is_admin = Auth::user()->email == "help@uconomy.com";    

        if (!$this->terms) {
            return view('livewire.terms-agreement');
        }


        $this->spending_amount = Auth::user()->limit;

        $amount = 0;
        $this->payments = [];
        $open_transaction = false;
        foreach ($this->transactions as $t) {
            $amount += $t->remaining_balance;
            if ($t->remaining_balance != 0) {
                $open_transaction = true;
                $ps = Payment::where('transaction', $t->id)
                        ->where('reschedule', $t->reschedule)
                        ->where('completed', 0)
                        ->get(['id', 'amount', 'date'])->toArray();

                $this->payments = array_merge($this->payments , $ps);

        
                $this->spending_amount -= $t->amount;
            }
        }

        


        $ps = Payment::where('transaction', null)
        ->where('user', Auth::id())
        ->where('completed', 0)
        ->get(['id', 'amount', 'date'])->toArray();


        if (!$open_transaction) {
            // Transaction wont be empty because of transaction with 0 remaining balance
            Payment::where('user', Auth::id())->where('transaction', null)->delete();
            Fee::where('user', Auth::id())->where('completed', 0)->delete();
         }

        $this->payments = array_merge($this->payments , $ps);

        $this->remaining_balance = $amount;

        if ($this->spending_amount < 0) {
            $this->spending_amount = 0;
        }


        // $this->spending_amount = Auth::user()->limit - $this->remaining_balance;

        $this->has_transactions = count($this->transactions);

        $this->fees = Fee::where('user', Auth::id())
                        ->where('completed', 0)
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
        $kyc = Auth::user()->kyc;
        $plaid = Auth::user()->plaid;
        $debit = Auth::user()->debit;
        $schedule = DefaultSchedule::where('user', Auth::id())->first();


        $sections_needed = array();

        $identity_verify = count(Identification::where('user', Auth::id())->get());

        
        // if($zelle == null) {
        //     $sections_needed[] = "Zelle";
        // }
        // if($email_verify == null || $phone_verify == null || $identity_verify == 0) {
        //     $sections_needed[] = "Verification";
        // }
        // if($address == null) {
        //     $sections_needed[] = "Address";
        // }
        // if($date_of_birth == null) {
        //     $sections_needed[] = "Date of Birth";
        // }
        if ($kyc == null || $kyc == false) {
            $sections_needed[] = "Verification (Identity, Bank Account, and Debit Card)";
        } else if ($plaid == null || $plaid == false) {
            $sections_needed[] = "Verification (Bank Account and Debit Card)";
        } else if ($debit == null || $debit == false) {
            $sections_needed[] = "Verification (Debit Card)";
        }

        if($schedule == null) {
            $sections_needed[] = "Default Payment Schedule";
        }
        

        if($kyc && $plaid) {
            $template_id = SyncteraCalls::getTemplateID();


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

    public function redirectReschedule() {
        return redirect('/reschedule'); 
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
