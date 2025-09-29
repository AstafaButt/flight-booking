<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Available Flights - onestoptravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        .flight-card { transition: transform .25s ease, box-shadow .25s ease; }
        .flight-card:hover { transform: translateY(-6px); box-shadow: 0 14px 30px rgba(0,0,0,.08); }
        .active-trip { background-color: #2563eb !important; color: white !important; }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-plane-departure text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">onestoptravel</span>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('flights.index') }}" class="hover:text-blue-300">Home</a>
                    <a href="#" class="hover:text-blue-300">Flights</a>
                    <a href="#" class="hover:text-blue-300">My Bookings</a>
                    <a href="#" class="hover:text-blue-300">Support</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Available Flights</h1>
                <p class="text-gray-600 mt-2">
                    @if(isset($flights['data']) && count($flights['data']) > 0)
                        Found <span class="font-semibold text-blue-600">{{ count($flights['data']) }}</span> flights
                    @else
                        No flights found for your search criteria
                    @endif
                </p>
            </div>
            <a href="{{ route('flights.index') }}" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center">
                <i class="fas fa-search mr-2"></i> New Search
            </a>
        </div>
    </div>

    @if(isset($flights['data']) && count($flights['data']) > 0)
    <!-- Controls: Sort / Filters / Trip -->
    <div class="container mx-auto px-4 py-6 bg-white rounded-lg shadow-sm mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="text-gray-700 font-medium">Sort by:</span>
                <select id="sortSelect" class="border rounded px-3 py-2">
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="duration-asc">Duration: Shortest</option>
                    <option value="departure-asc">Departure: Earliest</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-gray-700 font-medium">Filters:</span>
                <button class="filter-btn px-3 py-1 rounded-full bg-gray-100" data-filter="nonstop">Non-stop</button>
                <button class="filter-btn px-3 py-1 rounded-full bg-gray-100" data-filter="morning">Morning Departure</button>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-gray-700 font-medium">Trip:</span>
                <button class="trip-btn px-3 py-1 rounded-full active-trip" data-trip="oneway">One Way</button>
                <button class="trip-btn px-3 py-1 rounded-full bg-gray-100" data-trip="round">Round Trip</button>
                <button class="trip-btn px-3 py-1 rounded-full bg-gray-100" data-trip="multi">Multi-City</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Results container -->
    <div class="container mx-auto px-4 py-8">
        @if(isset($flights['data']) && count($flights['data']) > 0)
        <div id="flightResults" class="space-y-6">
            @foreach($flights['data'] as $index => $flight)
                @php
                    // derive first/last segments and times
                     $firstSegment = $flight['itineraries'][0]['segments'][0];
    $departureTime = \Carbon\Carbon::parse($firstSegment['departure']['at']);
    $arrivalTime = \Carbon\Carbon::parse(end($flight['itineraries'][0]['segments'])['arrival']['at']);
                    $itinerarySegments = $flight['itineraries'][0]['segments'];
                    $lastSegment = end($itinerarySegments);
                   
                    $stops = count($itinerarySegments) - 1;
                    $itinerariesCount = count($flight['itineraries']);
                    // parse ISO 8601 duration PT#H#M#S -> minutes
                    $iso = $flight['itineraries'][0]['duration'] ?? '';
                    preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $iso, $m);
                    $hours = isset($m[1]) ? (int)$m[1] : 0;
                    $mins = isset($m[2]) ? (int)$m[2] : 0;
                    $secs = isset($m[3]) ? (int)$m[3] : 0;
                    $durationMinutes = $hours * 60 + $mins + (int)ceil($secs / 60);
                @endphp
                

                {{-- each flight is a real form -> keep it that way so submit works --}}
                <form action="{{ route('flights.confirm.post') }}" method="POST"
                    class="flight-item flight-card bg-white rounded-xl shadow-md overflow-hidden"
                    data-price="{{ (float)$flight['price']['total'] }}"
                    data-duration="{{ $durationMinutes }}"
                    data-departure="{{ $departureTime->getTimestamp() }}"
                    data-stops="{{ $stops }}"
                    data-itineraries="{{ $itinerariesCount }}"
                    data-currency="{{ $flight['price']['currency'] ?? 'INR' }}"
                    style="display: none;"
                >
                    @csrf
                    <input type="hidden" name="flight" value="{{ e(json_encode($flight)) }}">
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center">
                        <div class="flex-1 mb-4 lg:mb-0 flex items-center gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $departureTime->format('H:i') }}</div>
                                <div class="text-sm text-gray-600">{{ $firstSegment['departure']['iataCode'] }}</div>
                            </div>

                            <div class="flex-1 text-center">
                                <div class="text-gray-600 text-sm">
                                    {{ substr($iso, 2) ?: 'N/A' }} • {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                </div>
                                <div class="h-px bg-gray-300 my-2 relative">
                                    <i class="fas fa-plane absolute left-1/2 transform -translate-x-1/2 -top-2 text-gray-400 bg-white px-1"></i>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $arrivalTime->format('H:i') }}</div>
                                <div class="text-sm text-gray-600">{{ $lastSegment['arrival']['iataCode'] }}</div>
                            </div>
                        </div>

                        <div class="text-center lg:text-right">
                            <div class="text-3xl font-bold text-blue-600 mb-2">
                                {{ $flight['price']['total'] }} {{ $flight['price']['currency'] ?? 'INR' }}
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                                Select Flight <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>

        <!-- Load More -->
        <div class="text-center mt-8">
            <button id="loadMoreBtn" class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-refresh mr-2"></i> Load More Flights
            </button>
        </div>
        @else
            <div class="text-center py-12">
                <div class="bg-white rounded-xl shadow-sm p-8 max-w-md mx-auto">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No flights found</h3>
                    <a href="{{ route('flights.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">New Search</a>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="container mx-auto px-4 text-center text-gray-400 border-t border-gray-700 pt-8">
            <p>&copy; 2025 onestoptravel. All rights reserved.</p>
        </div>
    </footer>

    <script>
    (function () {
        // DOM helpers
        const container = document.getElementById('flightResults');
        if (!container) return;

        const allNodes = Array.from(container.querySelectorAll('.flight-item'));

        // build structured array with parsed attributes
        const flights = allNodes.map(node => {
            const price = parseFloat(node.dataset.price || '0');
            const duration = parseInt(node.dataset.duration || '0', 10);
            const departure = parseInt(node.dataset.departure || '0', 10) * 1000; // timestamp -> ms
            const stops = parseInt(node.dataset.stops || '0', 10);
            const itineraries = parseInt(node.dataset.itineraries || '1', 10);
            return { node, price, duration, departure, stops, itineraries };
        });

        // UI state
        let visibleCount = 5;
        let activeFilters = { nonstop: false, morning: false };
        let activeTrip = 'oneway'; // default
        let sortKey = 'price-asc';
        let visibleList = flights.slice(); // filtered & sorted list

        // helper to compute departure hour
        function departureHourOf(f) {
            return new Date(f.departure).getHours();
        }

        // apply filters & sort -> update visibleList
        function applyFiltersAndSort() {
            let list = flights.slice();

            // Trip filter
            list = list.filter(f => {
                if (activeTrip === 'oneway') return f.itineraries === 1;
                if (activeTrip === 'round') return f.itineraries === 2;
                if (activeTrip === 'multi') return f.itineraries > 2;
                return true;
            });

            // Non-stop filter
            if (activeFilters.nonstop) {
                list = list.filter(f => f.stops === 0);
            }

            // Morning filter (let's define morning as 5:00 - 11:59)
            if (activeFilters.morning) {
                list = list.filter(f => {
                    const h = departureHourOf(f);
                    return h >= 5 && h < 12;
                });
            }

            // Sorting
            switch (sortKey) {
                case 'price-asc':
                    list.sort((a,b) => a.price - b.price);
                    break;
                case 'price-desc':
                    list.sort((a,b) => b.price - a.price);
                    break;
                case 'duration-asc':
                    list.sort((a,b) => a.duration - b.duration);
                    break;
                case 'departure-asc':
                    list.sort((a,b) => a.departure - b.departure);
                    break;
            }

            visibleList = list;
            visibleCount = Math.min(5, visibleList.length); // reset to first page
            render();
        }

        // render visibleList first visibleCount items (move real nodes into container)
        function render() {
    // clear container
    container.innerHTML = '';

    if (visibleList.length === 0) {
        // show "no flights" message
        container.innerHTML = `
            <div class="text-center py-12 w-full">
                <div class="bg-white rounded-xl shadow-sm p-8 max-w-md mx-auto">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No flights found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or search parameters.</p>
                </div>
            </div>
        `;
    } else {
        // show filtered flights
        visibleList.forEach((f, idx) => {
            if (idx < visibleCount) {
                f.node.style.display = '';
                container.appendChild(f.node);
            } else {
                f.node.style.display = 'none';
                container.appendChild(f.node);
            }
        });
    }

    // toggle load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        if (visibleCount >= visibleList.length || visibleList.length === 0) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.style.display = '';
        }
    }
}


        // event bindings
        document.getElementById('sortSelect')?.addEventListener('change', (e) => {
            sortKey = e.target.value;
            applyFiltersAndSort();
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.filter;
                activeFilters[key] = !activeFilters[key];
                btn.classList.toggle('bg-blue-600');
                btn.classList.toggle('text-white');
                applyFiltersAndSort();
            });
        });

        document.querySelectorAll('.trip-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.trip-btn').forEach(b => {
                    b.classList.remove('active-trip');
                    b.classList.remove('bg-gray-100');
                });
                btn.classList.add('active-trip');
                activeTrip = btn.dataset.trip;
                applyFiltersAndSort();
            });
        });

        document.getElementById('loadMoreBtn')?.addEventListener('click', () => {
            visibleCount += 5;
            render();
        });

        // initial setup: ensure container has nodes in original order (just in case)
        // We call applyFiltersAndSort to build and render initial view (first page)
        applyFiltersAndSort();

    })();
    </script>
</body>
</html>
