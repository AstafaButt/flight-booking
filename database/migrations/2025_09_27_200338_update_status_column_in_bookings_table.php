<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Change the status column to be longer
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status', 20)->change(); // Increase length to 20 characters
        });
    }

    public function down()
    {
        // Revert back to original length if needed
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status', 10)->change(); // Revert to shorter length
        });
    }
};