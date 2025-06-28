<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Choreography>
 */
class ChoreographyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'style' => fake()->randomElement(['K-Pop', 'Jazzfunk', 'Hip Hop']),
            'difficulty' => fake()->randomElement(['Beginner', 'Intermediate', 'Hard']),
            'video_url' => fake()->url(), // Replace with fixed YouTube links if needed
        ];

    }
}
