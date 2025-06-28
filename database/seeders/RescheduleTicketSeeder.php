<?php

namespace Database\Seeders;

use App\Models\RescheduleTicket;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RescheduleTicketSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users and bookings to link
        $users = User::inRandomOrder()->take(3)->get();
        $bookings = Booking::inRandomOrder()->take(3)->get();

        foreach ($users as $user) {
            foreach ($bookings as $booking) {
                RescheduleTicket::create([
                    'user_id' => $user->id,
                    'original_booking_id' => $booking->id,
                    'expires_at' => Carbon::now()->addDays(rand(-5, 10)),  // Some expired, some valid
                    'used' => rand(0, 1),
                ]);
            }
        }
    }
}
