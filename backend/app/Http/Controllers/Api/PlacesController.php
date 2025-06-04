<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use App\Services\GeoapifyService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Places",
 *     description="Retrieve nearby places data."
 * )
 */
class PlacesController extends Controller
{
    private GeoapifyService $geoapifyService;

    public function __construct(GeoapifyService $geoapifyService)
    {
        $this->geoapifyService = $geoapifyService;
    }

    /**
     * @OA\Get(
     *     path="/api/places/{city}",
     *     tags={"Places"},
     *     summary="Get places near a given city",
     *     @OA\Parameter(
     *         name="city",
     *         in="path",
     *         required=true,
     *         description="City name in Japan",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of places",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
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
