<h2>Flight Invoice</h2>
<p>Booking ID: {{ $booking->id }}</p>
<p>Status: {{ $booking->status }}</p>
<p>Email: {{ $booking->email }}</p>
<p>Amount: {{ $booking->flight_data['price']['total'] }} INR</p>
<p>From: {{ $booking->flight_data['itineraries'][0]['segments'][0]['departure']['iataCode'] }}</p>
<p>To: {{ $booking->flight_data['itineraries'][0]['segments'][0]['arrival']['iataCode'] }}</p>
