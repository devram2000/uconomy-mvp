<?php

namespace App\Http\Livewire;

use Livewire\Component;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\InputFields;
use PayPal\Api\WebProfile;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use \Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\BPayment;
use PayPal\Api\FlowConfig;




class PaypalController extends Component
{
    private $apiContext;
    public $service_fee;
    public $fee_percentage = 0.03;

    public function __construct()
    {
        parent::__construct();
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public $isButtonDisabled = false;

    public function createPayment()
    {
        $this->isButtonDisabled = true;
    
    
        try {
            // Set up payment details
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
        
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($this->service_fee);
        
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setDescription('Bill date change service fee.');
        
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(route('execute-payment', ['id' => $this->bill_id]))
                ->setCancelUrl(route('paypal', ['id' => $this->bill_id, 'status' => 'cancelled']));
        
            $inputFields = new InputFields();
            $inputFields->setNoShipping(1);
        
            $flowConfig = new FlowConfig();
            $flowConfig->setLandingPageType('Billing');
        
            $webProfile = new WebProfile();
            $webProfile->setName('NoShipping' . uniqid())
                ->setInputFields($inputFields)
                ->setFlowConfig($flowConfig) // Set the flow config object
                ->setTemporary(true);
        
            $createProfileResponse = $webProfile->create($this->apiContext);
        
            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls)
                ->setExperienceProfileId($createProfileResponse->getId());

            $payment->create($this->apiContext);
        } catch (PayPalConnectionException $ex) {
            \Log::error($ex->getMessage());
            session()->flash('error', 'Payment failed. Please try again later.');
            return redirect()->route('paypal', ['id' => $this->bill_id, 'status' => 'cancelled']);
        }        
        // Add the useraction parameter to the approval link
        $approvalUrl = $payment->getApprovalLink() . '&useraction=commit';
    
        // Emit the 'paymentApproved' event with the modified approval URL
        $this->emit('paymentApproved', $approvalUrl);
    }
    
    public static function executePayment(Request $request)
    {

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        $apiContext->setConfig(config('paypal.settings'));
    
        if (!$request->has('paymentId') || !$request->has('PayerID')) {
            session()->flash('error', 'Payment failed. Please try again later.');
            return redirect()->route('paypal');
        }
    
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
    
        $payment = Payment::get($paymentId, $apiContext);
    
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
    
        try {
            $result = $payment->execute($execution, $apiContext);
        } catch (PayPalConnectionException $ex) {
            session()->flash('error', 'Payment failed. Please try again later.');
            \Log::error($ex->getMessage());
            return redirect()->route('home');
        }
    
        // Get the sale details
        $sale = $result->getTransactions()[0]->getRelatedResources()[0]->getSale();
    
        // Save the sale details to your database
        // ...
        $bill_id = $request->route('id');

        $bill = Bill::find($bill_id);
        $bill->status = 0;
        $bill->save();

    
        session()->flash('success', 'Payment successful!');
        return redirect()->route('home');
    }
    
    public function redirectHome() {
        return redirect('home'); 
    }

    public function mount(Request $request)
    {
        $this->bill_id = $request->route('id');

        $bill = Bill::find($this->bill_id);


        $status = $request->query('status');

        if ($status == 'cancelled') {
            session()->flash('error', 'Payment failed. Please try again.');
        }

        if (!$bill || $bill->user != auth()->id() || $bill->status != NULL) {
            return $this->redirect(route('home'));
        }

        $bill = Bill::find($this->bill_id);
        $totalAmount = $bill->bpayments()->sum('amount');
        $this->service_fee = $totalAmount * $this->fee_percentage;


    }

    

    public function render()
    {
        return view('livewire.paypal-controller');
    }

}
