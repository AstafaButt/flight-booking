<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; }
        .details, .flight, .pricing { width: 100%; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Travel Agency</h2>
        <p>Invoice #{{ $booking->id }}</p>
    </div>

    <div class="details">
        <h4>Passenger Details</h4>
        <p><strong>Name:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
        <p><strong>Email:</strong> {{ $booking->email }}</p>
        <p><strong>Phone:</strong> {{ $booking->phone }}</p>
    </div>

    <div class="flight">
        <h4>Flight Details</h4>
        @php
            $segments = $order['data']['flightOffers'][0]['itineraries'][0]['segments'] ?? [];
        @endphp
        <table>
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Airline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($segments as $seg)
                    <tr>
                        <td>{{ $seg['departure']['iataCode'] }}</td>
                        <td>{{ $seg['arrival']['iataCode'] }}</td>
                        <td>{{ $seg['departure']['at'] }}</td>
                        <td>{{ $seg['arrival']['at'] }}</td>
                        <td>{{ $seg['carrierCode'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pricing">
        <h4>Pricing</h4>
        <p><strong>Total Price:</strong> {{ $order['data']['flightOffers'][0]['price']['total'] ?? $booking->amount }} {{ $order['data']['flightOffers'][0]['price']['currency'] ?? 'INR' }}</p>
    </div>
</body>
</html>
