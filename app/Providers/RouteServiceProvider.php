<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    // public const HOME = '/dashboard'; // ← this is what we override

    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
    // public static function redirectBasedOnRole($user)
    // {
    //     return match (strtolower($user->role->name ?? '')) {
    //         'admin' => '/admin/dashboard',
    //         'instructor' => '/instructor/dashboard',
    //         'student' => '/student/dashboard',
    //         default => '/',
    //     };
    // }
public static function redirectBasedOnRole($user)
{
    if ($user->hasRole('admin')) {
        return '/admin/dashboard';
    } elseif ($user->hasRole('instructor')) {
        return '/instructor/dashboard';
    } elseif ($user->hasRole('student')) {
        return '/student/dashboard';
    }

    return '/'; // default fallback
}




}
