<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class WeatherData
{
    public function __construct(
        public string $city,
        public float $temperature,
        public string $description,
        public int $timestamp,
        public string $source,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            city: $data['name'],
            temperature: round($data['main']['temp'] - 273.15, 2), // Kelvin → Celsius
            description: $data['weather'][0]['description'],
            timestamp: $data['dt'],
            source: 'external',
        );
    }

    public static function fromCached(array $data): self
    {
        return new self(
            city: $data['city'],
            temperature: $data['temperature'],
            description: $data['description'],
            timestamp: $data['timestamp'],
            source: 'cache',
        );
    }

    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'temperature' => $this->temperature,
            'description' => $this->description,
            'timestamp' => $this->timestamp,
            'source' => $this->source,
        ];
    }
}