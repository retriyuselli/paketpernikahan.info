<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Super Admin',
                'email'             => 'admin@paketpernikahan.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Editor',
                'email'             => 'editor@paketpernikahan.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Demo User',
                'email'             => 'demo@paketpernikahan.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }

        // Additional random users
        User::factory(10)->create();

        $this->command->info('UserSeeder: ' . (count($users) + 10) . ' users seeded.');
    }
}
