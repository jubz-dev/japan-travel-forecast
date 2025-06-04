<?php

namespace Tests\Feature;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    /** @test */
    public function it_returns_forecast_data()
    {
        $this->mock(WeatherService::class, function ($mock) {
            $mock->shouldReceive('getForecast')
                ->with('Tokyo')
                ->andReturn(['weather' => 'sunny']);
        });

        $response = $this->getJson('/api/weather/Tokyo');

        $response->assertOk()
                 ->assertJson(['weather' => 'sunny']);
    }

    /** @test */
    public function it_returns_404_when_city_not_found()
    {
        $this->mock(WeatherService::class, function ($mock) {
            $mock->shouldReceive('getForecast')
                ->andReturn(null);
        });

        $response = $this->getJson('/api/weather/UnknownCity');

        $response->assertNotFound()
                 ->assertJson(['error' => 'City not found']);
    }

    /** @test */
    public function it_returns_500_on_internal_error()
    {
        $this->mock(WeatherService::class, function ($mock) {
            $mock->shouldReceive('getForecast')
                ->andThrow(new \Exception('API error'));
        });

        Log::shouldReceive('error')->once();

        $response = $this->getJson('/api/weather/Tokyo');

        $response->assertStatus(500)
                 ->assertJson(['error' => 'Unable to fetch weather data']);
    }
}