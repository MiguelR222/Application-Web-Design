<?php

namespace Database\Factories;

use App\Models\RoboticsKit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [ 
            'title' => fake()->sentence(3), 
            'cover_image' => fake()->imageUrl(), 
            'content' => fake()->paragraph(), 
            'robotics_kit_id' => RoboticsKit::inRandomOrder()->first()->id 
        ];
    }
}
