<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    // Passenger info
    $table->string('first_name');
    $table->string('last_name');
    $table->string('email');
    $table->string('phone')->nullable();
    $table->date('dob')->nullable();
    $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
    $table->string('passport_number')->nullable();
    $table->date('passport_expiry')->nullable();
    $table->string('nationality', 2)->nullable();

    // Flight info
    $table->string('amadeus_order_id')->nullable();
    $table->json('flight_offer')->nullable();

    // Payment info
    $table->string('stripe_payment_intent')->nullable();
    $table->enum('status', ['pending', 'authorized', 'captured', 'failed', 'refunded', 'ticketed', 'order_failed'])->default('pending');
    $table->decimal('amount', 10, 2)->default(0);

    // Unique booking token
    $table->string('booking_token')->unique()->nullable();

    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
