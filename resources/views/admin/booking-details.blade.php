<!-- Success/Error Messages -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-2"></i>
        <span class="text-green-700">{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
        <span class="text-red-700">{{ session('error') }}</span>
    </div>
</div>
@endif
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Header -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">onestoptravel Admin</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.bookings') }}" class="hover:text-blue-300">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Bookings
                    </a>
                    <a href="{{ route('flights.index') }}" class="hover:text-blue-300">
                        <i class="fas fa-globe mr-1"></i> View Site
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Booking Details #{{ $booking->id }}</h1>
                <div class="flex space-x-3">
            <!--        <button onclick="printPage()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
    <i class="fas fa-print mr-2"></i> Print
</button>-->
                   <!-- <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-envelope mr-2"></i> Resend Email
                    </button> -->
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Passenger Details -->
               <!-- Passenger Details -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4 flex items-center">
        <i class="fas fa-user mr-3 text-blue-500"></i> Passenger Details
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-600">Full Name</label>
            <p class="mt-1 text-lg">{{ $booking->first_name }} {{ $booking->last_name }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Email</label>
            <p class="mt-1 text-lg">{{ $booking->email }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Phone</label>
            <p class="mt-1 text-lg">{{ $booking->phone }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Date of Birth</label>
            <p class="mt-1 text-lg">{{ $booking->dob }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Gender</label>
            <p class="mt-1 text-lg">{{ $booking->gender }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Passport Number</label>
            <p class="mt-1 text-lg">{{ $booking->passport_number }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Passport Expiry</label>
            <p class="mt-1 text-lg">{{ $booking->passport_expiry }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Nationality</label>
            <p class="mt-1 text-lg">{{ $booking->nationality }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600">Booking Status</label>
            <p class="mt-1">
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                       ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </p>
        </div>
    </div>
</div>


                    <!-- Flight Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-plane mr-3 text-blue-500"></i> Flight Information
                        </h2>
                        @php
                            $offer = json_decode($booking->flight_offer, true);
                            $itinerary = $offer['itineraries'][0] ?? [];
                            $firstSegment = $itinerary['segments'][0] ?? [];
                            $lastSegment = end($itinerary['segments']) ?? [];
                        @endphp
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-semibold text-lg">{{ $firstSegment['departure']['iataCode'] ?? 'N/A' }} → {{ $lastSegment['arrival']['iataCode'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600">{{ substr($itinerary['duration'] ?? '', 2) ?: 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-lg">{{ $booking->amount }} {{ $offer['price']['currency'] ?? 'INR' }}</div>
                                    <div class="text-sm text-gray-600">{{ $firstSegment['carrierCode'] ?? 'N/A' }}{{ $firstSegment['number'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions & Timeline -->
                <div class="space-y-6">
                    <!-- Booking Actions -->
                   <!-- Booking Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Booking Actions</h2>
    <div class="space-y-3">
        
        <!-- Confirm Booking Form -->
        @if($booking->status === 'pending')
        <form action="{{ route('admin.booking.confirm', $booking->id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to confirm this booking?')"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i> Confirm Booking
            </button>
        </form>
        @endif

        <!-- Cancel Booking Form -->
        @if(in_array($booking->status, ['pending', 'confirmed']))
        <form action="{{ route('admin.booking.cancel', $booking->id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-times-circle mr-2"></i> Cancel Booking
            </button>
        </form>
        @endif

        <!-- Refund Payment Form -->
        @if($booking->status === 'confirmed' && $booking->stripe_payment_intent_id)
        <form action="{{ route('admin.booking.refund', $booking->id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Are you sure you want to process a refund? This action cannot be undone.')"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-money-bill-wave mr-2"></i> Refund Payment
            </button>
        </form>
        @endif

        <!-- Status Display -->
        <div class="p-3 bg-gray-100 rounded-lg text-center">
            <span class="font-semibold">Current Status:</span>
            <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium
                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
    </div>
</div>

                    <!-- Booking Timeline -->
                   <!-- Booking Timeline -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Timeline</h2>
    <div class="space-y-4">
        <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
            <div>
                <div class="font-medium">Booking Created</div>
                <div class="text-sm text-gray-600">{{ $booking->created_at->format('M j, Y H:i') }}</div>
            </div>
        </div>
        
        @if($booking->paid_at)
        <div class="flex items-center">
            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
            <div>
                <div class="font-medium">Payment Completed</div>
                <div class="text-sm text-gray-600">{{ $booking->paid_at->format('M j, Y H:i') }}</div>
            </div>
        </div>
        @endif
        
        @if($booking->refunded_at)
        <div class="flex items-center">
            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
            <div>
                <div class="font-medium">Payment Refunded</div>
                <div class="text-sm text-gray-600">{{ $booking->refunded_at->format('M j, Y H:i') }}</div>
                @if($booking->stripe_refund_id)
                <div class="text-xs text-gray-500">Refund ID: {{ $booking->stripe_refund_id }}</div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>