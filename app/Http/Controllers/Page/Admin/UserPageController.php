<?php

namespace App\Http\Controllers\Page\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class UserPageController extends Controller
{
     public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('dashboards.admin.manage_users', compact('users', 'roles'));
    }
}
