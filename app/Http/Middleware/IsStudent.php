<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsStudent
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !$request->user()->hasRole('student')) {
            abort(403, 'Unauthorized. Student role required.');
        }

        return $next($request);
    }
}
