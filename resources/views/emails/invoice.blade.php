<p>Hello,</p>
<p>Thank you for your booking. Attached is your invoice.</p>
<p>Flight: {{ $booking->flight_data['itineraries'][0]['segments'][0]['departure']['iataCode'] }}
 → {{ $booking->flight_data['itineraries'][0]['segments'][0]['arrival']['iataCode'] }}</p>
<p>Amount Paid: {{ $booking->flight_data['price']['total'] }} INR</p>
