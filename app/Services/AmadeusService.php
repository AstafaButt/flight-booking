<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AmadeusService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->baseUrl = env('AMADEUS_BASE_URL');
        $this->clientId = env('AMADEUS_CLIENT_ID');
        $this->clientSecret = env('AMADEUS_CLIENT_SECRET');
    }

    /**
     * Get Amadeus OAuth token (cached)
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember('amadeus_token', 1700, function () {
            try {
                $response = Http::asForm()
                    ->timeout(60)
                    ->post("{$this->baseUrl}/v1/security/oauth2/token", [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]);

                if ($response->failed()) {
                    Log::error('Amadeus token request failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return null;
                }

                return $response->json()['access_token'] ?? null;

            } catch (\Exception $e) {
                Log::error('Amadeus token exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Search flights via Amadeus API
     */
    public function searchFlights(string $origin, string $destination, string $departureDate, int $adults = 1, string $currency = 'INR'): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => true, 'message' => 'Failed to get Amadeus access token.'];
        }

        try {
            $response = Http::withToken($token)
                ->timeout(120)
                ->get("{$this->baseUrl}/v2/shopping/flight-offers", [
                    'originLocationCode' => $origin,
                    'destinationLocationCode' => $destination,
                    'departureDate' => $departureDate,
                    'adults' => $adults,
                    'currencyCode' => $currency,
                ]);

            if ($response->failed()) {
                Log::error('Amadeus flight search failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return ['error' => true, 'message' => 'Unable to fetch flight offers at this time.'];
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Amadeus flight search exception: ' . $e->getMessage());
            return ['error' => true, 'message' => 'Flight search failed due to an exception.'];
        }
    }

    /**
     * Book a flight on Amadeus
     *
     * @param array $flightOffer Single flight offer from search
     * @param array $passengerDetails Array of passenger details
     * @return array|null Amadeus booking response or null if failed
     */
    public function bookFlight(array $flightOffer, array $passengerDetails): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('Amadeus booking failed: No access token.');
            return null;
        }

        $payload = [
            'data' => [
                'type' => 'flight-order',
                'flightOffers' => [$flightOffer],
                'travelers' => $passengerDetails
            ]
        ];

        try {
            $response = Http::withToken($token)
                ->timeout(120)
                ->post("{$this->baseUrl}/v1/booking/flight-orders", $payload);

            if ($response->failed()) {
                Log::error('Amadeus booking failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Amadeus booking exception: ' . $e->getMessage());
            return null;
        }
    }
}
