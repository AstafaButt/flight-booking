@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Passenger Details & Payment</h2>

    <form id="booking-form">
        @csrf

        <!-- Passenger info -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
        </div>

        <!-- Flight + Payment Info -->
        <input type="hidden" name="flight_offer" value="{{ json_encode($flight) }}">
        <input type="hidden" name="amount" value="{{ $flight['price']['total'] }}">

        <div class="mb-3">
            <h5>
                Flight: {{ $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] }}
                →
                {{ $flight['itineraries'][0]['segments'][0]['arrival']['iataCode'] }}
            </h5>
            <p>
                <strong>Price:</strong> {{ $flight['price']['total'] }} INR <br>
                <strong>Airline:</strong> {{ $flight['validatingAirlineCodes'][0] ?? 'N/A' }}
            </p>
        </div>

        <!-- Stripe card element -->
        <div id="card-element" class="mb-3"></div>
        <div id="card-errors" class="text-danger mb-3"></div>

        <button id="pay-button" class="btn btn-success w-100">Pay & Confirm</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('booking-form');
    const payButton = document.getElementById('pay-button');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        payButton.disabled = true;
        payButton.textContent = 'Processing...';

        let formData = new FormData(form);

        // 1. Call backend to create booking + PaymentIntent
        let response = await fetch("{{ route('bookings.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': formData.get('_token') },
            body: formData
        });

        let data = await response.json();
        if (!data.client_secret) {
            alert("Booking creation failed");
            return;
        }

        // 2. Confirm card payment
        const {error, paymentIntent} = await stripe.confirmCardPayment(data.client_secret, {
            payment_method: { card: card }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            payButton.disabled = false;
            payButton.textContent = 'Pay & Confirm';
        } else {
            // Success
            alert("Payment successful! Booking ID: " + data.booking_id);
            window.location.href = "/dashboard"; // or redirect to booking summary page
        }
    });
</script>
@endsection
