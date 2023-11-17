<?php

namespace App\Schedule;

use App\Models\Category;
use App\NewsReaders\NewsReaderManager;

class FetchNews
{
    public function __invoke(): void
    {
        $drivers = array_keys(config('news-reader.drivers'));
        $categories = Category::query()->get();
        $newsReader = resolve(NewsReaderManager::class);

        foreach ($drivers as $driver) {
            foreach ($categories as $category) {
                $newsReader
                    ->driver($driver)
                    ->setFrom(now()->subDays(2))
                    ->setCategory($category)
                    ->pullNewsToDb();
            }
        }
    }
}
