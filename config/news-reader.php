<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following gateway to use.
    | You can switch to a different driver at runtime.
    |
    */
    'driver'            => env('NEWS_READER_DRIVER', 'newsapi'),

    /*
    |--------------------------------------------------------------------------
    | List of Drivers
    |--------------------------------------------------------------------------
    |
    | These are the list of drivers to use for reading news.
    |
    */
    'drivers' => [
        'newsapi' => [
            'apikey' => env('NEWSAPI_API_KEY'),
        ],
        'theguardian' => [
            'apikey' => env('THEGUARDIAN_API_KEY'),
        ],

    ]
];
