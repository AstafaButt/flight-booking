<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent($amount, $currency = 'inr', $metadata = [], $description = null)
    {
        return PaymentIntent::create([
            'amount'   => (int) ($amount * 100),
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'capture_method'       => 'automatic',
            'metadata'             => $metadata,
            'description'          => $description,
        ]);
    }
}
