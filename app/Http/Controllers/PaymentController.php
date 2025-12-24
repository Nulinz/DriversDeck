<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class PaymentController extends Controller
{
    public function initiatePayment(PaymentService $paymentService)
    {
        $orderId = 'ORDER_' . uniqid();
        $amount = 1.00; // INR
        $customer = [
            'id' => 'user_123',
            'email' => 'test@example.com',
            'phone' => '9876543210',
        ];

        $order = $paymentService->createOrder($orderId, $amount, $customer);

        // log::info("Order - " . $order);

        $paymentSessionId = $order['payment_session_id'] ?? null;

        if ($paymentSessionId) {
            $paymentUrl = "https://payments.cashfree.com/pg/checkout/{$paymentSessionId}";
            return redirect($paymentUrl);
        }

        // if (isset($order['payments']['url'])) {
        //     return redirect($order['payments']['url']);
        // }

        // return response()->json($order);
    }

    public function handleCallback(Request $request, PaymentService $paymentService)
    {

        $orderId = $request->input('order_id');

        // Optionally: fetch order from Cashfree using API again
        $orderStatus = $paymentService->fetchOrderStatus($orderId);

        log::info("Order - " . $orderStatus);

        return view('payment.success', [
            'order_id' => $orderId,
            // 'status' => $orderStatus['order_status'] ?? 'UNKNOWN'
        ]);




        // dd("hello");
        // Handle redirect after payment
        // return view('payment.success', ['order_id' => $request->input('order_id')]);
    }

    public function handleWebhook(Request $request)
    {
        // Verify signature and process status
        Log::info('Cashfree webhook received:', $request->all());
        return response()->json(['status' => 'received']);
    }

    public function sendTestEmail()
    {
        // Mail::to('test@example.com')->send(new TestMail());
        // return 'Mail sent!';

        Mail::to('user@example.com')->send(
            new TestMail('welcome', [
                'userName' => 'Harshanth',
            ])
        );
    }
}
