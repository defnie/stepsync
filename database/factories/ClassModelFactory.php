<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Choreography;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
{
    protected $model = ClassModel::class;

    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase(),
            'location' => fake()->address(),
            'instructor_id' => User::factory(),
            'choreography_id' => Choreography::factory(),
            'date' => fake()->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'max_slot' => fake()->numberBetween(10, 20),
        ];
    }
}
