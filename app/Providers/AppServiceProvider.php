<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Observers\SiteSettingObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        SiteSetting::observe(SiteSettingObserver::class);

        View::composer('*', function ($view) {
            if (Schema::hasTable('site_settings')) {
                $view->with('settings', SiteSetting::first() ?? new SiteSetting);
            }
        });
    }
}
