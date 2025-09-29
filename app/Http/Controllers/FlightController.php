<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AmadeusService;

class FlightController extends Controller
{
    private $amadeus;

    public function __construct(AmadeusService $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    /**
     * Show flight search page
     */
    public function index()
    {
        return view('flights.search');
    }

    /**
     * Search for flights using Amadeus API
     */
    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'date' => 'required|date',
        ]);

        try {
            $flights = $this->amadeus->searchFlights(
                $request->origin,
                $request->destination,
                $request->date
            );

            if (isset($flights['error'])) {
                return redirect()->route('flights.index')
                    ->with('error', $flights['message']);
            }

            // Save flight data in session for confirmation
            $request->session()->put('flight_data', $flights);

            return view('flights.results', compact('flights'));

        } catch (\Exception $e) {
            \Log::error('Flight search failed: ' . $e->getMessage());
            return redirect()->route('flights.index')
                ->with('error', 'Something went wrong while searching for flights. Please try again later.');
        }
    }

    /**
     * Show flight confirmation page
     */
   public function showConfirm(Request $request)
{
    $flightData = $request->session()->get('flight_data');

    if (!$flightData) {
        return redirect()->route('flights.index')
            ->with('error', 'No flight selected. Please search for flights again.');
    }

    // Generate a one-time booking token
    $bookingToken = session()->get('booking_token', bin2hex(random_bytes(16)));
    $request->session()->put('booking_token', $bookingToken);

    return view('flights.confirm', [
        'flight' => $flightData,
        'bookingToken' => $bookingToken
    ]);
}


    /**
     * Confirm flight selection and store in session for booking
     */
    public function confirm(Request $request)
    {
        $flightJson = $request->input('flight');

        if (!$flightJson) {
            return redirect()->route('flights.index')
                ->with('error', 'No flight data received. Please select a flight again.');
        }

        $flightData = json_decode(html_entity_decode($flightJson), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->route('flights.index')
                ->with('error', 'Invalid flight data format. Please try again.');
        }

        // Save flight data in session
        $request->session()->put('flight_data', $flightData);

        return redirect()->route('flights.confirm.get');
    }

    /**
     * Save passenger details before booking
     */
    public function savePassengerDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|in:MALE,FEMALE',
            'passport_number' => 'required|string|max:20',
            'passport_expiry' => 'required|date|after:today',
            'nationality' => 'required|string|size:2',
        ]);

        // Save passenger details in session
        $request->session()->put('passenger_details', $request->all());

        // Generate a new booking token for BookingController to prevent duplicate submissions
        $request->session()->put('booking_token', bin2hex(random_bytes(16)));

        return redirect()->route('payment.page');
    }
}
