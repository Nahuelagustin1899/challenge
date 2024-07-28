<?php

namespace Database\Seeders;

use Database\Seeders\mocks\BookingMockSeeder;
use Database\Seeders\mocks\HotelMockSeeder;
use Database\Seeders\mocks\TourMockSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            TourMockSeeder::class,
            HotelMockSeeder::class,
            BookingMockSeeder::class,
        ]);
    }
}
