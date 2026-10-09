<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL') ?: config('mail.admin_email', 'admin@rkvibes.com');
        $password = env('ADMIN_PASSWORD');

        if (empty($password)) {
            if (app()->isProduction()) {
                $this->command?->error('ADMIN_PASSWORD must be configured in production before running AdminUserSeeder.');

                return;
            }

            // Fallback for local development environment if no ADMIN_PASSWORD is set in .env
            $password = 'Admin@RK2026!Secure';
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'RK Vibes Administrator'),
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("Admin user seeded successfully for [{$email}].");
    }
}
