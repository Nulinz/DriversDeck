<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CashfreeService
{
    public function createOrder($orderId, $amount, $customerName, $customerEmail, $customerPhone, $customerId = null)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-version' => '2025-01-01', // Updated API version
                'x-client-id' => env('CASHFREE_APP_ID'),
                'x-client-secret' => env('CASHFREE_SECRET_KEY'),
                'x-environment' => env('CASHFREE_ENVIRONMENT', 'PRODUCTION'), // Added environment header
            ])->post(env('CASHFREE_API_URL'), [
                'order_id' => $orderId,
                'order_amount' => $amount,
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id' => $customerId ? 'CORP_' . $customerId : 'CORP_' . time(),
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'customer_phone' => $customerPhone,
                ],
                'order_meta' => [
                    'return_url' => route('auth.payment_callback') . '?order_id=' . $orderId,
                    'notify_url' => route('auth.payment_callback'),
                ],
                'order_note' => 'Subscription payment for ' . $customerName
            ]);

            $responseData = $response->json();
            
            // Log the response for debugging
            Log::info('Cashfree API Response: ', $responseData);
            
            return $responseData;
            
        } catch (\Exception $e) {
            Log::error('Cashfree API Error: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment($orderId)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-version' => '2025-01-01', // Updated API version
                'x-client-id' => env('CASHFREE_APP_ID'),
                'x-client-secret' => env('CASHFREE_SECRET_KEY'),
                'x-environment' => env('CASHFREE_ENVIRONMENT', 'PRODUCTION'), // Added environment header
            ])->get(env('CASHFREE_API_URL') . '/' . $orderId . '/payments');

            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getOrderStatus($orderId)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-version' => '2025-01-01',
                'x-client-id' => env('CASHFREE_APP_ID'),
                'x-client-secret' => env('CASHFREE_SECRET_KEY'),
                'x-environment' => env('CASHFREE_ENVIRONMENT', 'PRODUCTION'),
            ])->get(env('CASHFREE_API_URL') . '/' . $orderId);

            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Order status check failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}