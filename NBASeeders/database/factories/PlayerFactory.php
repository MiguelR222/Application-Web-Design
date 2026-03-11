<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => $this->faker->firstName(),
            'last_name'    => $this->faker->lastName(),
            'number'       => $this->faker->numberBetween(0, 99),
            'salary'       => $this->faker->randomFloat(2, 500000, 50000000),
            'years_played' => $this->faker->numberBetween(0, 20),
            'team_id'      => Team::factory(),
        ];
    }
}
