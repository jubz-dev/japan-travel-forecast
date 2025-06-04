<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TileControllerTest extends TestCase
{
    /** @test */
    public function it_returns_cached_tile()
    {
        $z = 10;
        $x = 100;
        $y = 200;
        $cachePath = "tiles/{$z}/{$x}/{$y}.png";
        $tileData = 'cached_tile_data';

        Storage::fake('local');
        Storage::disk('local')->put($cachePath, $tileData);

        $response = $this->get("/api/tiles/{$z}/{$x}/{$y}");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $response->assertHeader('Cache-Control', 'max-age=31536000, public');
        $this->assertEquals($tileData, $response->getContent());
    }

    /** @test */
    public function it_fetches_tile_if_not_cached()
    {
        $z = 10;
        $x = 100;
        $y = 200;
        $tileData = 'fetched_tile_data';

        Storage::fake('local');

        Http::fake([
            'https://*.tile.openstreetmap.org/*' => Http::response($tileData, 200),
        ]);

        $response = $this->get("/api/tiles/{$z}/{$x}/{$y}");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $response->assertHeader('Cache-Control', 'max-age=31536000, public');
        $this->assertEquals($tileData, $response->getContent());
        Storage::disk('local')->assertExists("tiles/{$z}/{$x}/{$y}.png");
    }

    /** @test */
    public function it_returns_404_if_tile_unavailable()
    {
        $z = 10;
        $x = 100;
        $y = 200;

        Storage::fake('local');

        Http::fake([
            'https://*.tile.openstreetmap.org/*' => Http::response('', 404),
        ]);

        $response = $this->get("/api/tiles/{$z}/{$x}/{$y}");

        $response->assertNotFound();
    }
}