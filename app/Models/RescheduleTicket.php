<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescheduleTicket extends Model
{
    protected $fillable = [
        'user_id',
        'original_booking_id',
        'expires_at',
        'used',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function originalBooking()
    {
        return $this->belongsTo(Booking::class, 'original_booking_id');
    }
}
