<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test : new user registration.
     * @return void
     */
    public function test_user_can_register()
    {
        // Send POST request to /api/register with valid data
        $response = $this->postJson('/api/register', [
            'name'                  => 'Mamunur Rashid',
            'email'                 => 'mamunurrashid1010@gmail.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        // Assert response status and structure
        $response->assertCreated()->assertJson(['message' => 'User registered successfully.']);

        // Assert that the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => 'mamunurrashid1010@gmail.com',
        ]);
    }

    /**
     * Test : user login and receive an access token.
     * @return void
     */
    public function test_user_can_login_and_receive_token()
    {
        // Create a test user
        $user = User::factory()->create([
            'email'    => 'mamunurrashid1010@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Send POST request to /api/login with correct credentials
        $response = $this->postJson('/api/login', [
            'email'    => 'mamunurrashid1010@gmail.com',
            'password' => 'password',
        ]);

        // Assert successful login and token response
        $response->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user',
            ]);
    }

    /**
     * Test : authenticated user can access their profile.
     * @return void
     */
    public function test_authenticated_user_can_access_profile()
    {
        // Create a test user and token
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        // Send GET request with Bearer token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user');

        // Assert response contains user details
        $response->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Test : authenticated user can logout and invalidate their token.
     * @return void
     */
    public function test_authenticated_user_can_logout()
    {
        // Create a test user and token
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        // Send POST request to /api/logout with Bearer token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        // Assert logout was successful
        $response->assertOk()
            ->assertJson([
                'message' => 'User logged out successfully.',
            ]);
    }


}
