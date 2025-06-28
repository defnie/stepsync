<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Correct one from Laravel


class Choreography extends Model
{
    use HasFactory;
    protected $fillable = ['style', 'difficulty', 'video_url'];

    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }
}
