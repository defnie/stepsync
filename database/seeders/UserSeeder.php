<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch roles by name for clarity
        $adminRole = Role::where('name', 'admin')->first();
        $instructorRole = Role::where('name', 'instructor')->first();
        $studentRole = Role::where('name', 'student')->first();

        if (!$adminRole || !$instructorRole || !$studentRole) {
        throw new \Exception('Required roles not found. Did you run the RoleSeeder?');
    }
        // Create users and assign roles
        $u1 = User::create([
            'name' => 'Instructor One',
            'email' => 'instructor1@example.com',
            'password' => Hash::make('password'),
        ]);
        $u1->roles()->attach($instructorRole);

        $u2 = User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
        ]);
        $u2->roles()->attach($adminRole);

        $u3 = User::create([
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
        ]);
        $u3->roles()->attach($studentRole);

        // Multi-role user example:
        $u4 = User::create([
            'name' => 'Arif',
            'email' => 'arif.dance123@example.com',
            'password' => Hash::make('password'),
        ]);
        $u4->roles()->attach([$adminRole->id, $instructorRole->id]);

        $u5 = User::create([
            'name' => 'Defnie',
            'email' => 'defnie@example.com',
            'password' => Hash::make('password'),
        ]);
        $u5->roles()->attach([$studentRole->id,$adminRole->id, $instructorRole->id]);

        $u6 = User::create([
            'name' => 'Vincent',
            'email' => 'vincent@example.com',
            'password' => Hash::make('password'),
        ]);
        $u6->roles()->attach([$instructorRole->id,$studentRole->id]);
    }
}
