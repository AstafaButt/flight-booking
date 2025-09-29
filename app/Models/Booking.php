<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_REFUNDED => 'Refunded',
        ];
    }

    protected $fillable = [
        // Passenger info
        'first_name', 'last_name', 'email', 'phone',
        'dob', 'gender', 'passport_number', 'passport_expiry', 'nationality',

        // Flight info
        'flight_offer', 'amount', 'status', 'amadeus_order_id',

        // Payment info
        'stripe_payment_intent_id', 'stripe_refund_id',
        'paid_at', 'refunded_at',
    ];

    protected $casts = [
        'flight_offer' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];
}
