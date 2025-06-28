<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    protected $fillable = [
        'user_id', 'class_id', 'payment_type', 'reschedule_ticket_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class);  // if your model is named `Classes`
    }

    public function rescheduleTicket()
    {
        return $this->belongsTo(RescheduleTicket::class);
    }
}
