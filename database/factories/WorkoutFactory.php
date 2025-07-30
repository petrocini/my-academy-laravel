<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => now()->format('Y-m-d'),
            'notes' => $this->faker->sentence,
        ];
    }
}
