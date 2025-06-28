<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleChangeNotification extends Model
{
    protected $fillable = ['class_id', 'old_time', 'new_time', 'sent_at'];

    public function class() {
        return $this->belongsTo(ClassModel::class);
    }
}

