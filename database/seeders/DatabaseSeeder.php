<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        ChoreographySeeder::class,
        ClassSeeder::class,
        BookingSeeder::class,
        BookingRequestSeeder::class,
        RescheduleTicketSeeder::class,

    ]);
}


}
