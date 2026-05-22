<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\WeatherData;
use App\Exceptions\WeatherServiceException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class WeatherService
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
    ) {}

    public function getWeather(string $city): WeatherData
    {
        $response = Http::timeout(10)
            ->get("{$this->baseUrl}/weather", [
                'q' => $city,
                'appid' => $this->apiKey,
            ]);

        if ($response->status() === 404) {
            throw WeatherServiceException::cityNotFound($city);
        }

        if ($response->status() === 401) {
            throw WeatherServiceException::invalidApiKey();
        }

        if ($response->failed()) {
            throw WeatherServiceException::apiUnavailable();
        }

        return WeatherData::fromApiResponse(
            $response->json()
        );
    }

    public function getCachedWeather(string $city): WeatherData
    {
        $cacheKey = "weather:{$city}";

        $data = Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($city) {
                return $this->getWeather($city)->toArray();
            }
        );

        return WeatherData::fromCached($data);
    }
}