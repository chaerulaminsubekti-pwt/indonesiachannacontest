<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_guest_can_see_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_authenticated_user_cannot_see_login_page(): void
    {
        $user = $this->createPenyelenggara();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createPenyelenggara([
            'email' => 'penyelenggara@icc.test',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'penyelenggara@icc.test',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/panel');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $this->createPenyelenggara([
            'email' => 'penyelenggara@icc.test',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'penyelenggara@icc.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->createPenyelenggara([
            'email' => 'inactive@icc.test',
            'password' => bcrypt('secret123'),
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@icc.test',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_super_admin_redirects_to_admin_panel(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }

    public function test_penyelenggara_redirects_to_panel(): void
    {
        $user = $this->createPenyelenggara([
            'email' => 'pic@icc.test',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'pic@icc.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/panel');
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createPenyelenggara();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
