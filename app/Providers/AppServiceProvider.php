<?php

namespace App\Providers;

use App\NewsReaders\Drivers\NewsApi;
use App\NewsReaders\Drivers\TheGuardian;
use App\NewsReaders\Drivers\WorldNews;
use App\NewsReaders\NewsReaderManager;
use App\Schedule\FetchNews;
use Illuminate\Console\Scheduling\Schedule;
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

        $this->app->bind(WorldNews::class, function () {
            $config = config('news-reader.drivers.worldnews') ?? [];
            return new WorldNews($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = app(Schedule::class);
            $schedule->call(new FetchNews())
                ->dailyAt('02:00')
                ->description(FetchNews::class);
        });
    }
}
