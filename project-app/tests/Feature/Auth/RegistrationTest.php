<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

use App\Models\department;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $department;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a department to avoid foreign key constraint violation
        // Use factory to create a department
        $this->department = department::factory()->create([
            'name' => 'IT',
            'description' => 'IT Department',
        ]);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'firstname'=> 'TEST',
            'lastname'=> 'USER',
            'email' => 'test@example.com',
            'dept_id' => $this->department->id, // Example department ID, adjust according to your database
            'usertype' => 'user', // Specify the user type
            'password' => 'password',
            'password_confirmation' => 'password',
            'contact' => '01231241',
            'birthdate' => '2000-01-01',
            'address' => '123 Main St',
        ]);

        $this->assertAuthenticated(); // This checks if the user is authenticated
        $response->assertRedirect(route('user.scanQR', absolute: false)); // Check for redirection based on user type
    }
}
