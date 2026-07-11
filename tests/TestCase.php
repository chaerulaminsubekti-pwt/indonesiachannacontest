<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
    }

    protected function createAdmin(): User
    {
        $user = User::where('email', 'admin@icc.com')->first();
        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Super Admin ICC',
                'email' => 'admin@icc.com',
                'username' => 'admin',
                'role' => 'super_admin',
                'status' => 'active',
            ]);
            $user->assignRole('super_admin');
        }

        return $user;
    }

    protected function createPenyelenggara(array $overrides = []): User
    {
        $user = User::factory()->create(array_merge([
            'name' => 'Penyelenggara',
            'email' => 'penyelenggara@test.com',
            'username' => 'penyelenggara',
            'role' => 'penyelenggara',
            'status' => 'active',
        ], $overrides));

        $user->assignRole('penyelenggara');

        return $user;
    }
}
