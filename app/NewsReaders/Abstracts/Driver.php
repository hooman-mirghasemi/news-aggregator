<?php

namespace App\NewsReaders\Abstracts;

use App\Models\Category;
use \App\NewsReaders\Contracts\Driver as DriverContract;
use Carbon\Carbon;

abstract class Driver implements DriverContract
{
    protected string $apiKey;
    protected Carbon $from;
    protected Category $category;

    public function setFrom(Carbon $date): \App\NewsReaders\Contracts\Driver
    {
        $this->from = $date;
        return $this;
    }

    public function setCategory(Category $category): \App\NewsReaders\Contracts\Driver
    {
        $this->category = $category;
        return $this;
    }
}
