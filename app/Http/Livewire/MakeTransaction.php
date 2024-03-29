<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Identification;
use App\Models\Fee;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Event;


class MakeTransaction extends Component
{
    public $amount;
    public $old_amount;
    public $remaining_amount;
    public $category;
    public $description;
    public $start_date;
    public $last_payment_date;
    public $events;
    public $fees;
    public $window;
    public $events_and_fees;
    public $categories = ['Retail', 'Service', 'Peer-to-Peer Marketplace', 'Bill', 'Other'];

    public $currentStep = 1;
    public $successMessage = '';

    public function updateRemainingAmount() {
        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();

        $event_amount = 0;
        
        foreach($this->events as $event) {
            $event_amount += floatval($event['title']);
        }

        $this->remaining_amount = $this->amount - $event_amount;

    }

    public function updateFees() {
        Event::where('user', Auth::id())->where('fee', true)->delete();

        $custom_payments = Event::where('user', Auth::id())
                                ->orderBy('start', 'ASC')
                                ->get(['title', 'start'])
                                ->toArray();

        $this->start_date = date('Y-m-d');
        $this->last_payment_date = $custom_payments[count($custom_payments) - 1]['start'];
        
        $fee_date = $this->start_date;
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
            Event::create([
                'title' => 5,
                'start' => $fee_date,
                'user' => Auth::id(),
                'fee' => true,
            ]);
            $fee_date = date("Y-m-d", strtotime("+1 month", strtotime($fee_date)));
        }



        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();

        $this->fees = Event::where('user', Auth::id()) 
                                ->where('fee', true)
                                
                                ->get(['id', 'title', 'start'])->toArray();
        // $test = array_map(function ($a) { 
        //     return $a[] = ["borderColor" => "black"];
        // }, $this->fees);

        $fee_view =$this->fees;

        for ($i = 0; $i < count($fee_view ); $i++) {
            $fee_view [$i]["borderColor"] = "black";
            $fee_view [$i]["color"] = "white";
        }
       

        $this->events_and_fees = array_merge($fee_view , $this->events);
    }


      /**

     * Write code on Method

     *

     * @return response()

     */

    public function firstStepSubmit()

    {

        $transactions = Transaction::where('user', Auth::id())->get();

        $total_remaining = 0;
        foreach ($transactions as $t) {
            $total_remaining += $t->remaining_balance;
        }
        $spending_amount = Auth::user()->limit - $total_remaining;

        $this->window = Auth::user()->window;
        
        if ($this->window == NULL) {
            $this->window = 3;
        }

        $limit = Auth::user()->limit;
        $validatedData = $this->validate([
            'amount' => "required|numeric|min:10|max:$spending_amount",
            'category' => 'required',
            'description' => 'required',
            // 'zelle' => 'required',
        ]);


        $this->updateEvents();

        $this->currentStep = 2;

    }


    public function updateEvents() {
        if ($this->old_amount != $this->amount) {
            Event::where('user', Auth::id())->delete();
        }

        $this->updateRemainingAmount();

        $this->old_amount = $this->amount;
    }

      /**

     * Write code on Method

     *

     * @return response()

     */

    public function secondStepSubmit()

    {

        $this->updateRemainingAmount();
        
        $this->updateFees();


        $this->currentStep = 3;

    }


        /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
           case 'add':
              $event = Event::create([
                  'title' => $request->title,
                //   'title' => $this->amount,
                  'start' => $request->start,
                  'user' => Auth::id(),
                  'fee' => false,
              ]);
  
              return response()->json($event);
             break;
  
        //    case 'update':
        //       $event = Event::find($request->id)->update([
        //         'title' => $request->title,
        //         'start' => $request->start,
        //         'user' => Auth::id(),
        //     ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Event::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }

  
    public function submitForm()
    {
        
        $transaction = Transaction::create([
            'amount' => $this->amount,
            'remaining_balance' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'user' => Auth::id(),
            'start_date' => $this->start_date,
            'due_date' => $this->last_payment_date,
        ]);
        foreach($this->events as $event) {
            Payment::create([
                'user' => Auth::id(),
                'transaction' => $transaction->id,
                'amount' => $event['title'],
                'date' => $event['start'],
                'completed' => false,
            ]);
        }
        foreach($this->fees as $fee) {
            Fee::create([
                'user' => Auth::id(),
                'transaction' => $transaction->id,
                'amount' => $fee['title'],
                'date' => $fee['start'],
                'completed' => false,
            ]);
        }


        $this->successMessage = 'Product Created Successfully.';
        $this->clearForm();
        $this->currentStep = 4;

    }

    public function redirectHome() {
        return redirect('home'); 
    }

       /**

     * Write code on Method

     *

     * @return response()

     */

    public function back($step)

    {
        $this->currentStep = $step;    

    }

  

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function clearForm()

    {

        $this->amount = '';

        $this->category = '';

        $this->description = '';

        Event::where('user', Auth::id())->delete();

    }

    public function index()
    {

        // $zelle = Auth::user()->zelle;
        // $phone_verify = Auth::user()->phone_verified_at;
        // $email_verify = Auth::user()->email_verified_at;
        // $address = Address::where('user', Auth::id())->first();
        // $date_of_birth = Auth::user()->date_of_birth;

        // $identity_verify = count(Identification::where('user', Auth::id())->get());

        
        // if($zelle == null || $email_verify == null || $phone_verify == null
        // || $identity_verify == 0 || $address == null || $date_of_birth == null) {
        //     dd('hello');
        //     $this->redirect('/home');
        // } 

        return view('livewire.make-transaction');
    }


}
