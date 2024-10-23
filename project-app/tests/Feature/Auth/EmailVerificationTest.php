<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create(['usertype' => 'admin']);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // Conditional logic to determine the expected route based on the usertype
        $expectedRoute = match ($user->usertype) {
            'admin' => route('admin.home'),
            'dept_head' => route('dept_head.home'),
            'user' => route('user.scanQR'),
            default => route('login'), // Fallback in case of an unexpected usertype
        };

        $response->assertRedirect($expectedRoute . '?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        // Create an unverified user
        $user = User::factory()->unverified()->create();

        // Generate an invalid verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')] // Invalid hash
        );

        // Act as the user and hit the verification URL
        $this->actingAs($user)->get($verificationUrl);

        // Assert that the user has not verified their email
        $this->assertFalse($user->fresh()->hasVerifiedEmail(), 'The user should not be marked as verified with an invalid hash.');
    }
}
