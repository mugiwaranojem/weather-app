<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/weather/cached',
    [WeatherController::class, 'cached']
);

Route::get(
    '/weather',
    [WeatherController::class, 'show']
);
