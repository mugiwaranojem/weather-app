<?php

declare(strict_types=1);

namespace Tests\Unit\DTO;

use App\DTO\WeatherData;
use Tests\TestCase;

final class WeatherDataTest extends TestCase
{
    public function test_from_api_response_creates_weather_data(): void
    {
        $response = [
            'name' => 'Quezon City',
            'main' => [
                'temp' => 308.29, // Kelvin
            ],
            'weather' => [
                [
                    'description' => 'few clouds',
                ],
            ],
            'dt' => 1779428700,
        ];

        $result = WeatherData::fromApiResponse($response);

        $this->assertInstanceOf(WeatherData::class, $result);
        $this->assertSame('Quezon City', $result->city);
        $this->assertIsFloat($result->temperature);
        $this->assertIsString($result->description);
        $this->assertIsInt($result->timestamp);
        $this->assertSame('external', $result->source);
    }

    public function test_from_cached_creates_weather_data(): void
    {
        $cached = [
            'city' => 'Quezon City',
            'temperature' => 35.14,
            'description' => 'few clouds',
            'timestamp' => 1779428700,
            'source' => 'cache',
        ];

        $result = WeatherData::fromCached($cached);

        $this->assertInstanceOf(WeatherData::class, $result);
        $this->assertSame('Quezon City', $result->city);
        $this->assertIsFloat($result->temperature);
        $this->assertIsString($result->description);
        $this->assertIsInt($result->timestamp);

        $this->assertSame('cache', $result->source);
    }

    public function test_to_array_returns_expected_array(): void
    {
        $weatherData = new WeatherData(
            city: 'Quezon City',
            temperature: 35.14,
            description: 'few clouds',
            timestamp: 1779428700,
            source: 'external',
        );

        $result = $weatherData->toArray();

        $this->assertIsArray($result);

        $this->assertSame([
            'city' => 'Quezon City',
            'temperature' => 35.14,
            'description' => 'few clouds',
            'timestamp' => 1779428700,
            'source' => 'external',
        ], $result);
    }

    public function test_constructor_sets_properties(): void
    {
        $weatherData = new WeatherData(
            city: 'Quezon City',
            temperature: 35.14,
            description: 'few clouds',
            timestamp: 1779428700,
            source: 'external',
        );

        $this->assertSame('Quezon City', $weatherData->city);
        $this->assertIsFloat($weatherData->temperature);
        $this->assertIsString($weatherData->description);
        $this->assertIsInt($weatherData->timestamp);
        $this->assertSame('external', $weatherData->source);
    }
}