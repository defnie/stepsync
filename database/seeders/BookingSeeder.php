<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;


class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'user_id' => 1,
            'class_id' => 1,
            'status' => 'Attended',
        ]);

        Booking::create([
            'user_id' => 2,
            'class_id' => 1,
            'status' => 'Attended',
        ]);

        Booking::create([
            'user_id' => 3,
            'class_id' => 1,
            'status' => 'Absent',
        ]);

        Booking::create([
            'user_id' => 1,
            'class_id' => 2,
            'status' => 'Attended',
        ]);

        Booking::create([
            'user_id' => 2,
            'class_id' => 2,
            'status' => 'Cancelled',
        ]);

        Booking::create([
            'user_id' => 3,
            'class_id' => 2,
            'status' => 'Absent',
        ]);

        Booking::create([
            'user_id' => 4,
            'class_id' => 2,
            'status' => 'Absent',
        ]);

        Booking::create([
            'user_id' => 5,
            'class_id' => 2,
            'status' => 'Attended',
        ]);

        Booking::create([
            'user_id' => 4,
            'class_id' => 3,
            'status' => 'Attended',
        ]);

        Booking::create([
            'user_id' => 5,
            'class_id' => 3,
            'status' => 'Attended',
        ]);
    }
}
