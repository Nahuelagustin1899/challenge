<?php

namespace Database\Seeders\mocks;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingMockSeeder extends Seeder
{
    /**
     * Run the database mock seeders.
     */
    public function run(): void
    {
        Booking::factory()
            ->count(20)
            ->create();
    }
}
