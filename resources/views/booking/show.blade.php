@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Booking Confirmation</h2>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title">
                {{ $booking->first_name }} {{ $booking->last_name }}
            </h5>
            <p>
                <strong>Email:</strong> {{ $booking->email }} <br>
                <strong>Phone:</strong> {{ $booking->phone ?? 'N/A' }}
            </p>

            <hr>

            @php
                $flight = json_decode($booking->flight_offer, true);
            @endphp

            @if($flight)
                <h5>Flight Details</h5>
                <p>
                    <strong>From:</strong> {{ $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] }} <br>
                    <strong>To:</strong> {{ $flight['itineraries'][0]['segments'][0]['arrival']['iataCode'] }} <br>
                    <strong>Airline:</strong> {{ $flight['validatingAirlineCodes'][0] ?? 'N/A' }} <br>
                    <strong>Price:</strong> {{ $booking->amount }} INR
                </p>
            @endif

            <hr>

            <h5>Payment Status</h5>
            <p>
                <span class="badge 
                    @if($booking->status === 'captured') bg-success 
                    @elseif($booking->status === 'authorized') bg-warning 
                    @elseif($booking->status === 'failed') bg-danger 
                    @else bg-secondary 
                    @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </p>

            @if($booking->status === 'captured' || $booking->status === 'ticketed')
                <a href="{{ url('/storage/invoices/invoice_'.$booking->id.'.pdf') }}" 
                   class="btn btn-primary" target="_blank">
                   Download Invoice
                </a>
            @endif
        </div>
    </div>

    <a href="/dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
</div>
@endsection
