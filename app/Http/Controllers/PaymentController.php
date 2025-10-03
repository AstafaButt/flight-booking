<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Services\AmadeusService;
use App\Services\StripeService;
use App\Jobs\BookFlightAmadeusJob;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    protected AmadeusService $amadeus;
    protected StripeService $stripe;

    public function __construct(AmadeusService $amadeus, StripeService $stripe)
    {
        $this->amadeus = $amadeus;
        $this->stripe = $stripe;
    }

    /**
     * Show payment page
     */
    public function show(int $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('payment.page', compact('booking'));
    }

    /**
     * Create Stripe PaymentIntent
     */
    public function createIntent(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        try {
            $booking = Booking::findOrFail($request->booking_id);

            // Create payment intent with metadata
            $paymentIntent = $this->stripe->createPaymentIntent(
                $booking->amount,
                'inr',
                [
                    'booking_id'     => $booking->id,
                    'customer_email' => $booking->email,
                ],
                'Flight Booking #' . $booking->id
            );

            Log::info('Stripe PaymentIntent created', [
                'booking_id'        => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount'            => $booking->amount,
            ]);

            return response()->json([
                'clientSecret'     => $paymentIntent->client_secret,
                'paymentIntentId'  => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment intent error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Handle successful payment redirect
     */
    public function handleSuccess(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required',
            'booking_id'        => 'required|exists:bookings,id',
        ]);

        try {
            $booking = Booking::findOrFail($request->booking_id);

            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
            }

            // Update booking
            $booking->update([
                'status'                   => 'confirmed',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid_at'                  => now(),
            ]);

            Log::info('Payment successful', [
                'booking_id'        => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
            ]);

            // Dispatch Amadeus booking
            $this->bookFlightOnAmadeus($booking);

            return redirect()->route('booking.success', $booking->id)
                ->with('success', 'Payment completed and flight booking in progress!');
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->route('payment.show', $request->booking_id)
                ->with('error', 'Payment verification failed.');
        }
    }

    /**
     * Stripe webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSuccessWebhook($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailure($event->data->object);
                    break;
            }

            return response()->json(['received' => true]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook failed'], 400);
        }
    }

    /**
     * Webhook: Payment success
     */
    private function handlePaymentSuccessWebhook($paymentIntent)
    {
        $booking = Booking::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$booking) return;

        $booking->update([
            'status'  => 'confirmed',
            'paid_at' => now(),
        ]);

        Log::info("Webhook: Payment confirmed for booking #{$booking->id}");

        $this->bookFlightOnAmadeus($booking);
    }

    /**
     * Webhook: Payment failure
     */
    private function handlePaymentFailure($paymentIntent)
    {
        $booking = Booking::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$booking) return;

        $booking->update(['status' => 'payment_failed']);
        Log::error("Webhook: Payment failed for booking #{$booking->id}");
    }

    /**
     * Dispatch Amadeus flight booking
     */
    private function bookFlightOnAmadeus(Booking $booking): void
    {
        BookFlightAmadeusJob::dispatch($booking, $this->amadeus)
            ->delay(now()->addSeconds(10));
    }
}
