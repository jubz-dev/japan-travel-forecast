<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TileService
{
    /**
     * Retrieve a map tile image, using caching and random subdomains.
     *
     * @param int $z Zoom level.
     * @param int $x X tile coordinate.
     * @param int $y Y tile coordinate.
     * @return string|null Binary PNG data or null if not found.
     */
    public function getTile(int $z, int $x, int $y): ?string
    {
        $cachePath = "tiles/{$z}/{$x}/{$y}.png";

        if (Storage::disk('local')->exists($cachePath)) {
            return Storage::disk('local')->get($cachePath);
        }

        $subdomains = ['a', 'b', 'c'];
        $subdomain = $subdomains[array_rand($subdomains)];
        $tileUrl = "https://{$subdomain}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";

        $response = Http::get($tileUrl);

        if ($response->successful()) {
            Storage::disk('local')->put($cachePath, $response->body());
            return $response->body();
        }

        return null;
    }
}
