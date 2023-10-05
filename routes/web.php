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

Route::get('/', [CoreController::class, 'loginPage']);


Route::get('/register', [CoreController::class, 'registerPage']);
Route::get('/login', [CoreController::class, 'loginPage']);
Route::get('/dashboard', [CoreController::class, 'dashboard']);

// Route::get('/songs', [ArtistSongsController::class, 'songsPage']);
// Route::get('/artists', [ArtistSongsController::class, 'artistsPage']);
Route::get('/dashboard/users', [CoreController::class, 'userTab'])->name('core.user.user-tab');
Route::get('/dashboard/artists', [ArtistSongsController::class, 'artistTab'])->name('artist-song.artist-tab');
