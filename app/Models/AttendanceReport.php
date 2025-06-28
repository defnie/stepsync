<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClassModel;
use App\Models\User;


class AttendanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'instructor_id',
        'user_id',
        'attended', // ✅ new field to allow mass assignment
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
