<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\department;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Facades\Route;

class AuthenticationTest extends TestCase
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

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    #[DataProvider('userTypesProvider')]
    public function test_users_can_authenticate_and_redirect_based_on_role(string $usertype, string $expectedRoute): void
    {
        $user = User::factory()->create(['usertype' => $usertype]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            // 'password' => $user->password,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route($expectedRoute));
    }

    public static function userTypesProvider(): array
    {
        return [
            ['admin', 'admin.home'],
            ['dept_head', 'dept_head.home'],
            ['user', 'user.scanQR'],
        ];
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
