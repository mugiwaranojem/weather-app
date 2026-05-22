<?php

return [
    'base_url' => env(
        'OPENWEATHER_BASE_URL',
        'https://api.openweathermap.org/data/2.5'
    ),

    'api_key' => env('OPENWEATHER_API_KEY'),
];