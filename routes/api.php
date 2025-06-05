<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1/auth")->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name("register");
    Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name("password.reset");
    Route::post('/accept-invite', [AuthController::class, 'register'])->name("accept-invite");

    // admin role routes
    Route::prefix('admin')->middleware(["auth:api"])->group(function () {
        Route::post("/invite", [AuthController::class, "invite"])->middleware('permission:create users');
    });
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', fn() => auth()->user());
        Route::post("/refresh-token", [AuthController::class, "refreshToken"]);
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/email/verify/{id}/{hash}", [AuthController::class, "verifyEmail"])->name("verification.verify");
        Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationMail'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');
    });

});
