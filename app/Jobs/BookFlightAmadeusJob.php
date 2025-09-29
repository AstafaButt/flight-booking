<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\AmadeusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookFlightAmadeusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Booking $booking;
    public int $tries = 3; // Retry 3 times before failing
    public int $backoff = 60; // Retry after 60 seconds

    protected AmadeusService $amadeus;

    public function __construct(Booking $booking, AmadeusService $amadeus)
    {
        $this->booking = $booking;
        $this->amadeus = $amadeus;
    }

    public function handle()
    {
        try {
            $passengerDetails = [[
                'id' => '1',
                'dateOfBirth' => $this->booking->dob,
                'name' => [
                    'firstName' => $this->booking->first_name,
                    'lastName' => $this->booking->last_name,
                ],
                'gender' => strtoupper(substr($this->booking->gender, 0, 1)),
                'contact' => [
                    'emailAddress' => $this->booking->email,
                    'phones' => [[
                        'deviceType' => 'MOBILE',
                        'countryCallingCode' => '91',
                        'number' => $this->booking->phone,
                    ]]
                ],
                'documents' => [[
                    'documentType' => 'PASSPORT',
                    'number' => $this->booking->passport_number,
                    'expiryDate' => $this->booking->passport_expiry,
                    'issuanceCountry' => $this->booking->nationality,
                ]]
            ]];

            $amadeusResponse = $this->amadeus->bookFlight(json_decode($this->booking->flight_offer, true), $passengerDetails);

            if (!empty($amadeusResponse['data']['id'])) {
                $this->booking->update(['amadeus_pnr' => $amadeusResponse['data']['id']]);
                Log::info('Flight booked on Amadeus for booking #' . $this->booking->id);
            } else {
                Log::warning('Amadeus booking failed for booking #' . $this->booking->id);
                $this->fail(); // triggers retry
            }
        } catch (\Exception $e) {
            Log::error('Amadeus booking exception for booking #' . $this->booking->id . ': ' . $e->getMessage());
            $this->fail(); // triggers retry
        }
    }
}
