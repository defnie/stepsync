<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceReport;



class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes'; // "Class" is a reserved word in PHP

    protected $fillable = [
        'title',
        'location',
        'instructor_id',
        'choreography_id',
        'date',
        'start_time',
        'end_time',
        'max_slot',
        'video_url',
        'status',
        'doc_name1', 'doc_link1',
        'doc_name2', 'doc_link2',
        'doc_name3', 'doc_link3',
    ];

    

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function choreography()
    {
        return $this->belongsTo(Choreography::class, 'choreography_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'class_id');
    }

    public function attendanceReport()
    {
        return $this->hasOne(AttendanceReport::class, 'class_id');
    }
    public function attendanceReports()
    {
        return $this->hasMany(AttendanceReport::class, 'class_id');
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'class_id');
    }
    



}
