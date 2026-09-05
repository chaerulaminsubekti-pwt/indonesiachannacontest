<?php

namespace Tests\Feature;

use Tests\TestCase;

class InfoPagesTest extends TestCase
{
    public function test_tentang_page_returns_200(): void
    {
        $this->get(route('tentang'))->assertOk()->assertSee('Tentang ICC', false);
    }

    public function test_privasi_page_returns_200_with_adsense_clause(): void
    {
        $this->get(route('privasi'))->assertOk()->assertSee('AdSense', false);
    }

    public function test_kontak_page_returns_200(): void
    {
        $this->get(route('kontak'))->assertOk();
    }

    public function test_disclaimer_page_returns_200(): void
    {
        $this->get(route('disclaimer'))->assertOk();
    }

    public function test_footer_links_to_info_pages(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('tentang'), false);
        $response->assertSee(route('privasi'), false);
        $response->assertSee(route('kontak'), false);
        $response->assertSee(route('disclaimer'), false);
        $response->assertDontSee('Link Cepat', false);
    }
}
