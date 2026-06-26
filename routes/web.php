<?php

declare(strict_types=1);

use Gingerminds\LaravelCore\Http\Controllers\Permission\PermissionController;
use Gingerminds\LaravelCore\Http\Controllers\Role\RoleController;
use Gingerminds\LaravelCore\Http\Controllers\Security\AuthController;
use Gingerminds\LaravelCore\Http\Controllers\User\ContributorController;
use Gingerminds\LaravelCore\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(config('gingerminds-core.admin_prefix'))
    ->name('gingerminds-core.')
    ->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('login', 'login')->name('login');
            Route::post('login', 'authenticate')->name('authenticate');
            Route::get('reset-password', 'reset')->name('reset-password');
        });

        Route::middleware(['gingerminds-core.auth'])->group(function () {
            Route::controller(AuthController::class)->group(function () {
                Route::post('logout', 'logout')->name('logout');
            });

            Route::controller(UserController::class)->name('profile.')->group(function () {
                Route::get('profile', 'editProfile')->name('edit-profile');
                Route::patch('profile', 'updateProfile')->name('update-profile');
            });

            Route::resource('users', UserController::class);
            Route::resource('contributors', ContributorController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
        });
    });
