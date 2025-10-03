<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;
use App\Services\AmadeusService;
use App\Jobs\BookFlightAmadeusJob;

class PaymentController extends Controller
{
    protected AmadeusService $amadeus;

    public function __construct(AmadeusService $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    /**
     * Show payment page for a booking
     */
    public function show(int $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $stripeAmount = (int) ($booking->amount * 100); // Convert to cents
        return view('payment.page', compact('booking', 'stripeAmount'));
    }

    /**
     * Create Stripe PaymentIntent
     */
    public function createIntent(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $booking = Booking::findOrFail($request->booking_id);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($booking->amount * 100),
                'currency' => 'inr',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'customer_email' => $booking->email,
                ],
                'description' => 'Flight Booking #' . $booking->id,
            ]);

            Log::info('Stripe PaymentIntent created', [
                'booking_id' => $booking->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $booking->amount,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Payment intent creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Handle successful payment (Stripe redirect)
     */
    /**
 * Handle successful payment (Stripe redirect)
 */
public function handleSuccess(Request $request)
{
    $request->validate([
        'payment_intent_id' => 'required',
        'booking_id' => 'required|exists:bookings,id',
    ]);

    try {
        $booking = Booking::findOrFail($request->booking_id);

        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status !== 'succeeded') {
            // If payment isn't succeeded yet, check if it requires action
            if ($paymentIntent->status === 'requires_action') {
                return redirect()->back()->with('error', 'Payment requires additional action.');
            }
            throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
        }

        // Update booking status
        $booking->update([
            'status' => 'confirmed',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'paid_at' => now(),
        ]);

        Log::info('Payment successful', [
            'booking_id' => $booking->id,
            'payment_intent_id' => $paymentIntent->id,
        ]);

        // Dispatch job for Amadeus booking
        $this->bookFlightOnAmadeus($booking);

        return redirect()->route('booking.success', $booking->id)
                         ->with('success', 'Payment completed and flight booking in progress!');

    } catch (\Exception $e) {
        Log::error('Payment verification error: ' . $e->getMessage());
        return redirect()->route('payment.show', $request->booking_id)
                         ->with('error', 'Payment verification failed: ' . $e->getMessage());
    }
}
    /**
     * Stripe Webhook handler
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
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccessWebhook($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailure($paymentIntent);
                    break;
            }

            return response()->json(['received' => true]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook failed'], 400);
        }
    }

    /**
     * Handle successful payment via webhook
     */
    private function handlePaymentSuccessWebhook($paymentIntent)
    {
        $booking = Booking::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$booking) return;

        $booking->update([
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        Log::info('Webhook: Payment confirmed for booking #' . $booking->id);

        $this->bookFlightOnAmadeus($booking);
    }

    /**
     * Handle failed payment via webhook
     */
    private function handlePaymentFailure($paymentIntent)
    {
        $booking = Booking::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$booking) return;

        $booking->update(['status' => 'payment_failed']);
        Log::error('Webhook: Payment failed for booking #' . $booking->id);
    }

    /**
     * Book flight on Amadeus
     */
    private function bookFlightOnAmadeus(Booking $booking): void
{
    BookFlightAmadeusJob::dispatch($booking, $this->amadeus)
        ->delay(now()->addSeconds(10)); // initial delay before first attempt
}
}
