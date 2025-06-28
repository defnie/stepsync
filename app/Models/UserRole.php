<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    public $timestamps = false; // unless you added created_at/updated_at
    protected $fillable = ['user_id', 'role_id', 'assigned_by', 'assigned_at'];
}
