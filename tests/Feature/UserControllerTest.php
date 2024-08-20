<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_user()
    {
        // Data for the new user
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'role' => 'user',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Send a POST request to the register route
        $response = $this->postJson('/api/register', $userData);

        // Assert that the response is 201 Created and contains the expected data
        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);

        // Assert that the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'role' => 'user',
        ]);
    }

    /** @test */
    public function it_cannot_register_a_user_with_invalid_data()
    {
        // Data for the new user with an invalid email
        $userData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'role' => 'user',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Send a POST request to the register route
        $response = $this->postJson('/api/register', $userData);

        // Assert that the response is 400 Bad Request
        $response->assertStatus(400)
                 ->assertJsonStructure(['email']);
    }

    /** @test */
    public function it_can_login_a_user_with_correct_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
        ]);

        // Data for login
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];

        // Send a POST request to the login route
        $response = $this->postJson('/api/login', $loginData);

        // Assert that the response is 200 OK and contains the expected data
        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'token']);
    }

    /** @test */
    public function it_cannot_login_a_user_with_invalid_credentials()
    {
        // Data for login with incorrect password
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ];

        // Send a POST request to the login route
        $response = $this->postJson('/api/login', $loginData);

        // Assert that the response is 401 Unauthorized
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials',
                 ]);
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Send a POST request to the logout route
        $response = $this->postJson('/api/logout');

        // Assert that the response is 200 OK and contains the expected message
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logged out']);
    }
}
