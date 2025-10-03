<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Models\Booking;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', fn() => view('welcome'))->name('home');

// --------------------
// Public Flights
// --------------------
Route::prefix('flights')->name('flights.')->group(function () {
    Route::get('/', [FlightController::class, 'index'])->name('index');
    Route::get('/search', [FlightController::class, 'search'])->name('search');

    // Flight confirmation
    Route::get('/confirm', [FlightController::class, 'showConfirm'])->name('confirm.get');
    Route::post('/confirm', [FlightController::class, 'confirm'])->name('confirm.post');

    // Book flight and redirect to payment
    Route::post('/book', [BookingController::class, 'store'])->name('book');
});

// --------------------
// Payment Routes
// --------------------
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/{booking}', [PaymentController::class, 'show'])->name('page');
    Route::post('/create-intent', [PaymentController::class, 'createIntent'])->name('create');
    Route::post('/success', [PaymentController::class, 'handleSuccess'])->name('success');
    Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook');
});

// --------------------
// Booking Completion
// --------------------
Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::post('/{id}/complete', [BookingController::class, 'complete'])->name('complete');
});

// Booking success (optional)
Route::get('/booking/success/{booking}', function ($bookingId) {
    $booking = Booking::findOrFail($bookingId);
    return view('booking.success', compact('booking'));
})->name('booking.success');

// --------------------
// Admin Routes
// --------------------
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Login
    Route::post('/login', function (Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'admin' && $password === 'secret') {
            $request->session()->put('is_admin', true);
            $request->session()->put('admin_username', $username);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home')
            ->with('error', 'Invalid username or password!')
            ->with('show_admin_modal', true);
    })->name('login');

    // Admin Logoutss
    Route::post('/logout', function (Request $request) {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');

    // Protected Admin Routes
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $stats = [
                'total_bookings'     => Booking::count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
                'pending_bookings'   => Booking::where('status', 'pending')->count(),
                'revenue'            => Booking::where('status', 'confirmed')->sum('amount'),
            ];

            $recentBookings = Booking::latest()->take(10)->get();

            return view('admin.dashboard', compact('stats', 'recentBookings'));
        })->name('dashboard');

        // Booking Management
        Route::get('/bookings', function () {
            $bookings = Booking::latest()->get();
            return view('admin.bookings', compact('bookings'));
        })->name('bookings');

        Route::get('/bookings/{booking}', function (Booking $booking) {
            return view('admin.booking-details', compact('booking'));
        })->name('booking.show');

        Route::post('/bookings/{booking}/confirm', function (Booking $booking) {
            $booking->update(['status' => 'confirmed']);
            return back()->with('success', 'Booking confirmed successfully!');
        })->name('booking.confirm');

        Route::post('/bookings/{booking}/cancel', function (Booking $booking) {
            $booking->update(['status' => 'cancelled']);
            return back()->with('success', 'Booking cancelled successfully!');
        })->name('booking.cancel');

        Route::post('/bookings/{booking}/refund', function (Booking $booking) {
            try {
                if ($booking->status !== 'confirmed') {
                    return back()->with('error', 'Only confirmed bookings can be refunded.');
                }

                if (!$booking->stripe_payment_intent_id) {
                    return back()->with('error', 'No payment information found for refund.');
                }

                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                $refund = \Stripe\Refund::create([
                    'payment_intent' => $booking->stripe_payment_intent_id,
                    'amount' => $booking->amount * 100,
                ]);

                $booking->update([
                    'status'           => 'refunded',
                    'refunded_at'      => now(),
                    'stripe_refund_id' => $refund->id,
                ]);

                \Log::info('Refund processed', [
                    'booking_id' => $booking->id,
                    'refund_id'  => $refund->id,
                    'amount'     => $booking->amount,
                ]);

                return back()->with('success', 'Refund processed successfully! Refund ID: '.$refund->id);

            } catch (\Exception $e) {
                \Log::error('Refund error: '.$e->getMessage());
                return back()->with('error', 'Refund failed: '.$e->getMessage());
            }
        })->name('booking.refund');
    });
});
