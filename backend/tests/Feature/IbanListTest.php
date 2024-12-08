<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IbanListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up initial data, create roles and users.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create roles (if they don't exist)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create users
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password@123'),
            'iban' => 'AL35202111090000000001234567',
        ]);
        $adminUser->roles()->attach($adminRole);

        $regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password@123'),
            'iban' => 'AO06004400006729503010102',
        ]);
        $regularUser->roles()->attach($userRole);
    }

    /**
     * Test admin user can access IBAN number list.
     *
     * @return void
     */
    public function test_admin_user_can_access_iban_list()
    {
        // Authenticate as the admin user
        $adminUser = User::where('email', 'admin@example.com')->first();
        Sanctum::actingAs($adminUser);

        // Send GET request to fetch IBAN list
        $response = $this->getJson('/api/iban-number-list');

        // Assert that the response is successful and contains the expected IBAN data
        $response->assertStatus(200);

        // Assuming there are 2 IBANs
        $response->assertJsonCount(2);
    }

    /**
     * Test regular user can not access IBAN number list.
     *
     * @return void
     */
    public function test_regular_user_cannot_access_iban_list()
    {
        // Authenticate as the regular user
        $regularUser = User::where('email', 'user@example.com')->first();
        Sanctum::actingAs($regularUser);

        // Send GET request to fetch IBAN list
        $response = $this->getJson('/api/iban-number-list');

        // Assert that the response returns forbidden status
        $response->assertStatus(403);
    }
}
