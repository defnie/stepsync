<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'booking_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function rescheduleTickets()
    {
        return $this->hasMany(RescheduleTicket::class, 'original_booking_id');
    }
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
    // App\Models\Booking.php



}

