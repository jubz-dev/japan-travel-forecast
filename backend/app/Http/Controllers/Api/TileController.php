<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TileService;
use Illuminate\Http\Response;

class TileController extends Controller
{
    private TileService $tileService;

    public function __construct(TileService $tileService)
    {
        $this->tileService = $tileService;
    }

    /**
     * Retrieve a map tile image, using a random subdomain.
     *
     * @param string $s Subdomain indicator (not used directly, kept for API compatibility).
     * @param int $z Zoom level.
     * @param int $x X tile coordinate.
     * @param int $y Y tile coordinate.
     * @return Response Tile image response or error message.
     */
    public function getTileWithSubdomain(string $s, int $z, int $x, int $y): Response
    {
        return $this->getTile($z, $x, $y);
    }

    /**
     * Retrieve and cache a map tile image.
     *
     * @param int $z Zoom level.
     * @param int $x X tile coordinate.
     * @param int $y Y tile coordinate.
     * @return Response Tile image response or error message.
     */
    public function getTile(int $z, int $x, int $y): Response
    {
        $tile = $this->tileService->getTile($z, $x, $y);

        if ($tile) {
            return response($tile, 200)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'max-age=31536000, public');
        }

        return response('Tile not available', 404);
    }
}
