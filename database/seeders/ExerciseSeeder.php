<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            ['name' => 'Supino reto', 'category' => 'peito'],
            ['name' => 'Supino inclinado', 'category' => 'peito'],
            ['name' => 'Crucifixo', 'category' => 'peito'],

            ['name' => 'Puxada frente no pulley', 'category' => 'costas'],
            ['name' => 'Remada baixa', 'category' => 'costas'],
            ['name' => 'Levantamento terra', 'category' => 'costas'],

            ['name' => 'Agachamento livre', 'category' => 'perna'],
            ['name' => 'Leg press', 'category' => 'perna'],
            ['name' => 'Cadeira extensora', 'category' => 'perna'],
            ['name' => 'Mesa flexora', 'category' => 'perna'],

            ['name' => 'Desenvolvimento com halteres', 'category' => 'ombro'],
            ['name' => 'Elevação lateral', 'category' => 'ombro'],

            ['name' => 'Rosca direta', 'category' => 'bíceps'],
            ['name' => 'Rosca alternada', 'category' => 'bíceps'],

            ['name' => 'Tríceps testa', 'category' => 'tríceps'],
            ['name' => 'Tríceps pulley', 'category' => 'tríceps'],

            ['name' => 'Abdominal crunch', 'category' => 'abdômen'],
            ['name' => 'Prancha', 'category' => 'abdômen'],
        ];

        foreach ($exercises as $exercise) {
            Exercise::firstOrCreate($exercise);
        }
    }
}
