<?php

namespace App\NewsReaders\Contracts;

use App\Models\Category;
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
     * @param srting $category
     *
     * @return self
     */
    public function setCategory(Category $category): self;

    public function pullNewsToDb(): void;

}
