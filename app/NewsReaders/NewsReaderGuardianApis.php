<?php

namespace App\NewsReaders;

use Illuminate\Support\Facades\Http;

class NewsReaderGuardianApis
{
    public static function read()
    {
        $a = 'https://content.guardianapis.com/search?q=sport&api-key=34772b72-8d3f-479b-9a42-8841a483840e&show-fields=all&show-elements=all&show-references=author';

        $response = Http::get($a);

        return $response;
    }

    public static function single()
    {
        $url = 'https://content.guardianapis.com/australia-news/live/2023/nov/16/australia-politics-live-infrastructure-review-catherine-king-rail-road-apec-anthony-albanese-peter-dutton-richard-marles-question-time?api-key=34772b72-8d3f-479b-9a42-8841a483840e&show-references=all';

        $response = Http::get($url);

        return $response;
    }
}
