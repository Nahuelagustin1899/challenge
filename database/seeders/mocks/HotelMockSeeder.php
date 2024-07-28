<?php

namespace Database\Seeders\mocks;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelMockSeeder extends Seeder
{
    /**
     * Run the database mock seeders.
     */
    public function run(): void
    {
        Hotel::factory()
            ->count(20)
            ->create();
    }
}
