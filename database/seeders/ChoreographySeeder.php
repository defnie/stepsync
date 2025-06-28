<?php

namespace Database\Seeders;

use App\Models\Choreography;
use Illuminate\Database\Seeder;

class ChoreographySeeder extends Seeder
{
    public function run(): void
    {
        $styles = ['Hip Hop', 'Jazzfunk', 'K-Pop'];
        $difficulties = ['Beginner', 'Intermediate', 'Hard'];

        foreach ($styles as $style) {
            foreach ($difficulties as $difficulty) {
                Choreography::create([
                    'style' => $style,
                    'difficulty' => $difficulty,
                ]);
            }
        }
    }
}

