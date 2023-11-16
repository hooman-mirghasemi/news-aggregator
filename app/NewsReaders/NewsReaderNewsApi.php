<?php

namespace App\NewsReaders;

use Illuminate\Support\Facades\Http;

class NewsReaderNewsApi
{
    public static function read()
    {
        $a = 'https://newsapi.org/v2/everything?q=health&apiKey=e267b599b173479f9d4a24a1130008cd';

        $response = Http::get($a);

        return $response;
    }
}
