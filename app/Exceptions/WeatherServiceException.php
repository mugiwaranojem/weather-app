<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class WeatherServiceException extends Exception
{
    public static function cityNotFound(string $city): self
    {
        return new self(
            message: "City [{$city}] not found.",
            code: 404
        );
    }

    public static function invalidApiKey(): self
    {
        return new self(
            message: 'Invalid OpenWeather API key.',
            code: 401
        );
    }

    public static function apiUnavailable(): self
    {
        return new self(
            message: 'Weather service temporarily unavailable.',
            code: 503
        );
    }
}