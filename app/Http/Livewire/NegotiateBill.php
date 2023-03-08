<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Bill;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\BPayment;



class NegotiateBill extends Component
{
    use WithFileUploads;
    public $bill;
    public $bill_object;
    public $comments;
    public $events;
    public $submitted;



    public function removeBill() {
        $this->bill = NULL;
    }
    
    public function submitBill() {
    
        $validatedData = $this->validate([
            'bill' => "required|mimes:jpeg,png,jpg,pdf|max:5048",
        ]);
    
    
        $image1Name = Auth::id()."_".date('Y-m-d H:i:s').'.'.$this->bill->extension();  
        
        $this->bill->storeAs('bills',  $image1Name, 'public');
    
        $this->bill_object = Bill::create([
            'user' => Auth::id(),
            'bill' => $image1Name,
            'comments' => $this->comments,
        ]);

        $this->events = Event::where('user', Auth::id())
                                ->where('fee', false)
                                ->get(['id', 'title', 'start'])->toArray();


        foreach($this->events as $event) {
            BPayment::create([
                'user' => Auth::id(),
                'bill' => $this->bill_object['id'],
                'amount' => $event['title'],
                'date' => $event['start'],
            ]);
        }

        Event::where('user', Auth::id())->delete();
        
        $this->submitted = 1;
        // return redirect('/home');
    
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
