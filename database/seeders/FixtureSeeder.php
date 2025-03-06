<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class FixtureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::factory()->count(4)->create();
    }
}
