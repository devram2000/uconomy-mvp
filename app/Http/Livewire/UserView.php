<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Identification;
use App\Models\Address;
use App\Models\Bill;
use App\Models\BPayment;

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
    public $spending_amount;
    public $events_and_fees;
    public $bills;
    public $newMessage;
    public $bills_payments = [];

    public function updateBillStatus($billId, $status)
    {
        $bill = Bill::find($billId);
        if (!$bill) {
            return;
        }
    
        $bill->status = $status;
        $bill->save();
    
        $this->dispatchBrowserEvent('bill-status-updated', [
            'billId' => $billId,
            'status' => $status,
        ]);
    
        // Show a success message to the user
        // Toastr::success(__('Bill status updated successfully.'), __('Success'));
        return redirect("/admin-user/" . $this->userID);
    }


    public function updateBillMessage($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->comments = $this->newMessage;
        $bill->save();
        return redirect("/admin-user/" . $this->userID);

    }


    
    public function render(Request $request)
    {
        $url = $request->path();
        $this->userID = substr($url, 11);

        $userList = User::where('id', $this->userID)->get();
        $this->isUser = !$userList->isEmpty();

        if($this->isUser) {
            $this->user = $userList[0];

            $this->transactions = Transaction::where('user', $this->user->id)->get();

            $this->spending_amount = $this->user->limit;

            $amount = 0;
            foreach ($this->transactions as $t) {
                $amount += $t->remaining_balance;
                if ($t->remaining_balance != 0) {
                    $this->spending_amount -= $t->amount;
                }
            }

            if ($this->spending_amount < 0) {
                $this->spending_amount = 0;
            }

            $this->remaining_balance = $amount;

            // $this->spending_amount = Auth::user()->limit - $this->remaining_balance;
    
            
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


            $this->bills = Bill::where('user', $this->user->id)
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


        

        return view('livewire.user-view');
    }
}
