<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nbaCities = [
            'Atlanta', 'Boston', 'Brooklyn', 'Charlotte', 'Chicago',
            'Cleveland', 'Dallas', 'Denver', 'Detroit', 'Golden State',
            'Houston', 'Indiana', 'Los Angeles', 'Memphis', 'Miami',
            'Milwaukee', 'Minnesota', 'New Orleans', 'New York', 'Oklahoma City',
            'Orlando', 'Philadelphia', 'Phoenix', 'Portland', 'Sacramento',
            'San Antonio', 'Toronto', 'Utah', 'Washington',
        ];

        return [
            'city' => $this->faker->unique()->randomElement($nbaCities),
        ];
    }
}
