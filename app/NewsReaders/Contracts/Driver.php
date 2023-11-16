<?php

namespace App\NewsReaders\Contracts;

use Carbon\Carbon;

interface Driver
{
    /**
     * Add filter from date
     *
     * @param Carbon $date
     *
     * @return self
     */
    public function setFrom(Carbon $date): self;

    /**
     * Add filter search text
     *
     * @param srting $search
     *
     * @return self
     */
    public function setSearchQuery(string $search): self;

    public function dedicateAuthor(): string;
    public function dedicateSource(): string;

}
