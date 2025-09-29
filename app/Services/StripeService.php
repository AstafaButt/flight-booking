<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function createPaymentIntent($amount, $currency = 'inr')
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // convert to paisa
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'capture_method' => 'manual', // manual capture after ticketing
        ]);
    }
}
