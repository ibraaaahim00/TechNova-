<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create the first administrator account for the TechNova CMS';

    public function handle(): int
    {
        $name = $this->ask('Administrator name');
        $email = $this->ask('Administrator email');

        if (blank($name) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A name and valid email address are required.');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('An account with that email already exists. No account was changed.');

            return self::FAILURE;
        }

        $password = $this->secret('Password (at least 12 characters)');
        $confirmation = $this->secret('Confirm password');
        if (mb_strlen($password) < 12 || ! hash_equals($password, $confirmation)) {
            $this->error('The password must be at least 12 characters and match its confirmation.');

            return self::FAILURE;
        }

        User::query()->create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_admin' => true]);
        $this->info('Administrator account created. Sign in at /admin/login.');

        return self::SUCCESS;
    }
}
