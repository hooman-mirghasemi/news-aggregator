<?php

namespace App\Providers;

use App\NewsReaders\Drivers\NewsApi;
use App\NewsReaders\NewsReaderManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('NewsReaderManager', NewsReaderManager::class);

        $this->app->bind(NewsApi::class, function () {
            $config = config('sms.drivers.newsapi') ?? [];
            return new NewsApi($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
