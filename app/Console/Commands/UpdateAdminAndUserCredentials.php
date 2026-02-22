<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateAdminAndUserCredentials extends Command
{
    protected $signature = 'accounts:update-credentials';

    protected $description = 'Generate new passwords for the first two users and output their credentials';

    public function handle(): int
    {
        $users = User::orderBy('id')->take(2)->get();

        if ($users->count() < 2) {
            $this->error('At least two users are required in the system.');
            return 1;
        }

        $credentials = [];

        foreach ($users as $index => $user) {
            $password = Str::random(16);
            $user->update(['password' => $password]);
            $credentials[] = [
                'user' => $index + 1,
                'email' => $user->email,
                'password' => $password,
            ];
        }

        $this->newLine();
        $this->info('--- New credentials (save these) ---');
        foreach ($credentials as $c) {
            $this->line(sprintf('User %d: %s / %s', $c['user'], $c['email'], $c['password']));
        }
        $this->info('--- End credentials ---');

        return 0;
    }
}
