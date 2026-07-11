<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class FaviconHelper
{
    public static function resolve(): string
    {
        $favicon = url('favicon.ico');
        try {
            if (Schema::hasTable('site_settings')) {
                $setting = SiteSetting::first();
                if ($setting?->favicon) {
                    $favicon = Storage::url($setting->favicon);
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }
        return $favicon;
    }
}
