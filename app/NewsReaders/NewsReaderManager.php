<?php

namespace App\NewsReaders;

use App\NewsReaders\Drivers\NewsApi;
use App\NewsReaders\Drivers\TheGuardian;
use App\NewsReaders\Drivers\WorldNews;
use Illuminate\Support\Manager;

class NewsReaderManager extends Manager
{

    /**
     * Create an instance of the NewsApi driver.
     *
     * @return NewsApi
     */
    protected function createNewsApiDriver(): NewsApi
    {
        return $this->container->make(NewsApi::class);
    }

    /**
     * Create an instance of the TheGuardian driver.
     *
     * @return TheGuardian
     */
    protected function createTheGuardianDriver(): TheGuardian
    {
        return $this->container->make(TheGuardian::class);
    }

    /**
     * Create an instance of the TheGuardian driver.
     *
     * @return TheGuardian
     */
    protected function createWorldNewsDriver(): WorldNews
    {
        return $this->container->make(WorldNews::class);
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('news-reader.driver');
    }
}
