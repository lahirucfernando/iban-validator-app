<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    private $userName = 'David Miller';
    private $email = 'devid@example.com';
    private $password = 'password@123';

    /**
     * Create user
     *
     * @return User
     */
    private function createUser()
    {
        return User::factory()->create([
            'name' => $this->userName,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
    }

    /**
     * Test successful login.
     *
     * @return void
     */
    public function test_user_login_with_valid_credentials()
    {
        // Arrange: Create a user
        $this->createUser();

        // Act: Attempt to login
        $response = $this->postJson('/api/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // Assert: Check response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'uuid',
                        'name',
                        'email',
                    ],
                ],
            ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * Test login failure with incorrect password
     *
     * @return void
     */
    public function test_login_fails_with_invalid_credentials()
    {
        // Arrange: Create a user
        $this->createUser();

        // Act: Attempt to login with invalid credentials
        $response = $this->postJson('/api/login', [
            'email' => $this->email,
            'password' => 'wrongpassword',
        ]);

        // Assert: Check response
        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Test login with missing password.
     *
     * @return void
     */
    public function test_login_validation_error()
    {
        // Act: Attempt to login without password
        $response = $this->postJson('/api/login', [
            'email' => $this->email
        ]);

        // Assert the validation error
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
