<?php

namespace App\Services;

use App\Models\Workout;
use Illuminate\Support\Facades\DB;

class WorkoutService
{
    public function store(array $data, int $userId): Workout
    {
        return DB::transaction(function () use ($data, $userId) {
            $workout = Workout::create([
                'user_id' => $userId,
                'date' => $data['date'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['sets'] as $set) {
                $workout->sets()->create([
                    'exercise_id' => $set['exercise_id'],
                    'weight' => $set['weight'] ?? null,
                    'repetitions' => $set['repetitions'] ?? null,
                    'order' => $set['order'] ?? 1,
                ]);
            }

            return $workout;
        });
    }

    public function update(array $data, int $userId, int $workoutId)
    {
        return DB::transaction(function () use ($data, $userId, $workoutId) {
            $workout = Workout::where('id', $workoutId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $workout->update([
                'date' => $data['date'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Remove os sets antigos
            $workout->sets()->delete();

            // Adiciona os novos
            foreach ($data['sets'] as $set) {
                $workout->sets()->create([
                    'exercise_id' => $set['exercise_id'],
                    'weight' => $set['weight'] ?? null,
                    'repetitions' => $set['repetitions'] ?? null,
                    'order' => $set['order'] ?? 1,
                ]);
            }

            return $workout->load('sets.exercise');
        });
    }
}
