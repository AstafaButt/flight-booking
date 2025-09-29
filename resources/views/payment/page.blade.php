<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - SkyJourney</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="font-sans bg-gray-50">

<!-- Navigation Bar -->
<nav class="bg-blue-900 text-white shadow-lg">
    <div class="container mx-auto px-4 flex justify-between items-center py-4">
        <div class="flex items-center space-x-2">
            <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
            <span class="text-xl font-bold">SkyJourney</span>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600">Secure payment processed by Stripe</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Booking Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-receipt mr-2 text-blue-500"></i> Booking Summary
                    </h3>

                    @php
                        $passenger = $booking;
                        $offer = json_decode($booking->flight_offer, true);
                        $firstSegment = $offer['itineraries'][0]['segments'][0] ?? [];
                    @endphp

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Booking ID:</span>
                            <span class="font-medium">#{{ $booking->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Passenger:</span>
                            <span class="font-medium">{{ $passenger->first_name }} {{ $passenger->last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email:</span>
                            <span class="font-medium">{{ $passenger->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phone:</span>
                            <span class="font-medium">{{ $passenger->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date of Birth:</span>
                            <span class="font-medium">{{ $passenger->dob ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Gender:</span>
                            <span class="font-medium">{{ $passenger->gender ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Passport Number:</span>
                            <span class="font-medium">{{ $passenger->passport_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Passport Expiry:</span>
                            <span class="font-medium">{{ $passenger->passport_expiry ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nationality:</span>
                            <span class="font-medium">{{ $passenger->nationality ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Flight:</span>
                            <span class="font-medium">
                                {{ $firstSegment['departure']['iataCode'] ?? 'N/A' }} →
                                {{ $firstSegment['arrival']['iataCode'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total Amount:</span>
                                <span class="text-green-600">{{ $booking->amount }} {{ $offer['price']['currency'] ?? 'INR' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <form id="payment-form">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                            <div id="card-element" class="p-3 border border-gray-300 rounded-lg bg-white"></div>
                            <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                        </div>

                        <button type="submit" id="submit-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i>
                            <span id="button-text">Pay {{ $booking->amount }} {{ $offer['price']['currency'] ?? 'INR' }}</span>
                            <span id="button-spinner" class="hidden ml-2">
                                <i class="fas fa-spinner fa-spin"></i> Processing...
                            </span>
                        </button>

                        <div class="text-center mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-center space-x-4 text-gray-400 mb-2">
                                <i class="fas fa-shield-alt text-2xl"></i>
                                <i class="fas fa-lock text-2xl"></i>
                                <i class="fab fa-cc-stripe text-2xl"></i>
                            </div>
                            <p class="text-xs text-gray-500">Payments secured by Stripe • 256-bit SSL encryption</p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {style:{base:{fontSize:'16px', color:'#424770', '::placeholder':{color:'#aab7c4'}}}});
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        buttonSpinner.classList.remove('hidden');
        cardErrors.textContent = '';

        try {
            const response = await fetch('{{ route("payment.create") }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({ booking_id: '{{ $booking->id }}' })
            });

            const { clientSecret } = await response.json();

            const { error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ $booking->first_name }} {{ $booking->last_name }}',
                        email: '{{ $booking->email }}',
                        phone: '{{ $booking->phone }}',
                    }
                }
            });

            if(error){
                cardErrors.textContent = error.message;
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                buttonSpinner.classList.add('hidden');
            } else {
                window.location.href = '{{ route("booking.success", $booking->id) }}';
            }

        } catch(err){
            console.error(err);
            cardErrors.textContent = 'An error occurred. Please try again.';
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    });

    cardElement.on('change', ({error}) => {
        cardErrors.textContent = error ? error.message : '';
    });
</script>

</body>
</html>
