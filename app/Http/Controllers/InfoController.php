<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class InfoController extends Controller
{
    public function tentang()
    {
        return view('info.tentang', ['settings' => $this->settings()]);
    }

    public function privasi()
    {
        return view('info.privasi');
    }

    public function kontak()
    {
        return view('info.kontak', ['settings' => $this->settings()]);
    }

    public function disclaimer()
    {
        return view('info.disclaimer');
    }

    private function settings(): ?SiteSetting
    {
        return Schema::hasTable('site_settings') ? SiteSetting::first() : null;
    }
}
