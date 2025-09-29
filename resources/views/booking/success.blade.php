<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50">
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">onestoptravel</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Success Header -->
                <div class="bg-green-500 text-white py-8 text-center">
                    <div class="w-20 h-20 bg-white text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-3xl"></i>
                    </div>
                    <h1 class="text-4xl font-bold mb-2">Booking Confirmed!</h1>
                    <p class="text-green-100 text-lg">Your flight has been successfully booked</p>
                </div>

                <!-- Booking Details -->
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Booking Information -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-ticket-alt mr-3 text-blue-500"></i> Booking Details
                            </h2>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Booking Reference:</span>
                                    <span class="text-blue-600 font-mono">#{{ $booking->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Passenger:</span>
                                    <span>{{ $booking->first_name }} {{ $booking->last_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Email:</span>
                                    <span>{{ $booking->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Phone:</span>
                                    <span>{{ $booking->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Status:</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">Confirmed</span>
                                </div>
                            </div>
                        </div>

                        <!-- Flight Information -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-plane mr-3 text-blue-500"></i> Flight Information
                            </h2>
                            
                            @php
                                $offer = json_decode($booking->flight_offer, true);
                                $itinerary = $offer['itineraries'][0] ?? [];
                                $firstSegment = $itinerary['segments'][0] ?? [];
                                $lastSegment = end($itinerary['segments']) ?? [];
                            @endphp
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Route:</span>
                                    <span>{{ $firstSegment['departure']['iataCode'] ?? 'N/A' }} → {{ $lastSegment['arrival']['iataCode'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Departure:</span>
                                    <span>
                                        @if(isset($firstSegment['departure']['at']))
                                            {{ \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('M j, Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Duration:</span>
                                    <span>{{ substr($itinerary['duration'] ?? '', 2) ?: 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Airline:</span>
                                    <span>{{ $firstSegment['carrierCode'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">Amount Paid:</span>
                                    <span class="text-green-600 font-bold">{{ $booking->amount }} {{ $offer['price']['currency'] ?? 'USD' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Next Steps
                        </h3>
                        <ul class="text-blue-700 space-y-2">
                            <li>• You will receive a confirmation email shortly</li>
                            <li>• Check-in opens 24 hours before departure</li>
                            <li>• Bring valid ID and travel documents to the airport</li>
                            <li>• Arrive at least 2 hours before departure</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('flights.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 text-center">
                            <i class="fas fa-search mr-2"></i> Book Another Flight
                        </a>
                        <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                            <i class="fas fa-print mr-2"></i> Print Confirmation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to top when page loads
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
</body>
</html>