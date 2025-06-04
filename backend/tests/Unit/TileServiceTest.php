<?php

namespace Tests\Unit;

use App\Services\TileService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TileServiceTest extends TestCase
{
    /** @test */
    public function it_returns_tile_from_cache_if_exists(): void
    {
        $z = 10;
        $x = 100;
        $y = 200;
        $cachePath = "tiles/{$z}/{$x}/{$y}.png";
        $fakeTile = 'fake_binary_data';

        Storage::fake('local');
        Storage::disk('local')->put($cachePath, $fakeTile);

        $service = new TileService();

        $result = $service->getTile($z, $x, $y);

        $this->assertEquals($fakeTile, $result);
    }

    /** @test */
    public function it_fetches_and_caches_tile_when_not_in_cache(): void
    {
        $z = 10;
        $x = 100;
        $y = 200;
        $cachePath = "tiles/{$z}/{$x}/{$y}.png";
        $fakeTile = 'fetched_binary_data';

        Storage::fake('local');

        Http::fake([
            'https://*.tile.openstreetmap.org/*' => Http::response($fakeTile, 200, [
                'Content-Type' => 'image/png',
            ]),
        ]);

        $service = new TileService();
        $result = $service->getTile($z, $x, $y);

        $this->assertEquals($fakeTile, $result);
        Storage::disk('local')->assertExists($cachePath);
    }

    /** @test */
    public function it_returns_null_when_tile_fetch_fails(): void
    {
        $z = 10;
        $x = 100;
        $y = 200;

        Storage::fake('local');

        Http::fake([
            'https://*.tile.openstreetmap.org/*' => Http::response('', 404),
        ]);

        $service = new TileService();
        $result = $service->getTile($z, $x, $y);

        $this->assertNull($result);
        Storage::disk('local')->assertMissing("tiles/{$z}/{$x}/{$y}.png");
    }
}