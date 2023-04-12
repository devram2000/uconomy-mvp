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
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;



class PaypalController extends Component
{
    private $apiContext;

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

    public function createPayment()
    {
        // Set up payment details
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(10); // Replace with the actual amount

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription('Your transaction description');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('execute-payment'))
            ->setCancelUrl(route('paypal'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            session()->flash('error', 'Payment failed. Please try again later.');
            return;
        }

        // Redirect to the PayPal payment page
        return redirect($payment->getApprovalLink());
        // Redirect to the PayPal payment page
        $this->emit('paymentApproved', $payment->getApprovalLink());

    }


public function executePayment($paymentId, $payerId) {
        if (!$paymentId || !$payerId) {
            session()->flash('error', 'Payment failed. Please try again later.');
            return;
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (PayPalConnectionException $ex) {
            session()->flash('error', 'Payment failed. Please try again later.');
            return;
        }

        // Get the sale details
        $sale = $result->getTransactions()[0]->getRelatedResources()[0]->getSale();

        // Save the sale details to your database
        // ...

        session()->flash('success', 'Payment successful!');
    }

    public function render()
    {
        return view('livewire.paypal-controller');
    }
}