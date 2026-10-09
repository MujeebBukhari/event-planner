<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that custom 404 error view renders cleanly with branding and no sensitive stack traces.
     */
    public function test_custom_404_error_view_renders_cleanly(): void
    {
        $response = $this->get('/non-existent-page-for-testing-404');

        $response->assertStatus(404);
        $response->assertSee('Page Not Found');
        $response->assertSee('Error 404');
        $response->assertSee('Return to Homepage');
    }

    /**
     * Test that custom 403 error view exists and renders expected content.
     */
    public function test_custom_403_error_view_renders(): void
    {
        $view = $this->view('errors.403');

        $view->assertSee('403');
        $view->assertSee('Access Forbidden');
        $view->assertSee('Return to Homepage');
    }

    /**
     * Test that custom 500 error view exists and renders safe generic messaging.
     */
    public function test_custom_500_error_view_renders(): void
    {
        $view = $this->view('errors.500');

        $view->assertSee('500');
        $view->assertSee('Something Went Wrong');
        $view->assertSee('Return to Homepage');
    }

    /**
     * Test that custom 419 error view exists and renders session expiration warning.
     */
    public function test_custom_419_error_view_renders(): void
    {
        $view = $this->view('errors.419');

        $view->assertSee('419');
        $view->assertSee('Session Expired');
        $view->assertSee('Refresh Page');
    }

    /**
     * Test that custom 503 error view exists and renders maintenance messaging.
     */
    public function test_custom_503_error_view_renders(): void
    {
        $view = $this->view('errors.503');

        $view->assertSee('503');
        $view->assertSee("We'll Be Right Back", false);
        $view->assertSee('Check Again');
    }

    /**
     * Test that custom 429 error view exists and renders rate limit messaging.
     */
    public function test_custom_429_error_view_renders(): void
    {
        $view = $this->view('errors.429');

        $view->assertSee('429');
        $view->assertSee('Too Many Requests');
        $view->assertSee('Return to Homepage');
    }

    /**
     * Test that .env.example contains essential configuration keys and no leaked secrets.
     */
    public function test_env_example_has_required_keys_and_no_secrets(): void
    {
        $envExamplePath = base_path('.env.example');
        $this->assertFileExists($envExamplePath);

        $content = file_get_contents($envExamplePath);

        // Required keys
        $this->assertStringContainsString('APP_NAME=', $content);
        $this->assertStringContainsString('APP_ENV=', $content);
        $this->assertStringContainsString('APP_KEY=', $content);
        $this->assertStringContainsString('APP_DEBUG=', $content);
        $this->assertStringContainsString('APP_URL=', $content);
        $this->assertStringContainsString('DB_CONNECTION=', $content);
        $this->assertStringContainsString('SESSION_DRIVER=', $content);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=', $content);
        $this->assertStringContainsString('MAIL_MAILER=', $content);
        $this->assertStringContainsString('ADMIN_EMAIL=', $content);

        // APP_KEY should be blank in .env.example
        $this->assertMatchesRegularExpression('/^APP_KEY=\s*$/m', $content);

        // Actual active .env key should not be present in .env.example
        $activeKey = config('app.key');
        if (! empty($activeKey)) {
            $this->assertStringNotContainsString($activeKey, $content);
        }
    }

    /**
     * Test that admin:create command interactively or via options creates a valid administrator.
     */
    public function test_admin_create_artisan_command_creates_admin_user(): void
    {
        $email = 'newadmin@rkvibes.com';
        $this->assertDatabaseMissing('users', ['email' => $email]);

        $exitCode = Artisan::call('admin:create', [
            '--name' => 'Test Admin CLI',
            '--email' => $email,
            '--password' => 'SecurePass123!',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('users', [
            'name' => 'Test Admin CLI',
            'email' => $email,
            'role' => 'admin',
            'is_admin' => 1,
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test that AdminUserSeeder does not insert default password in production.
     */
    public function test_admin_user_seeder_refuses_default_password_in_production(): void
    {
        $originalEnv = app()['env'];
        app()['env'] = 'production';

        // Ensure ADMIN_PASSWORD is empty
        putenv('ADMIN_PASSWORD');
        unset($_ENV['ADMIN_PASSWORD'], $_SERVER['ADMIN_PASSWORD']);

        $testEmail = 'prod-admin-check@rkvibes.com';
        putenv("ADMIN_EMAIL={$testEmail}");

        $seeder = new AdminUserSeeder;
        $seeder->run();

        // Should not have created user because production requires ADMIN_PASSWORD
        $this->assertDatabaseMissing('users', ['email' => $testEmail]);

        // Restore environment
        app()['env'] = $originalEnv;
        putenv('ADMIN_EMAIL');
    }

    /**
     * Test that production optimization caches can be generated and cleared cleanly.
     */
    public function test_production_cache_commands_execute_cleanly(): void
    {
        $this->assertEquals(0, Artisan::call('config:cache'));
        $this->assertEquals(0, Artisan::call('config:clear'));

        $this->assertEquals(0, Artisan::call('route:cache'));
        $this->assertEquals(0, Artisan::call('route:clear'));

        $this->assertEquals(0, Artisan::call('view:cache'));
        $this->assertEquals(0, Artisan::call('view:clear'));
    }
}
