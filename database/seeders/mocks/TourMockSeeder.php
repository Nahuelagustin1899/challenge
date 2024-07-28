<?php

namespace Database\Seeders\mocks;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourMockSeeder extends Seeder
{
    /**
     * Run the database mock seeders.
     */
    public function run(): void
    {
        Tour::factory()
            ->count(20)
            ->create();
    }
}
