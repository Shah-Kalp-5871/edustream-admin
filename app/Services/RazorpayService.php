<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $keyId;
    protected $keySecret;
    protected $baseUrl = 'https://api.razorpay.com/v1';

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id');
        $this->keySecret = config('services.razorpay.key_secret');
    }

    public function createOrder($amount, $receiptId, $notes = [])
    {
        $response = Http::withoutVerifying()
            ->withBasicAuth($this->keyId, $this->keySecret)
            ->post("{$this->baseUrl}/orders", [
                'receipt' => $receiptId,
                'amount' => $amount * 100, // Amount in paise
                'currency' => 'INR',
                'notes' => $notes,
            ]);

        if ($response->failed()) {
            Log::error('Razorpay Order Creation Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Failed to create Razorpay order: ' . ($response->json()['error']['description'] ?? $response->body()));
        }

        return $response->json();
    }

    public function verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)
    {
        $generatedSignature = hash_hmac('sha256', "{$razorpayOrderId}|{$razorpayPaymentId}", $this->keySecret);
        
        return hash_equals($generatedSignature, $razorpaySignature);
    }
}
