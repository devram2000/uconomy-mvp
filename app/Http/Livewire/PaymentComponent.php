<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use Laravel\Cashier\Cashier;
use App\Models\User;
use Illuminate\Validation\ValidationException;


class PaymentComponent extends Component
{
    public $payment_method;
    public $payment_amount;
    public $error_message = NULL;
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
        // Auth::user()->deletePaymentMethods();
        // dd(Auth::user()->defaultPaymentMethod());

        return view('livewire.payment-component', [
            'intent' => Auth::user()->createSetupIntent()
        ]);
        
    }

    public function chargeCard()
    {
        $transaction_limit = Auth::user()->limit + 50;
        
        // $validatedData = $this->validate([
        //     'payment_amount' => "required|numeric|min:1|max:$transaction_limit",
        // ]);

        // if ($validatedData->fails()) { 
        //     $errors = $validatedData->errors();
        //     dd($errors->first('payment_amount'));
        //  }

        $error_message = "";

        if($this->payment_amount == NULL) {
            $error_message = "The payment amount field is required";
        } else if ($this->payment_amount < 1) {
            $error_message = "The payment amount field should be above $1";
        } else if ($this->payment_amount > $transaction_limit) {
            $error_message = "The payment amount field should be below $" . $transaction_limit;
        }

        if ($error_message != "") {
            return redirect("/payment?message=".$error_message); 
        }



        $amount = $this->payment_amount * 100;
        $paymentMethod = Auth::user()->defaultPaymentMethod()->id;
        try {
            $single_charge = Auth::user()->charge($amount, $paymentMethod, [
                'off_session' => true,
            ]);
            if($single_charge->status == 'succeeded') {
                $this->payment_completed = true;
            } else {
                $error_message = "Your payment stopped with the status of " . $single_charge->status;
                // throw ValidationException::withMessages(['field_name' => $error_message]);
            }
        } catch (\Stripe\Exception\CardException $e) {
            $card = Auth::user()->defaultPaymentMethod()->card;
            // dd($e->error);
            if($card->funding != "debit") {
                $error_message = "Please use a debit card for your payment.";
            } else if ($card->country != "US") {
                $error_message = "Please use a US based debit card.";
            } else {
                $error_message = $e->getMessage();
            }
            // throw ValidationException::withMessages(['field_name' => $error_message]);

        } catch (\Throwable $e) {
            $error_message = "Your payment failed. Please contact us if this issue persists.";
            // $this->dispatchBrowserEvent('say-goodbye', []);
            // throw ValidationException::withMessages(['field_name' => $error_message]);

        }

        if (!$this->payment_completed) {
            return redirect("/payment?message=".$error_message); 
        }

       
    }

    public function redirectHome() {
        return redirect('home'); 
    }
}
