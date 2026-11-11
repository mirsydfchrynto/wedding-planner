<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\WeddingMetricsService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WeddingMetricsService::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view): void {
            if (Schema::hasTable('wedding_profiles')) {
                $view->with('globalProfile', app(WeddingMetricsService::class)->getProfile());
            }
        });

        if (str_starts_with(config('app.url'), 'https://') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
