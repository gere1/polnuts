<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Missing translations fall back to the site's configured default locale
        // (Setting::default_locale) rather than a hardcoded one. Guarded because
        // this runs on every request/command, including before migrations exist.
        $fallback = 'ka';

        if (Schema::hasTable('settings')) {
            $fallback = Setting::current()->default_locale ?? 'ka';
        }

        Translatable::fallback(fallbackLocale: $fallback, fallbackAny: true);
    }
}
