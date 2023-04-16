<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Bill;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\BPayment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;




class NegotiateBill extends Component
{
    use WithFileUploads;
    public $bill;
    public $bill_object;
    public $comments;
    public $events;
    public $submitted;
    public $service_fee = 0;



    public function removeBill() {
        $this->bill = NULL;
    }
    
    public function submitBill() {
        $this->events = Event::where('user', Auth::id())
        ->where('fee', false)
        ->get(['id', 'title', 'start'])->toArray();

        $validator = Validator::make(
            ['bill' => $this->bill, 'events' => $this->events],
            [
            'bill' => "required|mimes:jpeg,png,jpg,pdf|max:50000",
            'events' => 'required'
            ],
            [
            'events.required' => 'At least one payment date is required.'
            ]
        );

        if ($validator->fails()) {
            $this->addError('events', $validator->errors()->first('events'));
            return;
        }
    
    
        $image1Name = Auth::id()."_".date('Y-m-d H:i:s').'.'.$this->bill->extension();  
        
        $this->bill->storeAs('bills',  $image1Name, 'public');
    
        $this->bill_object = Bill::create([
            'user' => Auth::id(),
            'bill' => $image1Name,
            'comments' => $this->comments,
        ]);


        $bill_total = 0;
        foreach($this->events as $event) {
            $bill_total += floatval($event['title']);
            BPayment::create([
                'user' => Auth::id(),
                'bill' => $this->bill_object['id'],
                'amount' => $event['title'],
                'date' => $event['start'],
            ]);
        }
        // $this->service_fee = 10;
        // $this->service_fee = $bill_total * 0.03;

        Event::where('user', Auth::id())->delete();
        
        $this->submitted = 1;
        // $someVariable = 'value'; // This can be any value you want to pass
        // $this->emit('redirectToPage', $this->service_fee);

        return redirect('/paypal/' . $this->bill_object['id']);
    
    }

    public function submitComment() {
        $this->bill_object->update(
            [
                'comments' => $this->comments,
            ]);
        return redirect('/home');
    }

    public function mount()
    {
        Event::where('user', Auth::id())->delete();
    }
    
    public function render()
    {
        return view('livewire.negotiate-bill');
    }
}
