<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ArtistSongs\Http\Controllers\ArtistController;
use Modules\ArtistSongs\Http\Controllers\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'auth.custom'])->group(function() {
    Route::apiResource('artists', ArtistController::class);
    Route::apiResource('songs', SongController::class);
    Route::get('songs/artist/{id}', [SongController::class, "fetchByArtist"]);
});