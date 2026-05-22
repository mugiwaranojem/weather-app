<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\WeatherServiceException;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherService $weatherService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['required', 'string'],
        ]);

        try {
            $weather = $this->weatherService
                ->getWeather($validated['city']);

            return response()->json(
                $weather->toArray()
            );
        } catch (WeatherServiceException $exception) {
            return $this->errorResponse($exception);
        }
    }

    public function cached(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['required', 'string'],
        ]);

        try {
            $weather = $this->weatherService
                ->getCachedWeather($validated['city']);

            return response()->json(
                $weather->toArray()
            );
        } catch (WeatherServiceException $exception) {
            return $this->errorResponse($exception);
        }
    }

    private function errorResponse(
        WeatherServiceException $exception
    ): JsonResponse {
        return response()->json([
            'message' => $exception->getMessage(),
        ], $exception->getCode());
    }
}