<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Retrieve a weather forecast for a given city in Japan.
     *
     * @param string $city The name of the city to get the forecast for.
     * @return JsonResponse The weather forecast or an error response.
     */
    public function getForecast(string $city): JsonResponse
    {
        try {
            $forecast = $this->weatherService->getForecast($city);

            if (!$forecast) {
                return response()->json(['error' => 'City not found'], 404);
            }

            return response()->json($forecast);
        } catch (\Exception $e) {
            Log::error("Weather API error: " . $e->getMessage());

            return response()->json(['error' => 'Unable to fetch weather data'], 500);
        }
    }
}
