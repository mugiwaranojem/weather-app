<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\WeatherService;
use Illuminate\Support\ServiceProvider;

final class WeatherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            WeatherService::class,
            fn () => new WeatherService(
                apiKey: config('weather.api_key'),
                baseUrl: config('weather.base_url'),
            )
        );
    }
}