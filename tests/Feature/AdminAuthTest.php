<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\EventBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Guest cannot access /admin.
     */
    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 2. Admin can access /admin.
     */
    public function test_admin_can_access_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('System Overview');
        $response->assertSee($admin->name);
    }

    /**
     * 3. Normal authenticated user cannot access /admin.
     */
    public function test_normal_authenticated_user_cannot_access_admin(): void
    {
        $normalUser = User::factory()->create([
            'role' => 'user',
            'is_admin' => false,
        ]);

        $this->assertFalse($normalUser->isAdmin());
        $this->assertFalse($normalUser->is_admin);

        $response = $this->actingAs($normalUser)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * 3b. User with is_admin true can access admin.
     */
    public function test_user_with_is_admin_true_can_access_admin(): void
    {
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertTrue($adminUser->isAdmin());

        $response = $this->actingAs($adminUser)->get(route('admin.dashboard'));

        $response->assertOk();
    }

    /**
     * 4. Admin login works via admin.login.submit.
     */
    public function test_admin_login_works(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue($admin->is_admin);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * 5. Invalid login is rejected.
     */
    public function test_invalid_login_is_rejected(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Non-admin user cannot login through admin portal.
     */
    public function test_non_admin_user_cannot_login_through_admin_portal(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'secret123',
            'role' => 'user',
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email' => 'user@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * 6. Admin can log out.
     */
    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    /**
     * 7. Unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 8. Admin dashboard loads successfully.
     */
    public function test_admin_dashboard_loads_successfully_with_summary_counts(): void
    {
        $admin = User::factory()->admin()->create();

        EventBooking::factory()->create([
            'status' => 'pending',
        ]);
        EventBooking::factory()->create([
            'status' => 'confirmed',
        ]);
        ContactMessage::factory()->create([
            'status' => 'unread',
        ]);
        ContactMessage::factory()->create([
            'status' => 'read',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('stats', function (array $stats) {
            return $stats['totalBookings'] === 2
                && $stats['pendingBookings'] === 1
                && $stats['totalMessages'] === 2
                && $stats['unreadMessages'] === 1
                && $stats['totalServices'] === 0
                && $stats['totalGalleryItems'] === 0
                && $stats['totalTestimonials'] === 0;
        });

        $response->assertSee('Total Bookings');
        $response->assertSee('Pending Bookings');
        $response->assertSee('Total Contact Messages');
        $response->assertSee('Unread Contact Messages');
        $response->assertSee('Total Services');
        $response->assertSee('Total Gallery Items');
        $response->assertSee('Total Testimonials');
    }

    /**
     * 9. Public pages display the small login button for guests.
     */
    public function test_public_pages_display_login_button_for_guests(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('login'));
        $response->assertSee('Login');
        $response->assertSee('nav-login');
    }

    /**
     * 9b. Public pages display dashboard button for authenticated users.
     */
    public function test_public_pages_display_dashboard_button_for_authenticated_users(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee('Dashboard');
    }

    /**
     * 10. Login page displays forgot password link.
     */
    public function test_login_page_displays_forgot_password_link(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk();
        $response->assertSee(route('password.request'));
        $response->assertSee('Forgot password?');
    }

    /**
     * 11. Forgot password page is accessible.
     */
    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertSee('Forgot Password');
        $response->assertSee('Send Password Reset Link');
    }

    /**
     * 12. Reset password link can be requested.
     */
    public function test_reset_password_link_can_be_requested(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    /**
     * 13. Password can be reset with valid token.
     */
    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('old-password-123'),
        ]);

        $token = Password::createToken($user);

        $resetPageResponse = $this->get(route('password.reset', ['token' => $token, 'email' => 'admin@example.com']));
        $resetPageResponse->assertOk();
        $resetPageResponse->assertSee('Reset Password');

        $updateResponse = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'admin@example.com',
            'password' => 'new-secret-password-123',
            'password_confirmation' => 'new-secret-password-123',
        ]);

        $updateResponse->assertRedirect(route('admin.login'));
        $updateResponse->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-secret-password-123', $user->fresh()->password));
    }
}
