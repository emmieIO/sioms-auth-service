<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name("register");
    Route::post('/accept-invite', [AuthController::class, 'register'])->name("accept-invite");

    // admin role routes
    Route::prefix('admin')->middleware(["auth:api"])->group(function () {
        Route::post("/invite", [AuthController::class, "invite"])->middleware('permission:create users');
    });
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', fn ()=>auth()->user());
        Route::post("/refresh-token", [AuthController::class, "refreshToken"]);
        Route::post("/logout", [AuthController::class, "logout"]);
    });

});
