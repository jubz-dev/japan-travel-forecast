<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\PlacesController;
use App\Http\Controllers\Api\TileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/weather/{city}', [WeatherController::class, 'getForecast']);
Route::get('/places/{city}', [PlacesController::class, 'getPlaces']);
Route::get('/tiles/{z}/{x}/{y}.png', [TileController::class, 'getTile']);
Route::get('/tiles/{s}/{z}/{x}/{y}.png', [TileController::class, 'getTileWithSubdomain']);
