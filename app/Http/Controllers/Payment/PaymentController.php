<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\Paypal\PaypalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected $paypalInstance;
    public function __construct(PaypalController $paypalInstance)
    {
        $this->paypalInstance = $paypalInstance;
    }
    public function prepareData($data)
    {
        return [
            'itemId' => $data['itemId'],
            'itemName' => $data['itemName'] ?? 'service',
            'price' => $data['price'] ?? '10000',
            'currency' => $data['method'] == 'paypal' ? 'USD' : 'EGP',
            'discount' => $data['discount'] ?? 0,
            'qty' => $data['qty'] ?? 1
        ];
    }
    public function switchPayment($method, $data)
    {
        switch ($method) {
            case 'paypal':
                return $this->paypalInstance->payment($data);
            default:
                return $this->paypalInstance->payment($data);
        }
    }
    public function gerneratePaymentLink(Request $request)
    {
        $data = $this->prepareData($request->all());
        try {

            $payment = $this->switchPayment($request->method, $data);
            if ($payment['status']) {
                return $this->handleResponse(data: [
                    'payment-link' => $payment['paymentLink']
                ], message: $payment['message']);
            } else {
                return $this->handleResponse(status: false, message: $payment['message']);
            }
        } catch (\Throwable $th) {
            Log::error('Payment Error ====>' . $th->getMessage());
            return $this->handleResponse(status: false, message: $th->getMessage());
        }
    }
}
