<?php

namespace App\Http\Controllers\Payment\Paypal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    protected $paypalConfig;
    public function __construct()
    {
        $this->paypalConfig = new PayPalClient;
        $this->paypalConfig->setApiCredentials(config('paypal'));
        $this->paypalConfig->getAccessToken();
    }
    public function payment($data)
    {
        // "payment_source": {
        //     "paypal": {
        //       "experience_context": {
        //         "payment_method_preference": "IMMEDIATE_PAYMENT_REQUIRED",
        //         "brand_name": "EXAMPLE INC",
        //         "locale": "en-US",
        //         "landing_page": "LOGIN",
        //         "shipping_preference": "SET_PROVIDED_ADDRESS",
        //         "user_action": "PAY_NOW",
        //         "return_url": "https://example.com/returnUrl",
        //         "cancel_url": "https://example.com/cancelUrl"
        //       }
        //     }
        //   }

        $response = $this->paypalConfig->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                    "return_url" => route('paypal.success', ['SUCCESS' => 'TRUE', 'message' => 'COMPLETED-TRANSACTION']),
                    "cancel_url" => route('paypal.cancel', ['SUCCESS' => 'FALSE', 'message' => 'CANCELLED-TRANSACTION']),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $data['price']
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return ['status' => true, 'message' => 'Payment Link Generated', 'paymentLink' => $links['href']];
                }
            }
            return ['status' => false, 'message' => 'Create Transaction Something went wrong', 'paymentLink' => null];;
        } else {
            return ['status' => false, 'message' => $response['message'] ?? ' Create Transaction Something went wrong', 'paymentLink' => null];;
        }
    }

    public function cancel()
    {
        return $this->handleResponse(message: 'cancel payment', status: 402);
    }
    public function success(Request $request)
    {

        // $provider = new PayPalClient;
        // $provider->setApiCredentials(config('paypal'));
        // $provider->getAccessToken();
        $data = $this->paypalConfig->showOrderDetails($request['token']);
        $response = $this->paypalConfig->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'APPROVE') {
            return $this->handleResponse(view:'payment-status', message: 'Transaction-Completed');
            // return redirect()
            //     ->route('createTransaction')
             //     ->with('success', 'Transaction complete.');
        } else {
            return $this->handleResponse(view:'payment-status',message: $response['message'] ?? 'Something went wrong');
            // return redirect()
            //     ->route('createTransaction')
            //     ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }


}
