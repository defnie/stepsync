<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

Event::listen(
    Registered::class,
    function ($event) {
        $user = $event->user;
        $studentRole = Role::where('name', 'student')->first();

        if ($studentRole) {
            $user->roles()->attach($studentRole->id); // Assign student role by default
        }
    }
);
