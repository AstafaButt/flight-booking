<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>onestoptravel - Book Your Perfect Flight</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .landing-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
        }
        .choice-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .choice-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="landing-bg">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Logo and Heading -->
            <div class="mb-12">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <i class="fas fa-plane-departure text-4xl text-white"></i>
                    <span class="text-4xl font-bold text-white">onestoptravel</span>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">Welcome to Your Travel Journey</h1>
                <p class="text-xl text-blue-100">Where would you like to go today?</p>
            </div>

            <!-- Choice Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                <!-- Passenger Card -->
                <div class="choice-card bg-white rounded-2xl p-8 cursor-pointer" onclick="selectPassenger()">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">I'm a Passenger</h3>
                    <p class="text-gray-600 mb-6">Book flights, manage your trips, and explore amazing destinations</p>
                    <div class="bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold transition duration-300 hover:bg-blue-700">
                        Book a Flight <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>

                <!-- Admin Card -->
                <div class="choice-card bg-white rounded-2xl p-8 cursor-pointer" onclick="selectAdmin()">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">I'm an Admin</h3>
                    <p class="text-gray-600 mb-6">Manage bookings, view reports, and oversee operations</p>
                    <div class="bg-green-600 text-white py-3 px-6 rounded-lg font-semibold transition duration-300 hover:bg-green-700">
                        Admin Login <i class="fas fa-lock ml-2"></i>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-blue-200">
                <p>© 2025 onestoptravel. All rights reserved. | Secure & Reliable Flight Booking</p>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal (Hidden by default) -->
    <div id="adminModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <i class="fas fa-shield-alt text-4xl text-green-600 mb-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
                <p class="text-gray-600">Enter your credentials to access the dashboard</p>
            </div>
@if(session('error'))
    <div style="color: red; margin-bottom: 10px;">
        {{ session('error') }}
    </div>
@endif

            <form id="adminLoginForm" method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login to Dashboard
                    </button>
                </div>
            </form>

            <button onclick="closeAdminModal()" class="w-full mt-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-times mr-2"></i> Cancel
            </button>
        </div>
    </div>

    <script>
        function selectPassenger() {
            window.location.href = "{{ route('flights.index') }}";
        }

        function selectAdmin() {
            document.getElementById('adminModal').classList.remove('hidden');
        }

        function closeAdminModal() {
            document.getElementById('adminModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('adminModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAdminModal();
            }
        });

        // Handle form submission
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // You can add loading state here
            this.submit();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAdminModal();
            }
        });

          @if(session('show_admin_modal'))
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('adminModal').classList.remove('hidden');
        });
    @endif
    </script>
</body>
</html>