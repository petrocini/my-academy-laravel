<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'category' => $this->faker->randomElement(['Peito', 'Costas', 'Pernas', 'Ombro']),
        ];
    }
}
