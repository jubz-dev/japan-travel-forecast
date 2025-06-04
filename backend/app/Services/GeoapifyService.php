<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class GeoapifyService
{
    private Client $client;
    private string $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('services.geoapify.api_key');
    }

    /**
     * Fetch places near a city and cache the result.
     *
     * @param string $city
     * @return array|null
     */
    public function getPlacesNearCity(string $city): ?array
    {
        $cacheKey = 'places_' . strtolower($city);

        return Cache::remember($cacheKey, 3600, function () use ($city) {
            $coordinates = $this->getCityCoordinates($city);

            if (!$coordinates) {
                return null;
            }

            return $this->getNearbyPlaces($coordinates['lon'], $coordinates['lat']);
        });
    }

    private function getCityCoordinates(string $city): ?array
    {
        $response = $this->client->get('https://api.geoapify.com/v1/geocode/search', [
            'query' => [
                'text' => "{$city}, Japan",
                'limit' => 9,
                'apiKey' => $this->apiKey,
                'lang' => 'en',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data['features'][0]['geometry']['coordinates'])) {
            return null;
        }

        [$lon, $lat] = $data['features'][0]['geometry']['coordinates'];

        return ['lon' => $lon, 'lat' => $lat];
    }

    private function getNearbyPlaces(float $lon, float $lat): array
    {
        $response = $this->client->get('https://api.geoapify.com/v2/places', [
            'query' => [
                'categories' => 'tourism.sights,entertainment,leisure',
                'filter' => "circle:{$lon},{$lat},5000",
                'limit' => 9,
                'apiKey' => $this->apiKey,
                'lang' => 'en',
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
