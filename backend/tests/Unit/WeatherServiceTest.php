<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    /** @test */
    public function it_returns_forecast_from_cache()
    {
        $city = 'Tokyo';
        $cacheKey = 'weather_forecast_tokyo';
        $cachedData = ['cached' => 'data'];

        Cache::shouldReceive('remember')
            ->with($cacheKey, \Mockery::any(), \Closure::class)
            ->andReturn($cachedData);

        $client = \Mockery::mock(Client::class);
        $service = new WeatherService($client);

        $result = $service->getForecast($city);

        $this->assertEquals($cachedData, $result);
    }

    /** @test */
    public function it_fetches_and_returns_forecast_when_not_cached()
    {
        $city = 'Tokyo';
        $responseData = ['list' => [['temp' => 20]]];
        $jsonData = json_encode($responseData);

        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->once()
            ->with('GET', 'forecast', \Mockery::on(function ($arg) {
                return $arg['query']['q'] === 'Tokyo,JP';
            }))
            ->andReturn(new Response(200, [], $jsonData));

        // Clear the cache for testing
        Cache::forget('weather_forecast_tokyo');

        $service = new WeatherService($mockClient);
        $result = $service->getForecast($city);

        $this->assertEquals($responseData, $result);
    }

    /** @test */
    public function it_returns_null_when_api_fails()
    {
        $city = 'Tokyo';

        $mockClient = \Mockery::mock(Client::class);
        $mockClient->shouldReceive('request')
            ->andReturn(new Response(404, [], ''));

        Cache::forget('weather_forecast_tokyo');

        $service = new WeatherService($mockClient);
        $result = $service->getForecast($city);

        $this->assertNull($result);
    }
}