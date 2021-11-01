<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Event;
// use Illuminate\Support\Facades\Validator;

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
        $i = 0;
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
            $i++;
        }

        $this->events = Event::where('user', Auth::id()) 
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();

        $fees = Event::where('user', Auth::id()) 
                                ->where('fee', true)
                                
                                ->get(['id', 'title', 'start'])->toArray();
        // $test = array_map(function ($a) { 
        //     return $a[] = ["borderColor" => "black"];
        // }, $this->fees);

        for ($i = 0; $i < count($fees); $i++) {
            $fees[$i]["borderColor"] = "black";
        }

        $this->events_and_fees = array_merge($fees, $this->events);
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
        
        Transaction::create([
            'amount' => $this->amount,
            'remaining_balance' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            // 'zelle' => $this->zelle,
            'user' => Auth::id(),
            'start_date' => date('Y-m-d H:i:s'),
            'due_date' => date('Y-m-d H:i:s', strtotime('+3 months')),
        ]);

        $this->successMessage = 'Product Created Successfully.';
        $this->clearForm();
        $this->currentStep = 1;

        return redirect('/dashboard');
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

    public function index(Request $request)
    {
    //     if($request->ajax()) {
    
    //         $data = Event::where('user', Auth::id())
    //                     ->get(['id', 'title', 'start']);

    //         return response()->json($data);
    //    }

        return view('livewire.make-transaction');
    }


}
