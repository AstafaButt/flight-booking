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
        .details-panel { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .details-panel.open { max-height: 500px; }
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
                    $itinerary = $flight['itineraries'][0] ?? [];
                    $segments = $itinerary['segments'] ?? [];
                    $firstSegment = $segments[0] ?? [];
                    $lastSegment = end($segments) ?: [];

                    $departureTime = \Carbon\Carbon::parse($firstSegment['departure']['at'] ?? now());
                    $arrivalTime = \Carbon\Carbon::parse($lastSegment['arrival']['at'] ?? now());

                    $stops = count($segments) - 1;
                    $durationIso = $itinerary['duration'] ?? '';
                    preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $durationIso, $matches);
                    $hours = $matches[1] ?? 0;
                    $minutes = $matches[2] ?? 0;
                    $seconds = $matches[3] ?? 0;
                    $durationMinutes = $hours * 60 + $minutes + ceil($seconds / 60);

                    $airlineCode = $firstSegment['carrierCode'] ?? 'N/A';
                    $flightNumber = $firstSegment['number'] ?? 'N/A';
                    $aircraftCode = $firstSegment['aircraft']['code'] ?? 'N/A';

                    $aircraftModels = [
                        '320'=>'Airbus A320','321'=>'Airbus A321','319'=>'Airbus A319',
                        '738'=>'Boeing 737-800','77W'=>'Boeing 777-300ER','788'=>'Boeing 787-8',
                        '789'=>'Boeing 787-9'
                    ];
                    $aircraftModel = $aircraftModels[$aircraftCode] ?? $aircraftCode;

                    $travelerPricing = $flight['travelerPricings'][0] ?? [];
                    $fareDetails = $travelerPricing['fareDetailsBySegment'][0] ?? [];
                    $baggageAllowance = ($fareDetails['includedCheckedBags']['weight'] ?? 20) . ' ' . ($fareDetails['includedCheckedBags']['weightUnit'] ?? 'kg');
                    $cabinClass = ucfirst(strtolower($fareDetails['cabin'] ?? 'ECONOMY'));

                    $airlineNames = [
                        'AI'=>'Air India','6E'=>'IndiGo','SG'=>'SpiceJet','UK'=>'Vistara','G8'=>'Go First',
                        'AK'=>'AirAsia','EY'=>'Etihad Airways','EK'=>'Emirates','QR'=>'Qatar Airways','SQ'=>'Singapore Airlines'
                    ];
                    $airlineName = $airlineNames[$airlineCode] ?? $airlineCode;

                    $itinerariesCount = count($flight['itineraries']);
                @endphp

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

                    <!-- Flight Info -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $airlineName }}</h3>
                                <p class="text-sm text-gray-600">Flight {{ $airlineCode }}{{ $flightNumber }} • {{ $aircraftModel }}</p>
                            </div>
                            <button type="button" class="details-toggle text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                <span class="mr-1">Flight details</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>

                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                            <div class="flex-1 mb-4 lg:mb-0 flex items-center gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-800">{{ $departureTime->format('H:i') }}</div>
                                    <div class="text-sm text-gray-600">{{ $departureTime->format('D, d M') }}</div>
                                    <div class="text-sm font-medium text-gray-800">{{ $firstSegment['departure']['iataCode'] ?? '' }}</div>
                                </div>
                                <div class="flex-1 text-center">
                                    <div class="text-gray-600 text-sm">
                                        {{ substr($durationIso,2) ?: 'N/A' }} • {{ $stops === 0 ? 'Non-stop' : $stops . ' stop' . ($stops>1?'s':'') }}
                                    </div>
                                    <div class="h-px bg-gray-300 my-2 relative">
                                        <i class="fas fa-plane absolute left-1/2 transform -translate-x-1/2 -top-2 text-gray-400 bg-white px-1"></i>
                                    </div>
                                    @if($stops>0)
                                    <div class="text-xs text-gray-500 mt-1">{{ $stops }} stop{{ $stops>1?'s':'' }}</div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-800">{{ $arrivalTime->format('H:i') }}</div>
                                    <div class="text-sm text-gray-600">{{ $arrivalTime->format('D, d M') }}</div>
                                    <div class="text-sm font-medium text-gray-800">{{ $lastSegment['arrival']['iataCode'] ?? '' }}</div>
                                </div>
                            </div>

                            <div class="text-center lg:text-right">
                                <div class="text-3xl font-bold text-blue-600 mb-2">
                                    {{ $flight['price']['total'] }} {{ $flight['price']['currency'] ?? 'INR' }}
                                </div>
                                <div class="text-sm text-gray-600 mb-3">
                                    {{ $cabinClass }} • {{ $baggageAllowance }} baggage
                                </div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                                    Select Flight <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Details -->
                    <div class="details-panel bg-gray-50 border-t">
                        <div class="p-6">
                            @foreach($segments as $segmentIndex => $segment)
                                @php
                                    $segDep = \Carbon\Carbon::parse($segment['departure']['at']);
                                    $segArr = \Carbon\Carbon::parse($segment['arrival']['at']);
                                    $segDur = $segDep->diffInMinutes($segArr);
                                @endphp
                                <div class="flex items-center py-3 {{ $segmentIndex>0?'border-t border-gray-200':'' }}">
                                    <div class="w-10 text-center">
                                        <i class="fas fa-plane text-gray-400"></i>
                                    </div>
                                    <div class="flex-1 grid grid-cols-12 gap-4 items-center">
                                        <div class="col-span-4">
                                            <div class="font-medium text-gray-800">{{ $segDep->format('H:i') }}</div>
                                            <div class="text-sm text-gray-600">{{ $segDep->format('d M') }}</div>
                                            <div class="text-sm font-medium">{{ $segment['departure']['iataCode'] }}</div>
                                        </div>
                                        <div class="col-span-4 text-center">
                                            <div class="text-sm text-gray-600">{{ floor($segDur/60) }}h {{ $segDur%60 }}m</div>
                                            <div class="h-px bg-gray-300 my-1"></div>
                                            <div class="text-xs text-gray-500">Flight {{ $segment['carrierCode'] }}{{ $segment['number'] }}</div>
                                        </div>
                                        <div class="col-span-4 text-right">
                                            <div class="font-medium text-gray-800">{{ $segArr->format('H:i') }}</div>
                                            <div class="text-sm text-gray-600">{{ $segArr->format('d M') }}</div>
                                            <div class="text-sm font-medium">{{ $segment['arrival']['iataCode'] }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if($segmentIndex < count($segments)-1)
                                    @php
                                        $nextSeg = $segments[$segmentIndex+1];
                                        $layoverMin = \Carbon\Carbon::parse($segment['arrival']['at'])->diffInMinutes(\Carbon\Carbon::parse($nextSeg['departure']['at']));
                                    @endphp
                                    <div class="flex items-center py-2 bg-yellow-50 rounded mx-10 mb-2">
                                        <div class="w-10 text-center">
                                            <i class="fas fa-clock text-yellow-500 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm text-yellow-700">
                                                Layover at {{ $segment['arrival']['iataCode'] }}: {{ floor($layoverMin/60) }}h {{ $layoverMin%60 }}m
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <button id="loadMoreBtn" class="bg-white hover:bg-gray-100 text-blue-600 border border-blue-600 px-6 py-3 rounded-lg font-semibold">
                Load More Flights
            </button>
        </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-700">
                No flights found for your search criteria.
            </div>
        @endif
    </div>

    <script>
        // Toggle details
        document.querySelectorAll('.details-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const panel = this.closest('.flight-card').querySelector('.details-panel');
                panel.classList.toggle('open');
            });
        });

        // Trip selection
        document.querySelectorAll('.trip-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.trip-btn').forEach(b => b.classList.remove('active-trip'));
                this.classList.add('active-trip');
            });
        });

        // Load More functionality
        let flightsShown = 5;
        const allFlights = Array.from(document.querySelectorAll('.flight-card'));
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        function showFlights() {
            allFlights.forEach((f, i) => f.style.display = (i<flightsShown ? 'block':'none'));
            if(flightsShown >= allFlights.length) loadMoreBtn.style.display='none';
        }

        loadMoreBtn.addEventListener('click', function() {
            flightsShown += 5;
            showFlights();
        });

        showFlights();
    </script>
</body>
</html>
