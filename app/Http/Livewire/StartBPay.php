<?php

namespace App\Http\Livewire;
use Auth;
use Livewire\Component;
use App\Models\Bill;
use App\Models\BPayment;


class StartBPay extends Component
{
    public $profile_completed = false;
    public $phone_verify;
    public $email_verify;
    public $is_card_added;
    public $bills;
    public $bills_payments = [];
    

    public function redirectBill() {
        return redirect('/bill'); 
    }

    public function mount() {
        // $this->phone_verify = Auth::user()->phone_verified_at;
        // $this->email_verify = Auth::user()->email_verified_at;
        $this->is_card_added = Auth::user()->stripe_id;
        $this->phone_verify = true;
        $this->email_verify = true;
        $this->bills = Bill::where('user', Auth::id())
                                ->get(['id', 'bill', 'comments', 'status', 'created_at'])->toArray();

        foreach ($this->bills as $bill) {
            $bpayments = BPayment::where('bill', $bill['id'])
            ->get(['id', 'amount', 'date'])->toArray();

            for ($j = 0; $j < count($bpayments); $j++) {
                $bpayments[$j]["title"] = $bpayments[$j]["amount"];
                $bpayments[$j]["start"] = $bpayments[$j]["date"];
    
            }

            array_push($this->bills_payments, [$bill, $bpayments]);
        }
    }

    public function render()
    {
        return view('livewire.start-b-pay');
    }
}
