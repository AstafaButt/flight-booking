<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - onestoptravel</title>
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

    <!-- Admin Sidebar -->
    <div class="flex">
        <div class="w-64 bg-white shadow-md min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-blue-600 bg-blue-50 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-ticket-alt mr-3"></i>
                            Bookings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Bookings</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Confirmed</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['confirmed_bookings'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Pending</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_bookings'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Revenue</h3>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['revenue'], 2) }}INR</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
      <!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow-md mt-8">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Recent Bookings</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Passenger Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <!--<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
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
                @foreach($recentBookings as $booking)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">#{{ $booking->id }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->first_name }} {{ $booking->last_name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->email }}</td>
                    <!--<td class="px-4 py-2 whitespace-nowrap">{{ $booking->phone }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->dob }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->gender }}</td>-->
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->passport_number }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->passport_expiry }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->nationality }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->amount }} INR</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                               ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $booking->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('admin.booking.show', $booking->id) }}" 
                           class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

        </div>
    </div>
</body>
</html>