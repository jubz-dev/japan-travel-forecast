<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    private Client $client;
    private string $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('services.openweather.api_key');
    }

    /**
     * Get the 5-day weather forecast for a city.
     *
     * @param string $city
     * @return array|null
     */
    public function getForecast(string $city): ?array
    {
        $cacheKey = 'weather_forecast_' . strtolower($city);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($city) {
            $response = $this->client->request('GET', 'forecast', [
                'base_uri' => 'https://api.openweathermap.org/data/2.5/',
                'query' => [
                    'q' => "{$city},JP",
                    'units' => 'metric',
                    'appid' => $this->apiKey,
                ],
                'timeout' => 5.0,
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents(), true);
        });
    }
}
