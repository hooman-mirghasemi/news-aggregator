<?php

namespace App\NewsReaders;

use App\NewsReaders\Drivers\NewsApi;
use Illuminate\Support\Manager;

class NewsReaderManager extends Manager
{

    /**
     * Create an instance of the newsapi driver.
     *
     * @return NewsApi
     */
    protected function createNewsApiDriver(): NewsApi
    {
        return $this->container->make(NewsApi::class);
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('news-reader.driver');
    }
}
