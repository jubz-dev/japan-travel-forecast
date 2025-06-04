<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeoapifyService;
use Illuminate\Http\JsonResponse;

class PlacesController extends Controller
{
    private GeoapifyService $geoapifyService;

    public function __construct(GeoapifyService $geoapifyService)
    {
        $this->geoapifyService = $geoapifyService;
    }

    /**
     * Retrieve a list of places near a given city in Japan.
     *
     * @param string $city The name of the city to search for.
     * @return JsonResponse A JSON response with places data or an error if the city is not found.
     */
    public function getPlaces(string $city): JsonResponse
    {
        $places = $this->geoapifyService->getPlacesNearCity($city);

        if (!$places) {
            return response()->json(['error' => 'City not found'], 404);
        }

        return response()->json($places);
    }
}
