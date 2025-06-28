<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsInstructor
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !$request->user()->hasRole('instructor')) {
            abort(403, 'Unauthorized. Instructor role required.');
        }

        return $next($request);
    }
}
