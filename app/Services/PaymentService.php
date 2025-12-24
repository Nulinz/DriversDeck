<?php

// app/Services/PaymentService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $baseUrl;
    protected $appId;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = env('CASHFREE_ENV') === 'sandbox'
            ? 'https://sandbox.cashfree.com/pg'
            : 'https://api.cashfree.com/pg';

        $this->appId = env('CASHFREE_APP_ID');
        $this->secretKey = env('CASHFREE_SECRET_KEY');

        // log::info($this->appId . "-//////-" . $this->secretKey . "-/////-" . $this->baseUrl);
    }

    protected function headers()
    {
        return [
            'x-api-version' => '2025-01-01',
            'x-client-id'   => $this->appId,
            'x-client-secret' => $this->secretKey,
            'Content-Type'  => 'application/json',
        ];
    }

    public function createOrder($orderId, $amount, $customer)
    {
        $payload = [
            'order_id' => $orderId,
            'order_amount' => $amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => $customer['id'] ?? 1,
                'customer_email' => $customer['email'] ?? 'hari@gmail.com',
                'customer_phone' => $customer['phone'] ?? '9791818968',
            ],
            'order_meta' => [
                // 'return_url' => route('cashfree.callback') . '?order_id={order_id}',
                'return_url' => 'https://driversdeck.in/payment/callback?order_id={$orderId}',

                'notify_url' => route('cashfree.webhook'),
            ]
        ];

        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/orders", $payload);

        // dd($response->json());

        return $response;
    }

    public function fetchOrderStatus($orderId)
    {
        $url = "https://api.cashfree.com/pg/orders/{$orderId}"; // Use sandbox endpoint for testing
        // $url = "https://sandbox.cashfree.com/pg/orders/{$orderId}"; // for sandbox

        $headers = [
            'x-api-version'   => '2025-01-01',
            'x-client-id'     => $this->appId,
            'x-client-secret' => $this->secretKey,
            'Content-Type'    => 'application/json',
        ];

        $response = Http::withHeaders($headers)->get($url);

        if ($response->successful()) {
            return $response;
        } else {
            Log::error('Failed to fetch Cashfree order status', [
                'order_id' => $orderId,
                'status_code' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        }
    }
}
