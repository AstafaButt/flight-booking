<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>onestoptravel - Find Your Perfect Flight</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .search-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.85);
        }
        .quote-carousel {
            transition: opacity 0.5s ease-in-out;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">onestoptravel</span>
                </div>
                
                <div class="hidden md:flex space-x-6">
                    <a href="#" class="hover:text-blue-300 transition duration-300">Home</a>
                    <a href="#" class="hover:text-blue-300 transition duration-300">Flights</a>
                    <a href="#" class="hover:text-blue-300 transition duration-300">Hotels</a>
                    <a href="#" class="hover:text-blue-300 transition duration-300">Deals</a>
                    <a href="#" class="hover:text-blue-300 transition duration-300">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                 <!--   <button class="bg-blue-700 hover:bg-blue-600 px-4 py-2 rounded transition duration-300">
                        <i class="fas fa-user mr-2"></i>Sign In
                    </button>
                    <button class="md:hidden text-xl">
                        <i class="fas fa-bars"></i>
                    </button> -->
                </div> 
            </div>
        </div>
    </nav>
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


    <!-- Hero Section with Search Form -->
    <section class="hero-bg py-16 md:py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Find Your Perfect Flight</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">Search and compare flights from hundreds of airlines to get the best deals</p>
            </div>
            
            <!-- Travel Quote Carousel -->
            <div class="max-w-3xl mx-auto mb-10 text-center">
                <div id="quoteCarousel" class="quote-carousel">
                    <p class="text-white text-lg italic">"The world is a book, and those who do not travel read only one page." - Saint Augustine</p>
                </div>
            </div>
            
            <!-- Search Form -->
            <div class="max-w-4xl mx-auto">
                <div class="search-card rounded-xl shadow-2xl p-6 md:p-8">
                    <form action="{{ route('flights.search') }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-6">
    <div>
        <label for="origin" class="block text-gray-700 font-medium mb-2">From</label>
        <div class="relative">
            <i class="fas fa-plane-departure absolute left-3 top-3 text-blue-500"></i>
            <input type="text" name="origin" id="origin" placeholder="Origin (e.g. DEL)" 
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
        </div>
    </div>
    
    <div>
        <label for="destination" class="block text-gray-700 font-medium mb-2">To</label>
        <div class="relative">
            <i class="fas fa-plane-arrival absolute left-3 top-3 text-blue-500"></i>
            <input type="text" name="destination" id="destination" placeholder="Destination (e.g. BOM)" 
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
        </div>
    </div>
    
    <div>
        <label for="departure_date" class="block text-gray-700 font-medium mb-2">Departure Date</label>
        <div class="relative">
            <i class="fas fa-calendar-day absolute left-3 top-3 text-blue-500"></i>
            <input type="date" name="departure_date" id="departure_date" 
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
        </div>
    </div>
    
    <div>
        <label for="return_date" class="block text-gray-700 font-medium mb-2">Return Date (optional)</label>
        <div class="relative">
            <i class="fas fa-calendar-check absolute left-3 top-3 text-blue-500"></i>
            <input type="date" name="return_date" id="return_date" 
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>
    
    <div class="md:col-span-2">
        <label for="adults" class="block text-gray-700 font-medium mb-2">Adults</label>
        <input type="number" name="adults" id="adults" min="1" max="9" value="1"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    
    <div class="md:col-span-2">
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
            <i class="fas fa-search mr-2"></i>Search Flights
        </button>
    </div>
</form>

                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Why Book With Us?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tag text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Best Price Guarantee</h3>
                    <p class="text-gray-600">We guarantee the best prices for your flights. Found a cheaper option? We'll match it!</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Secure Booking</h3>
                    <p class="text-gray-600">Your personal and payment information is protected with our secure booking system.</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Our customer support team is available around the clock to assist you with any queries.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">onestoptravel</h3>
                    <p class="text-gray-400">Your trusted partner for flight bookings. We make travel simple and affordable.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Flights</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Hotels</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Deals</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">FAQs</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-blue-400"></i> +1 (555) 123-4567
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-blue-400"></i> support@onestoptravel.com
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i> 123 Travel St, City, Country
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 onestoptravel. All rights reserved.</p>
            </div>
        </div>
    </footer>
    

    <script>
        // Travel quotes for the carousel
        const quotes = [
            '"The world is a book, and those who do not travel read only one page." - Saint Augustine',
            '"Travel is the only thing you buy that makes you richer." - Anonymous',
            '"Adventure is worthwhile." - Aesop',
            '"Life is either a daring adventure or nothing at all." - Helen Keller',
            '"Travel makes one modest. You see what a tiny place you occupy in the world." - Gustave Flaubert',
            '"To travel is to live." - Hans Christian Andersen'
        ];
        
        let currentQuoteIndex = 0;
        const quoteElement = document.getElementById('quoteCarousel');
        
        // Change quote every 5 seconds
        setInterval(() => {
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
            quoteElement.style.opacity = 0;
            
            setTimeout(() => {
                quoteElement.innerHTML = `<p class="text-white text-lg italic">${quotes[currentQuoteIndex]}</p>`;
                quoteElement.style.opacity = 1;
            }, 500);
        }, 5000);
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);
    </script>
</body>
</html>