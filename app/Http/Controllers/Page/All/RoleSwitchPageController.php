<?php

namespace App\Http\Controllers\Page\All;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RoleSwitchPageController extends Controller
{
    public function switchRole(string $role): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole($role)) {
            abort(403);
        }

        session(['active_role' => $role]);

        return redirect()->route($role . '.dashboard');
    }

    public function switch(Request $request)
    {
        $user = auth()->user();
        $newRole = $request->input('role');

        if (!$user->hasRole($newRole)) {
            abort(403, 'Unauthorized role switch.');
        }

        session(['active_role' => $newRole]);

        return redirect()->route($newRole . '.dashboard');
    }
}


