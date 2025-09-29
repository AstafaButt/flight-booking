<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation Bar (same as before) -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <!-- ... your nav code ... -->
    </nav>

    <!-- Booking Progress -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-3xl mx-auto">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-center">
                        <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium text-green-600">Search</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4">
                        <div class="progress-bar h-1 w-full"></div>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium text-green-600">Confirm</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4">
                        <div class="progress-bar h-1 w-2/3"></div>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">3</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Details</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4"></div>
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="font-bold">4</span>
                        </div>
                        <span class="text-sm font-medium text-gray-500">Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Passenger Details</h1>
                <p class="text-gray-600">Enter the passenger information for this booking</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <form action="{{ route('flights.save-passenger-details') }}" method="POST">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-user mr-2 text-blue-500"></i> Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                <input type="text" name="first_name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                <input type="text" name="last_name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                <input type="tel" name="phone" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Passport Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-passport mr-2 text-blue-500"></i> Passport Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Passport Number *</label>
                                <input type="text" name="passport_number" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                <input type="date" name="passport_expiry" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                                <select name="nationality" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="IN">India</option>
                                    <option value="US">United States</option>
                                    <option value="GB">United Kingdom</option>
                                    <!-- Add more countries -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-blue-500"></i> Emergency Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8">
                        <a href="{{ route('flights.confirm') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Flight Details
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                            Continue to Payment <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>