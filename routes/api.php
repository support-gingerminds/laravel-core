<?php

declare(strict_types=1);

use Gingerminds\LaravelCore\Http\Controllers\Security\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
