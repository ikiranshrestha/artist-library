<?php

use App\Http\Controllers\ArtistSongsController;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [CoreController::class, 'registerPage']);
// Route::get('/login', [CoreController::class, 'loginPage']);
// Route::get('/dashboard', [CoreController::class, 'dashboard']);

// Route::get('/songs', [ArtistSongsController::class, 'songsPage']);
// Route::get('/artists', [ArtistSongsController::class, 'artistsPage']);
