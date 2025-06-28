<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingRequest;
use App\Models\User;
use App\Models\ClassModel;

class BookingRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::inRandomOrder()->take(3)->get(); // Grab 3 random users
        $classes = ClassModel::inRandomOrder()->take(2)->get(); // Grab 2 random classes

        foreach ($users as $user) {
            foreach ($classes as $class) {
                BookingRequest::create([
                    'user_id' => $user->id,
                    'class_id' => $class->id,
                    'payment_type' => 'Transfer',
                    'status' => 'Pending',
                ]);
            }
        }
    }
}
