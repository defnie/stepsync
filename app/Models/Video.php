<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'uploader_id',
        'video_url',
        'upload_date',
    ];

    public $timestamps = false; // Because we're using upload_date manually

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
