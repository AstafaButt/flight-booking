<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <!-- Admin Header -->
    <nav class="bg-blue-900 text-white shadow-lg w-full">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">onestoptravel Admin</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-blue-300">Welcome, {{ session('admin_username') }}</span>
                    <a href="{{ route('flights.index') }}" class="hover:text-blue-300">
                        <i class="fas fa-globe mr-1"></i> View Site
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-blue-300">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Admin Sidebar + Main Content -->
    <div class="flex">

        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings') }}" class="flex items-center p-2 text-blue-600 bg-blue-50 rounded-lg">
                            <i class="fas fa-ticket-alt mr-3"></i> Bookings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Bookings Management</h1>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $bookings->count() }}</div>
                    <div class="text-sm text-gray-600">Total Bookings</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                    <div class="text-sm text-gray-600">Confirmed</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $bookings->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-600">Pending</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($bookings->where('status', 'confirmed')->sum('amount'), 2) }}INR</div>
                    <div class="text-sm text-gray-600">Total Revenue</div>
                </div>
            </div>

            <!-- Search Input -->
            <div class="flex space-x-4 mb-4">
                <input type="text" 
                       id="bookingSearch"
                       placeholder="Search by name, email, or passport no..." 
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Passenger</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <!-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                               <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">DOB</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>-->
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Passport No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Passport Expiry</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nationality</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Booking Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            @php
                                $offer = json_decode($booking->flight_offer, true);
                                $currency = $offer['price']['currency'] ?? 'INR';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 whitespace-nowrap font-mono">#{{ $booking->id }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->first_name }} {{ $booking->last_name }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->email }}</td>
                              <!--  <td class="px-4 py-2 whitespace-nowrap">{{ $booking->phone }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->dob }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->gender }}</td>-->
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->passport_number }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->passport_expiry }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $booking->nationality }}</td>
                                <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $booking->amount }} {{ $currency }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->created_at->format('M j, Y') }}<br>
                                    <span class="text-gray-400">{{ $booking->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.booking.show', $booking->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded text-sm bg-blue-50 hover:bg-blue-100 transition duration-300">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Search Script -->
    <script>
        document.getElementById('bookingSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const passport = row.cells[6].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm) || passport.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>
