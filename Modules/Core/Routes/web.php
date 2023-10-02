<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthenticationController;

// Route::prefix("core")->group(function() {
//     Route::get("/register", [AuthenticationController::class, "registerPage"]);
//     Route::post("/", [AuthenticationController::class, "register"])->name("core.register.user");
// });
