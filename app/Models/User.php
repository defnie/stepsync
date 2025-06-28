<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Correct one from Laravel
use App\Models\Booking;

class User extends Authenticatable
{
    protected $fillable = [
    'name',
    'email',
    'profile_picture', 
    'password',
];

    use HasFactory;//"hey this model can  use factories , dude!"
    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class, 'instructor_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole($roleName): bool
    {
        return $this->roles->contains(function ($role) use ($roleName) {
            return strtolower($role->name) === strtolower($roleName);
        });
    }
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }


    //for logins;)
  public function isAdmin(): bool
{
    return $this->hasRole('admin');
}

public function isInstructor(): bool
{
    return $this->hasRole('instructor');
}

public function isStudent(): bool
{
    return $this->hasRole('student');
}



}
