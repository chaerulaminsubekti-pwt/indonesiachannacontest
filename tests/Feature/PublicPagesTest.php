<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_event_index_page_returns_200(): void
    {
        $response = $this->get('/event');

        $response->assertStatus(200);
    }

    public function test_pengajuan_page_returns_200(): void
    {
        $response = $this->get('/pengajuan');

        $response->assertStatus(200);
    }

    public function test_struktur_organisasi_page_returns_200(): void
    {
        $response = $this->get('/struktur-organisasi');

        $response->assertStatus(200);
    }

    public function test_daftar_juri_page_returns_200(): void
    {
        $response = $this->get('/daftar-juri');

        $response->assertStatus(200);
    }

    public function test_regulasi_page_returns_200(): void
    {
        $response = $this->get('/regulasi');

        $response->assertStatus(200);
    }

    public function test_login_page_returns_200(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
