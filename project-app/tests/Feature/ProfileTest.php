<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        // Test for Admin User
        $adminUser = User::factory()->create(['usertype' => 'admin']);
        $response = $this
            ->actingAs($adminUser)
            ->get('/admin/profile');

        // $response->assertSee('<x-icons.dash-icon />', false);
        // Debugging: check the content of the response
        // dd($response->getContent());

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        // Create an admin user for the test
        $adminUser = User::factory()->create(['usertype' => 'admin']);

        // Define the data to be updated
        $updateData = [
            'location' => '123 Main St', // This corresponds to the address field in the database
            'contact' => '01231234567',   // Ensure this matches your validation rules
            'birthdate' => '2000-01-01',
            'gender' => 'male',            // Ensure this matches your validation rules
        ];

        // Update the user's profile
        $response = $this
            ->actingAs($adminUser)
            ->patch('/admin/profile_update', $updateData);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        // Refresh the user to get the updated values
        $adminUser->refresh();

        // Assert the values have been updated
        $this->assertSame('123 Main St', $adminUser->address);
        $this->assertSame('01231234567', $adminUser->contact);
        $this->assertSame('2000-01-01', $adminUser->birthdate);
        $this->assertSame('male', $adminUser->gender);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        // Update to the correct route based on the user type
        $route = match ($user->usertype) {
            'admin' => '/admin/profile_update',
            'dept_head' => '/dept_head/profile_update',
            'user' => '/user/profile_update',
        };

        $response = $this
            ->actingAs($user)
            ->patch($route, [
                'firstname' => 'Test User',
                'lastname' => 'User',           // Include last name
                'email' => $user->email,        // Unchanged email
                'contact' => '01231234567',     // Ensure this matches your validation rules
                'birthdate' => '2000-01-01',    // Include birthdate
                'location' => '123 Main St',     // Include address
                'gender' => 'male',              // Include gender
            ]);

        // Ensure the expected redirect matches the route after updating
        $expectedRedirect = match ($user->usertype) {
            'admin' => '/admin/profile',
            'dept_head' => '/dept_head/profile',
            'user' => '/user/profile',
        };

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect($expectedRedirect); // Use the expected redirect based on user type

        // Check if the email verification status is unchanged
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
