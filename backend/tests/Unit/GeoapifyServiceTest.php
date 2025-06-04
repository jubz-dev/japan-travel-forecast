<?php

namespace Tests\Unit;

use App\Services\GeoapifyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GeoapifyServiceTest extends TestCase
{
    /** @test */
    public function it_returns_places_from_cache()
    {
        $city = 'Tokyo';
        $cachedData = ['places' => 'cached'];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($cachedData);

        $client = \Mockery::mock(Client::class);
        $service = new GeoapifyService($client);

        $result = $service->getPlacesNearCity($city);

        $this->assertEquals($cachedData, $result);
    }

    /** @test */
    public function it_fetches_and_returns_places_if_not_in_cache()
    {
        $city = 'Tokyo';
        $geoResponse = [
            'features' => [
                [
                    'geometry' => ['coordinates' => [139.6917, 35.6895]],
                ],
            ],
        ];

        $placesResponse = ['places' => 'fetched'];

        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')
            ->once()
            ->with('https://api.geoapify.com/v1/geocode/search', \Mockery::on(function ($arg) use ($city) {
                return $arg['query']['text'] === "{$city}, Japan";
            }))
            ->andReturn(new Response(200, [], json_encode($geoResponse)));

        $mockClient->shouldReceive('get')
            ->once()
            ->with('https://api.geoapify.com/v2/places', \Mockery::any())
            ->andReturn(new Response(200, [], json_encode($placesResponse)));

        $service = new GeoapifyService($mockClient);
        $result = $service->getPlacesNearCity($city);

        $this->assertEquals($placesResponse, $result);
    }

    /** @test */
    public function it_returns_null_if_city_coordinates_are_not_found()
    {
        $city = 'InvalidCity';
        $geoResponse = ['features' => []];

        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')
            ->once()
            ->with('https://api.geoapify.com/v1/geocode/search', \Mockery::any())
            ->andReturn(new Response(200, [], json_encode($geoResponse)));

        $service = new GeoapifyService($mockClient);
        $result = $service->getPlacesNearCity($city);

        $this->assertNull($result);
    }
}