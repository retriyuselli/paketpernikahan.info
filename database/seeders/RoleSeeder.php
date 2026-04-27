<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles jika belum ada
        $roles = ['super_admin', 'admin', 'vendor', 'author', 'pengunjung'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Assign role ke user berdasarkan email
        $assignments = [
            'admin@paketpernikahan.com'  => 'super_admin',
            'editor@paketpernikahan.com' => 'admin',
            'demo@paketpernikahan.com'   => 'pengunjung',
        ];

        foreach ($assignments as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$role]);
            }
        }

        $this->command->info('RoleSeeder: ' . count($roles) . ' roles created, ' . count($assignments) . ' users assigned.');
    }
}
