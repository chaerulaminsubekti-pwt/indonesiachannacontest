<?php

namespace App\Observers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingObserver
{
    public function saved(SiteSetting $siteSetting): void
    {
        foreach (['favicon'] as $field) {
            if ($siteSetting->{$field}) {
                $storagePath = $siteSetting->{$field};
                $fullPath = Storage::disk('public')->path($storagePath);

                if (! file_exists($fullPath)) {
                    continue;
                }

                $publicPath = public_path('favicon.ico');

                if (file_exists($publicPath)) {
                    unlink($publicPath);
                }

                copy($fullPath, $publicPath);
            }
        }
    }
}
