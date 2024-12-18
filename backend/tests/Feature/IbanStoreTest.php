<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IbanStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the user can successfully save their IBAN.
     *
     * @return void
     */
    public function test_save_iban()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the created user
        Sanctum::actingAs($user);

        $iban = 'AL35202111090000000001234567';

        // Make the API request with valid IBAN
        $response = $this->postJson('/api/save-iban', [
            'iban' => $iban 
        ]);

        // Assert HTTP status
        $response->assertStatus(201);

        // Assert the response structure matches the ApiResponse success format
        $response->assertJsonStructure([
            'status',
            'message'
        ]);

        // Assert response data
        $response->assertJson([
            'status' => 'success',
            'message' => 'IBAN saved successfully.',
        ]);

        // Retrieve the user from the database
        $user = $user->fresh();

        // Decrypt the stored IBAN and clean it
        $decryptedIban = Crypt::decryptString($user->iban);

        // Assert that the decrypted IBAN matches the cleaned IBAN
        $this->assertEquals($iban, $decryptedIban);
    }

    /**
     * Test the validation of an invalid IBAN.
     *
     * @return void
     */
    public function test_invalid_iban()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the created user
        Sanctum::actingAs($user);

        // Make the API request with an invalid IBAN
        $response = $this->postJson('/api/save-iban', [
            'iban' => 'INVALID_IBAN' // Invalid IBAN format
        ]);

        // Assert HTTP status
        $response->assertStatus(422);

        // Assert the response structure matches the ApiResponse error format
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    /**
     * Test that an unauthenticated user cannot save an IBAN.
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_save_iban()
    {
        // Make the API request without authentication
        $response = $this->postJson('/api/save-iban', [
            'iban' => 'AL35202111090000000001234567' // Valid IBAN
        ]);

        // Assert that the response status is 401 (Unauthorized)
        $response->assertStatus(401);
    }
}
