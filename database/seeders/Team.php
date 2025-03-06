<?php

namespace Database\Seeders;

use Database\Factories\TeamFactory;
use Illuminate\Database\Seeder;

class Team extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamFactory::new([
            'name'                => 'Super Team 1',
            'home_power'          => 100,
            'away_power'          => 90,
            'supporter_strength'  => 44,
            'goalkeeper_strength' => 88,
            'attacker_strength'   => 99,
            'defence_strength'    => 97,
        ]);
    }
}
