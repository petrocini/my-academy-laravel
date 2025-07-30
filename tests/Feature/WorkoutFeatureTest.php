<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkoutFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_workout()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $payload = [
            'date' => now()->format('Y-m-d'),
            'notes' => 'Teste de treino',
            'sets' => [
                [
                    'exercise_id' => $exercise->id,
                    'weight' => 70,
                    'repetitions' => 10,
                    'order' => 1
                ]
            ]
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/workouts', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'workout' => [
                    'id',
                    'date',
                    'notes',
                    'sets' => [
                        [
                            'id',
                            'exercise' => ['id', 'name', 'category'],
                            'weight',
                            'repetitions',
                            'order'
                        ]
                    ]
                ]
            ]);
    }

    public function test_user_can_list_workouts()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $workout = Workout::factory()->for($user)->create();
        $workout->sets()->create([
            'exercise_id' => $exercise->id,
            'weight' => 50,
            'repetitions' => 12,
            'order' => 1
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/workouts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'date', 'notes', 'sets']
            ]);
    }

    public function test_user_can_view_a_specific_workout()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $workout = Workout::factory()->for($user)->create();
        $workout->sets()->create([
            'exercise_id' => $exercise->id,
            'weight' => 40,
            'repetitions' => 8,
            'order' => 1
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/workouts/{$workout->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $workout->id]);
    }

    public function test_user_can_update_a_workout()
    {
        $user = User::factory()->create();
        $exercise = Exercise::factory()->create();

        $workout = Workout::factory()->for($user)->create();

        $payload = [
            'date' => now()->format('Y-m-d'),
            'notes' => 'Atualizado',
            'sets' => [
                [
                    'exercise_id' => $exercise->id,
                    'weight' => 80,
                    'repetitions' => 15,
                    'order' => 1
                ]
            ]
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/workouts/{$workout->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['notes' => 'Atualizado']);
    }

    public function test_user_can_delete_a_workout()
    {
        $user = User::factory()->create();
        $workout = Workout::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/workouts/{$workout->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Treino removido com sucesso.']);
    }

    public function test_user_cannot_access_another_users_workout()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $workout = Workout::factory()->for($other)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/workouts/{$workout->id}");

        $response->assertStatus(404);
    }
}
