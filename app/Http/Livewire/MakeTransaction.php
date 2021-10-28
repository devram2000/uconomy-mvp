<?php

namespace App\Http\Livewire;

use Auth;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Event;

class MakeTransaction extends Component
{
    public $amount;
    public $category;
    public $description;
    public $categories = ['Retail', 'Service', 'Peer-to-Peer Marketplace', 'Bill', 'Other'];

    public $currentStep = 1;
    public $successMessage = '';

      /**

     * Write code on Method

     *

     * @return response()

     */

    public function firstStepSubmit()

    {

        $transactions = Transaction::where('user', Auth::id())->get();

        $amount = 0;
        foreach ($transactions as $t) {
            $amount += $t->remaining_balance;
        }
        $remaining_balance = $amount;

        $spending_amount = Auth::user()->limit - $remaining_balance;

        $limit = Auth::user()->limit;
        $validatedData = $this->validate([
            'amount' => "required|numeric|min:0|max:$spending_amount",
            'category' => 'required',
            'description' => 'required',
            // 'zelle' => 'required',
        ]);

 

        $this->currentStep = 2;

    }

      /**

     * Write code on Method

     *

     * @return response()

     */

    public function secondStepSubmit()

    {

        $this->currentStep = 3;

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

        // $this->stock = '';

        // $this->status = 1;

    }

    public function index()
    {
    //     if($request->ajax()) {
       
    //         $data = Event::whereDate('start', '>=', $request->start)
    //                   ->whereDate('end',   '<=', $request->end)
    //                   ->get(['id', 'title', 'start', 'end']);
 
    //         return response()->json($data);
    //    }
 
        return view('livewire.make-transaction');
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
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($event);
             break;
  
           case 'update':
              $event = Event::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
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

}
