<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>Hello {{ $booking->first_name }},</h2>

    <p>Thank you for booking with <strong>Travel Agency</strong>. Your flight has been successfully ticketed.</p>

    <h3>Booking Details:</h3>
    <ul>
        <li><strong>Booking ID:</strong> {{ $booking->id }}</li>
        <li><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
        <li><strong>Order ID (Amadeus):</strong> {{ $order['data']['id'] ?? 'N/A' }}</li>
    </ul>

    <h3>Flight Itinerary:</h3>
    <ul>
        @php
            $segments = $order['data']['flightOffers'][0]['itineraries'][0]['segments'] ?? [];
        @endphp
        @foreach ($segments as $seg)
            <li>
                {{ $seg['departure']['iataCode'] }} → {{ $seg['arrival']['iataCode'] }}
                | Depart: {{ $seg['departure']['at'] }}
                | Arrive: {{ $seg['arrival']['at'] }}
                | Airline: {{ $seg['carrierCode'] }}
            </li>
        @endforeach
    </ul>

    <p>You can find your attached <strong>invoice</strong> in this email.</p>

    <br>
    <p>onestoptravel,<br>
    Travel Agency Team</p>
</body>
</html>
