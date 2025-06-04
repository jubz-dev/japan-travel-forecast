<?php

namespace Tests\Feature;

use App\Services\GeoapifyService;
use Tests\TestCase;

class PlacesControllerTest extends TestCase
{
    /** @test */
    public function it_returns_places_list()
    {
        $this->mock(GeoapifyService::class, function ($mock) {
            $mock->shouldReceive('getPlacesNearCity')
                ->with('Tokyo')
                ->andReturn(['places' => 'some data']);
        });

        $response = $this->getJson('/api/places/Tokyo');

        $response->assertOk()
                 ->assertJson(['places' => 'some data']);
    }

    /** @test */
    public function it_returns_404_if_city_not_found()
    {
        $this->mock(GeoapifyService::class, function ($mock) {
            $mock->shouldReceive('getPlacesNearCity')
                ->with('UnknownCity')
                ->andReturn(null);
        });

        $response = $this->getJson('/api/places/UnknownCity');

        $response->assertNotFound()
                 ->assertJson(['error' => 'City not found']);
    }
}
