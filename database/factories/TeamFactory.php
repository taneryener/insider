<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                => $this->faker->company(),
            'home_power'          => $this->faker->numberBetween(0, 100),
            'away_power'          => $this->faker->numberBetween(0, 100),
            'supporter_strength'  => $this->faker->numberBetween(0, 100),
            'goalkeeper_strength' => $this->faker->numberBetween(0, 100),
            'attacker_strength'   => $this->faker->numberBetween(0, 100),
            'defence_strength'    => $this->faker->numberBetween(0, 100),
        ];
    }
}
