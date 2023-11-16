<?php

namespace App\NewsReaders\Drivers;

use App\NewsReaders\Abstracts\Driver;

class NewsApi extends Driver
{

    public function __construct(protected array $settings)
    {
        dd('here');
        $this->apiKey = data_get($this->settings, 'apikey');
    }
//    public static function read()
//    {
//        $a = 'https://newsapi.org/v2/everything?q=health&apiKey=e267b599b173479f9d4a24a1130008cd';
//
//        $response = Http::get($a);
//
//        return $response;
//    }

    public function dedicateAuthor(): string
    {
        return 'ali';
    }

    public function dedicateSource(): string
    {
        return 'bbd';
    }
}
