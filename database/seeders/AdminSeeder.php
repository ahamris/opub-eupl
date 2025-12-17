<?php

namespace Database\Seeders;

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Variable::DEFAULT_ACCOUNTS as [$name, $lastName, $email, $password, $role]) {
            $user = User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}

