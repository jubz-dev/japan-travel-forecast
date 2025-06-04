<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
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
     * @OA\Get(
     *     path="/api/weather/{city}",
     *     tags={"Weather"},
     *     summary="Get weather forecast for a city",
     *     @OA\Parameter(
     *         name="city",
     *         in="path",
     *         required=true,
     *         description="City name in Japan",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Forecast data",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to fetch weather data",
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
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
