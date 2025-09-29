<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('stripe_refund_id')->nullable()->after('stripe_payment_intent_id');
            $table->timestamp('refunded_at')->nullable()->after('paid_at');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['stripe_refund_id', 'refunded_at']);
        });
    }
};