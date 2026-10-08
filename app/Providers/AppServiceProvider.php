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
            $view->with('siteSettings', SiteSetting::query()->pluck('value', 'key')->all());
            $view->with('navigationItems', NavigationItem::query()->where('is_visible', true)->orderBy('sort_order')->get());
        });
    }
}
