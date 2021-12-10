<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use Laravel\Cashier\Cashier;
use App\Models\User;

class PaymentComponent extends Component
{
    public $payment_method;
    public $payment_amount;
    public $payment_completed = false;
    
    protected $listeners = [
        'setPaymentMethod',
        'chargeCard',
    ];

    public function setPaymentMethod($value)
    {
        if(!is_null($value)) {
            $this->payment_method = $value;
        }

        $plan = Auth::user()->addPaymentMethod($this->payment_method);
        Auth::user()->updateDefaultPaymentMethod($this->payment_method);
        // dd(Auth::user()->defaultPaymentMethod());

    }


    
    public function render()
    {
        if (Auth::user()->stripe_id == NULL) {
            $stripeCustomer = Auth::user()->createAsStripeCustomer();
        } else {
            $stripeCustomer = Auth::user()->asStripeCustomer();
        }
        Auth::user()->deletePaymentMethods();
        // dd(Auth::user()->defaultPaymentMethod());

        return view('livewire.payment-component', [
            'intent' => Auth::user()->createSetupIntent()
        ]);
        
    }

    public function chargeCard()
    {
        $amount = $this->payment_amount * 100;
        $paymentMethod = Auth::user()->defaultPaymentMethod()->id;
        try {
            $single_charge = Auth::user()->charge($amount, $paymentMethod);
            if($single_charge->status == 'succeeded') {
                $this->payment_completed = true;
            } else {
                dd($single_charge);
            }
        } catch (\Throwable $e) {
            dd($e);
        }

       
    }

    public function redirectHome() {
        return redirect('home'); 
    }
}
