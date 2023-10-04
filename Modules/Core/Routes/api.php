<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthenticationController;
use Modules\Core\Http\Middleware\AuthenticateCustom;

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

Route::middleware(['auth:sanctum', 'auth.custom'])->prefix("user")->group(function() {
    Route::post("/logout", [AuthenticationController::class, "logout"])->name("logout");
    Route::post("/create", [AuthenticationController::class, "register"])->name("core.create.user");

});

Route::prefix("user")->group(function() {
    Route::get("/register", [AuthenticationController::class, "registerPage"]);
    Route::post("/register-user", [AuthenticationController::class, "register"])->name("core.register.user");

    Route::post("/login", [AuthenticationController::class, "login"])->name("login");
});