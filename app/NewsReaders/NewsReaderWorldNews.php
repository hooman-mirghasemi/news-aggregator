<?php

namespace App\NewsReaders;

use Illuminate\Support\Facades\Http;

class NewsReaderWorldNews
{
    public static function read()
    {
        $a = 'https://api.worldnewsapi.com/search-news?api-key=37c9bc6608e647e6810c5d0d096b5929&text=sport';

        $response = Http::get($a);

        return $response;
    }
}
