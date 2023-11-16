<?php

namespace App\NewsReaders\Abstracts;

use \App\NewsReaders\Contracts\Driver as DriverContract;
use Carbon\Carbon;

abstract class Driver implements DriverContract
{
    protected string $apiKey;
    protected Carbon $from;
    protected string $search;

    public function setFrom(Carbon $date): \App\NewsReaders\Contracts\Driver
    {
        dd('ok');
        $this->from = $date;
        return $this;
    }

    public function setSearchQuery(string $search): \App\NewsReaders\Contracts\Driver
    {
        $this->search = $search;
        return $this;
    }
}
