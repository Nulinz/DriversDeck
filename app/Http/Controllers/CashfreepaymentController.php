<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\CashfreeService;

class CashfreepaymentController extends Controller
{
     public function createPayment(Request $request, CashfreeService $cashfree)
    {
        $orderId = 'ORDER_' . time();
        $amount = 500; // example amount

        $response = $cashfree->createOrder(
            $orderId,
            $amount,
            'John Doe',
            'john@example.com',
            '9876543210'
        );

        return response()->json($response);
    }

    public function callback(Request $request)
    {
        // Handle success/failure response from Cashfree
        return response()->json($request->all());
    }
        public function Pay()
    {
        // Make sure the 'payment' view exists in resources/views/payment.blade.php
        return view('payment');
    }

}
