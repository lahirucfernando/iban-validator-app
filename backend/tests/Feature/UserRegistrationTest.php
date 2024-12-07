<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user registration.
     *
     * @return void
     */
    public function test_user_registration_pass_with_valid_data()
    {
        $response = $this->postJson('/api/user-register', [
            'name' => 'David Miller',
            'email' => 'devid@example.com',
            'password' => 'password@123',
            'password_confirmation' => 'password@123',
        ]);

        // Assert HTTP status
        $response->assertStatus(201);

        // Assert the response structure matches the ApiResponse success format
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => [
                    'uuid',
                    'name',
                    'email',
                ]
            ],
        ]);

        // Assert response data
        $response->assertJson([
            'status' => 'success',
            'message' => 'User created successfully',
        ]);

        // Assert user exists in the database
        $this->assertDatabaseHas('users', ['email' => 'devid@example.com']);
    }

    /**
     * Test user registration with invalid data.
     *
     * @return void
     */
    public function test_user_registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/user-register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'mismatch-password',
        ]);

        // Assert HTTP status
        $response->assertStatus(422);

        // Assert the response structure matches the ApiResponse error format
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);

        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
