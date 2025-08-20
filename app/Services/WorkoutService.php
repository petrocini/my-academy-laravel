<?php

namespace App\Services;

use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkoutService
{
    public function store(array $data, int $userId): Workout
    {
        $data['date'] = Carbon::parse($data['date']);
        return DB::transaction(function () use ($data, $userId) {
            $workout = Workout::create([
                'user_id' => $userId,
                'exercise_id' => $data['exercise_id'],
                'date' => $data['date'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['sets'] as $set) {
                $workout->sets()->create([
                    'weight' => $set['weight'],
                    'repetitions' => $set['repetitions'],
                ]);
            }

            return $workout->load('sets');
        });
    }

    public function update(array $data, int $userId, int $id): Workout
    {
        return DB::transaction(function () use ($data, $userId, $id) {
            $workout = Workout::where('user_id', $userId)->findOrFail($id);

            $workout->update([
                'exercise_id' => $data['exercise_id'] ?? $workout->exercise_id,
                'date' => $data['date'] ?? $workout->date,
                'notes' => $data['notes'] ?? $workout->notes,
            ]);

            if (isset($data['sets'])) {
                $workout->sets()->delete();

                foreach ($data['sets'] as $set) {
                    $workout->sets()->create([
                        'weight' => $set['weight'],
                        'repetitions' => $set['repetitions'],
                    ]);
                }
            }

            return $workout->load('sets');
        });
    }
}
