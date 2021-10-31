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
    public $events;
    public $categories = ['Retail', 'Service', 'Peer-to-Peer Marketplace', 'Bill', 'Other'];

    public $currentStep = 1;
    public $successMessage = '';

    public function updateRemainingAmount() {
        $this->events = Event::where('user', Auth::id())
        ->get(['id', 'title', 'start']);

        $event_amount = 0;
        
        foreach($this->events as $event) {
            $event_amount += floatval($event['title']);
        }

        $this->remaining_amount = $this->amount - $event_amount;

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
            'amount' => "required|numeric|min:0|max:$spending_amount",
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

        // if($this->remaining_amount != 0) {
        //     return Redirect::back()->withErrors("Please add payments until your remaining balance is 0.");
        // }
        

        // $input = [
        //     'amount' => $this->remaining_amount,
        // ];

        // $validator = Validator::make($input, [
        //     'amount' => 'min:0|max:0',
        // ]);
        


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
              ]);
  
              return response()->json($event);
             break;
  
           case 'update':
              $event = Event::find($request->id)->update([
                'title' => $request->title,
                'start' => $request->start,
                'user' => Auth::id(),
            ]);
 
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
        if($request->ajax()) {
    
            $data = Event::where('user', Auth::id())
                        ->get(['id', 'title', 'start']);

            return response()->json($data);
       }

        return view('livewire.make-transaction');
    }


}
