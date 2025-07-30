<?php

namespace App\Services;

use App\Models\Exercise;

class ExerciseService
{
    public function store(array $data): Exercise
    {
        return Exercise::create($data);
    }

    public function update(Exercise $exercise, array $data): Exercise
    {
        $exercise->update($data);
        return $exercise;
    }

    public function destroy(Exercise $exercise): void
    {
        $exercise->delete();
    }
}
