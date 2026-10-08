<?php

namespace App\Providers;

use App\Models\NavigationItem;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('site.*', function ($view): void {
            $settings = SiteSetting::query()->get()->mapWithKeys(fn (SiteSetting $setting): array => [$setting->key => $setting->value])->all();
            $view->with('siteSettings', $settings);
            $view->with('navigationItems', NavigationItem::query()->where('is_visible', true)->orderBy('sort_order')->get());
        });
    }
}
