<?php

namespace App\Providers;

use App\NewsReaders\Drivers\NewsApi;
use App\NewsReaders\Drivers\TheGuardian;
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
            $config = config('news-reader.drivers.newsapi') ?? [];
            return new NewsApi($config);
        });

        $this->app->bind(TheGuardian::class, function () {
            $config = config('news-reader.drivers.theguardian') ?? [];
            return new TheGuardian($config);
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
