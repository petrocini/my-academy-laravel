<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_exercise()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Supino reto',
            'category' => 'Peito'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/exercises', $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Supino reto']);

        $this->assertDatabaseHas('exercises', ['name' => 'Supino reto']);
    }

    public function test_user_can_list_exercises()
    {
        $user = User::factory()->create();

        Exercise::factory()->create(['name' => 'Agachamento']);
        Exercise::factory()->create(['name' => 'Remada curvada']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/exercises');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_user_can_view_single_exercise()
    {
        $user = User::factory()->create();

        $exercise = Exercise::factory()->create(['name' => 'Puxada frente']);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/exercises/{$exercise->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Puxada frente']);
    }

    public function test_user_can_update_exercise()
    {
        $user = User::factory()->create();

        $exercise = Exercise::factory()->create(['name' => 'Leg press']);

        $payload = [
            'name' => 'Leg press 45',
            'category' => 'Pernas'
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/exercises/{$exercise->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Leg press 45']);

        $this->assertDatabaseHas('exercises', ['name' => 'Leg press 45']);
    }

    public function test_user_can_delete_exercise()
    {
        $user = User::factory()->create();

        $exercise = Exercise::factory()->create(['name' => 'Crossover']);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/exercises/{$exercise->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Exercício removido com sucesso.']);

        $this->assertDatabaseMissing('exercises', ['id' => $exercise->id]);
    }

    public function test_guest_cannot_access_exercises()
    {
        $exercise = Exercise::factory()->create();

        $this->getJson('/api/exercises')->assertStatus(401);
        $this->postJson('/api/exercises', ['name' => 'Teste'])->assertStatus(401);
        $this->getJson("/api/exercises/{$exercise->id}")->assertStatus(401);
        $this->putJson("/api/exercises/{$exercise->id}", ['name' => 'Update'])->assertStatus(401);
        $this->deleteJson("/api/exercises/{$exercise->id}")->assertStatus(401);
    }
}
