<?php

use App\Http\Controllers\Api\PlacesController;
use App\Http\Controllers\Api\TileController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/weather/{city}', [WeatherController::class, 'getForecast']);
    Route::get('/places/{city}', [PlacesController::class, 'getPlaces']);
    Route::get('/tiles/{z}/{x}/{y}.png', [TileController::class, 'getTile']);
    Route::get('/tiles/{s}/{z}/{x}/{y}.png', [TileController::class, 'getTileWithSubdomain']);
});
