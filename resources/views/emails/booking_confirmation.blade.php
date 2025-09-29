<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; }
        .footer { background: #e5e7eb; padding: 20px; text-align: center; font-size: 12px; }
        .booking-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .flight-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed!</h1>
            <p>Your flight has been successfully booked</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $booking->first_name }},</p>
            <p>Thank you for choosing onestoptravel! Your flight booking has been confirmed.</p>

            <div class="booking-details">
                <h3>Booking Details</h3>
                <p><strong>Booking Reference:</strong> #{{ $booking->id }}</p>
                <p><strong>Passenger:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
                <p><strong>Email:</strong> {{ $booking->email }}</p>
                <p><strong>Phone:</strong> {{ $booking->phone }}</p>
                <p><strong>Status:</strong> <span style="color: green; font-weight: bold;">Confirmed</span></p>
            </div>

            @php
                $offer = json_decode($booking->flight_offer, true);
                $itinerary = $offer['itineraries'][0] ?? [];
                $firstSegment = $itinerary['segments'][0] ?? [];
                $lastSegment = end($itinerary['segments']) ?? [];
            @endphp

            <div class="flight-info">
                <h3>Flight Information</h3>
                <p><strong>Route:</strong> {{ $firstSegment['departure']['iataCode'] ?? 'N/A' }} → {{ $lastSegment['arrival']['iataCode'] ?? 'N/A' }}</p>
                <p><strong>Departure:</strong> 
                    @if(isset($firstSegment['departure']['at']))
                        {{ \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('F j, Y \a\t H:i') }}
                    @else
                        N/A
                    @endif
                </p>
                <p><strong>Duration:</strong> {{ substr($itinerary['duration'] ?? '', 2) ?: 'N/A' }}</p>
                <p><strong>Airline:</strong> {{ $firstSegment['carrierCode'] ?? 'N/A' }}</p>
                <p><strong>Amount Paid:</strong> {{ $booking->amount }} {{ $offer['price']['currency'] ?? 'USD' }}</p>
            </div>

            <h3>Next Steps:</h3>
            <ul>
                <li>Check-in opens 24 hours before departure</li>
                <li>Bring valid ID and travel documents to the airport</li>
                <li>Arrive at least 2 hours before departure</li>
                <li>Keep this booking reference for your records</li>
            </ul>

            <p>If you have any questions, please contact our support team.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing onestoptravel!</p>
            <p>Email: support@onestoptravel.com | Phone: +1 (555) 123-4567</p>
            <p>© 2025 onestoptravel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>