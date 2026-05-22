<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\WeatherData;
use App\Exceptions\WeatherServiceException;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class WeatherServiceTest extends TestCase
{
    private WeatherService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new WeatherService(
            config('weather.api_key'),
            config('weather.base_url'),
        );
    }

    public function test_get_weather_returns_weather_data(): void
    {
        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'name' => 'Quezon City',
                'dt' => 1779428700,
                'main' => [
                    'temp' => 35.14,
                ],
                'weather' => [
                    [
                        'description' => 'few clouds',
                    ],
                ],
            ], 200),
        ]);

        $result = $this->service->getWeather('Quezon City');

        $this->assertSame('Quezon City', $result->city);
        $this->assertIsFloat($result->temperature);
        $this->assertIsString($result->description);
        $this->assertIsInt($result->timestamp);
        $this->assertSame('external', $result->source);
    }

    public function test_get_weather_throws_city_not_found_exception(): void
    {
        Http::fake([
            '*' => Http::response([], 404),
        ]);

        $this->expectException(WeatherServiceException::class);

        $this->service->getWeather('UnknownCity');
    }

    public function test_get_weather_throws_invalid_api_key_exception(): void
    {
        Http::fake([
            '*' => Http::response([], 401),
        ]);

        $this->expectException(WeatherServiceException::class);

        $this->service->getWeather('Quezon City');
    }

    public function test_get_weather_throws_api_unavailable_exception(): void
    {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $this->expectException(WeatherServiceException::class);

        $this->service->getWeather('Quezon City');
    }

    public function test_get_cached_weather_returns_cached_data(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn([
                'city' => 'Quezon City',
                'temperature' => 35.14,
                'description' => 'few clouds',
                'timestamp' => 1779428700,
                'source' => 'cache',
            ]);

        $result = $this->service->getCachedWeather('Quezon City');

        $this->assertInstanceOf(WeatherData::class, $result);

        $this->assertInstanceOf(WeatherData::class, $result);
        $this->assertSame('Quezon City', $result->city);
        $this->assertIsFloat($result->temperature);
        $this->assertIsString($result->description);
        $this->assertIsInt($result->timestamp);
        $this->assertSame('cache', $result->source);
    }

    public function test_get_cached_weather_stores_data_when_cache_missing(): void
    {
        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'name' => 'Quezon City',
                'dt' => 1779428700,
                'main' => [
                    'temp' => 35.14,
                ],
                'weather' => [
                    [
                        'description' => 'few clouds',
                    ],
                ],
            ], 200),
        ]);

        Cache::spy();

        $result = $this->service->getCachedWeather('Quezon City');

        $this->assertInstanceOf(WeatherData::class, $result);

        Cache::shouldHaveReceived('remember')
            ->once();
    }
}