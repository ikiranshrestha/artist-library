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

Route::middleware(['auth:sanctum', 'auth.custom'])->group(function() {
    // Route::get("/all", [AuthenticationController::class, "fetchAllUsers"])->name("core.users.all");
    // Route::get("/{id}", [AuthenticationController::class, "fetchUser"])->name("core.users.id");
    // Route::put("/{id}", [AuthenticationController::class, "updateUser"])->name("core.users.update");
    // Route::delete("/{id}", [AuthenticationController::class, "deleteUser"])->name("core.users.delete");
    Route::apiResource("user", AuthenticationController::class)->except("store");
    Route::post("user/logout", [AuthenticationController::class, "logout"])->name("logout");
    Route::post("user/create", [AuthenticationController::class, "register"])->name("core.create.user");

});

Route::prefix("user")->group(function() {
    Route::post("/register", [AuthenticationController::class, "register"])->name("core.register.user");

    Route::post("/login", [AuthenticationController::class, "login"])->name("core.login.user");
});