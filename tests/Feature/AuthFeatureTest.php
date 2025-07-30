<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Lucas',
            'email' => 'lucas@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', ['email' => 'lucas@example.com']);
    }

    public function test_user_cannot_register_with_unconfirmed_password()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Lucas',
            'email' => 'lucas@example.com',
            'password' => '123456',
            'password_confirmation' => '1234567'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'lucas@example.com',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'lucas@example.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'lucas@example.com',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'lucas@example.com',
            'password' => 'senhaerrada',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_access_me_endpoint()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $user->email]);
    }

    public function test_unauthenticated_user_cannot_access_me_endpoint()
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }
}
