<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Flight - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="font-sans bg-gray-50">

    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                <span class="text-xl font-bold">onestoptravel</span>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('flights.index') }}" class="hover:text-blue-300">Home</a>
                <a href="#" class="hover:text-blue-300">Flights</a>
                <a href="#" class="hover:text-blue-300">My Bookings</a>
                <a href="#" class="hover:text-blue-300">Support</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Confirm Your Flight</h2>

        @php
            $flight = session('flight_data');
            $passenger = session('passenger_details') ?? [];
            $bookingToken = session('booking_token');
        @endphp

        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-lg mb-2">Flight Details</h3>
            @if($flight)
                @php
                    $departure = $flight['itineraries'][0]['segments'][0]['departure']['at'] ?? 'N/A';
                    $arrival = $flight['itineraries'][0]['segments'][0]['arrival']['at'] ?? 'N/A';
                    $origin = $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? 'N/A';
                    $destination = $flight['itineraries'][0]['segments'][0]['arrival']['iataCode'] ?? 'N/A';
                    $price = $flight['price']['total'] ?? '0';
                @endphp
                <p><strong>From:</strong> {{ $origin }}</p>
                <p><strong>To:</strong> {{ $destination }}</p>
                <p><strong>Departure:</strong> {{ $departure }}</p>
                <p><strong>Arrival:</strong> {{ $arrival }}</p>
                <p><strong>Price:</strong> {{ $price }} INR</p>
            @else
                <p class="text-red-500">No flight data available. Please go back and select a flight.</p>
            @endif
        </div>

        <form action="{{ route('flights.book') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
    @csrf
    <input type="hidden" name="booking_token" value="{{ $bookingToken }}">
    <input type="hidden" name="flight_offer" value="{{ json_encode($flight) }}">
    <input type="hidden" name="amount" value="{{ $price }}">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label for="first_name" class="block font-semibold mb-1">First Name</label>
            <input type="text" name="first_name" id="first_name"
                value="{{ old('first_name', $passenger['first_name'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="last_name" class="block font-semibold mb-1">Last Name</label>
            <input type="text" name="last_name" id="last_name"
                value="{{ old('last_name', $passenger['last_name'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email"
                value="{{ old('email', $passenger['email'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="phone" class="block font-semibold mb-1">Phone</label>
            <input type="text" name="phone" id="phone"
                value="{{ old('phone', $passenger['phone'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="dob" class="block font-semibold mb-1">Date of Birth</label>
            <input type="date" name="dob" id="dob"
                value="{{ old('dob', $passenger['dob'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('dob')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="gender" class="block font-semibold mb-1">Gender</label>
            <select name="gender" id="gender" class="w-full border p-2 rounded" required>
                <option value="">Select Gender</option>
                <option value="MALE" {{ old('gender', $passenger['gender'] ?? '') == 'MALE' ? 'selected' : '' }}>Male</option>
                <option value="FEMALE" {{ old('gender', $passenger['gender'] ?? '') == 'FEMALE' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="passport_number" class="block font-semibold mb-1">Passport Number</label>
            <input type="text" name="passport_number" id="passport_number"
                value="{{ old('passport_number', $passenger['passport_number'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('passport_number')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="passport_expiry" class="block font-semibold mb-1">Passport Expiry</label>
            <input type="date" name="passport_expiry" id="passport_expiry"
                value="{{ old('passport_expiry', $passenger['passport_expiry'] ?? '') }}"
                class="w-full border p-2 rounded" required>
            @error('passport_expiry')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="nationality" class="block font-semibold mb-1">Nationality (2-letter code)</label>
            <input type="text" name="nationality" id="nationality"
                value="{{ old('nationality', $passenger['nationality'] ?? '') }}"
                class="w-full border p-2 rounded" maxlength="2" required>
            @error('nationality')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

    </div>

    <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Confirm & Book
        </button>
    </div>

    @if(session('error'))
        <p class="text-red-500 mt-4">{{ session('error') }}</p>
    @endif
</form>

    </div>
</body>
</html>
