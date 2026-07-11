<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'penyelenggara']);

        $admin = User::create([
            'name' => 'Super Admin ICC',
            'email' => 'admin@icc.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $admin->assignRole('super_admin');
    }
}
