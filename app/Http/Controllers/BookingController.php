<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\AmadeusService;
use PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    private $amadeus;

    public function __construct(AmadeusService $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    /**
     * Store a new booking.
     * Redirects to the payment page.
     * Prevents duplicate submissions using session token.
     */
   public function store(Request $request)
{
    $token = $request->input('booking_token');

    // Prevent duplicate submission
    if ($token !== $request->session()->get('booking_token')) {
        return redirect()->route('flights.confirm.get')
                         ->with('error', 'This booking form has already been submitted.');
    }

    // Remove token from session immediately after using
    $request->session()->forget('booking_token');

    // Validate and create booking (as before)
    $data = $request->validate([
        'first_name'      => 'required|string|max:255',
        'last_name'       => 'required|string|max:255',
        'email'           => 'required|email|max:255',
        'phone'           => 'required|string|max:20',
        'dob'             => 'nullable|date',
        'gender'          => 'nullable|in:MALE,FEMALE',
        'passport_number' => 'nullable|string|max:20',
        'passport_expiry' => 'nullable|date|after:today',
        'nationality'     => 'nullable|string|size:2',
        'flight_offer'    => 'required',
        'amount'          => 'required|numeric',
    ]);

    $flightOffer = is_string($data['flight_offer']) ? json_decode($data['flight_offer'], true) : $data['flight_offer'];

    $booking = Booking::create([
        'first_name'      => $data['first_name'],
        'last_name'       => $data['last_name'],
        'email'           => $data['email'],
        'phone'           => $data['phone'],
        'dob'             => $data['dob'],
        'gender'          => $data['gender'],
        'passport_number' => $data['passport_number'],
        'passport_expiry' => $data['passport_expiry'],
        'nationality'     => strtoupper($data['nationality'] ?? ''),
        'flight_offer'    => json_encode($flightOffer),
        'amount'          => $data['amount'],
        'status'          => 'pending',
    ]);

    // Redirect to payment page (PRG pattern prevents resubmit on refresh)
    return redirect()->route('payment.page', ['booking' => $booking->id])
                     ->with('success', 'Booking created! Please complete your payment.');
}


    /**
     * Complete the booking by issuing a ticket through Amadeus.
     */
    public function complete(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        try {
            $traveler = [
                "id" => "1",
                "dateOfBirth" => $booking->dob,
                "name" => [
                    "firstName" => $booking->first_name,
                    "lastName"  => $booking->last_name,
                ],
                "gender" => $booking->gender,
                "contact" => [
                    "emailAddress" => $booking->email,
                    "phones" => [
                        [
                            "deviceType" => "MOBILE",
                            "countryCallingCode" => "91",
                            "number" => $booking->phone,
                        ]
                    ]
                ],
                "documents" => [
                    [
                        "documentType" => "PASSPORT",
                        "number" => $booking->passport_number,
                        "expiryDate" => $booking->passport_expiry,
                        "issuanceCountry" => $booking->nationality,
                        "nationality" => $booking->nationality,
                        "holder" => true
                    ]
                ]
            ];

            $flightOffer = json_decode($booking->flight_offer, true);
            $order = $this->amadeus->createOrder($flightOffer, $traveler);

            $booking->status = 'ticketed';
            $booking->amadeus_order_id = $order['data']['id'] ?? null;
            $booking->save();

        } catch (\Exception $e) {
            $booking->status = 'order_failed';
            $booking->save();
            return response()->json(['error' => 'Amadeus order failed: ' . $e->getMessage()], 500);
        }

        // Generate PDF invoice & send email
        $pdf = PDF::loadView('pdf.invoice', compact('booking', 'order'));
        $invoicePath = storage_path("app/invoices/invoice_{$booking->id}.pdf");
        $pdf->save($invoicePath);

        Mail::send('emails.booking_success', compact('booking', 'order'), function ($message) use ($booking, $invoicePath) {
            $message->to($booking->email)
                    ->bcc('ops@youragency.com')
                    ->subject('Your Flight Booking Confirmation')
                    ->attach($invoicePath);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Booking completed and ticketed',
            'booking' => $booking,
            'order'   => $order,
        ]);
    }
}
