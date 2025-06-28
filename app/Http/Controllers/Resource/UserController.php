<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
     public function updateRoles(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Sync user roles
        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->back()->with('success', 'User roles updated successfully!');
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->roles()->detach(); // detach roles
        $user->delete();

        return redirect()->back()->with('success', 'User deleted.');
    }
}
