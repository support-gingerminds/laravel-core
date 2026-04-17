<?php

use Gingerminds\LaravelCore\Http\Controllers\User\ContributorController;
use Gingerminds\LaravelCore\Http\Controllers\Permission\PermissionController;
use Gingerminds\LaravelCore\Http\Controllers\Role\RoleController;
use Gingerminds\LaravelCore\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
  ->name('gingerminds-core.')
  ->group(function () {
      Route::resource('users', UserController::class);
      Route::resource('contributors', ContributorController::class);
      Route::resource('roles', RoleController::class);
      Route::resource('permissions', PermissionController::class);
  });