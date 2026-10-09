<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {--name= : The name of the admin user} {--email= : The email of the admin user} {--password= : The password for the admin user}', function () {
    $name = $this->option('name') ?: $this->ask('Administrator name', 'RK Vibes Administrator');
    $email = $this->option('email') ?: $this->ask('Administrator email');

    while (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email address format.');
        $email = $this->ask('Administrator email');
    }

    $password = $this->option('password');
    if (! $password) {
        $password = $this->secret('Administrator password');
        $confirm = $this->secret('Confirm administrator password');

        if ($password !== $confirm) {
            $this->error('Passwords do not match.');

            return 1;
        }
    }

    if (strlen((string) $password) < 8) {
        $this->error('Password must be at least 8 characters.');

        return 1;
    }

    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]
    );

    $this->info("Administrator [{$user->email}] successfully configured.");

    return 0;
})->purpose('Create or update an administrator account safely');
