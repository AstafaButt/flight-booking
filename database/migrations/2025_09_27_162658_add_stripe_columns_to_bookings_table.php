<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('stripe_payment_intent_id');
            $table->text('stripe_metadata')->nullable()->after('paid_at');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['stripe_payment_intent_id', 'paid_at', 'stripe_metadata']);
        });
    }
};