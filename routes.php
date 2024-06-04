<?php

use VanDmade\Cuztomisable\Controllers\SettingsController;
use VanDmade\Cuztomisable\Controllers\Authentication;
use VanDmade\Cuztomisable\Controllers\PermissionController;
use VanDmade\Cuztomisable\Controllers\RoleController;
use VanDmade\Cuztomisable\Controllers\UserController;

Route::controller(SettingsController::class)->group(function () {
    Route::get('/cuztomisable/settings', 'all');
});
Route::post('/login', [Authentication\LoginController::class, 'login']);
Route::controller(Authentication\MFAController::class)->group(function () {
    Route::post('/login/mfa/{token}/send', 'send');
    Route::get('/login/mfa/{token}/verify', 'verify');
    Route::post('/login/mfa/{token}', 'save');
});
Route::controller(Authentication\PasswordController::class)->group(function () {
    Route::post('/password/forgot', 'forgot');
    Route::get('/password/forgot/{token}/send', 'send');
    Route::post('/password/forgot/{token}', 'save');
    Route::get('/password/forgot/{token}/verify/{code?}', 'verify');
});
Route::group(['middleware' => ['auth:sanctum', 'ability:user,admin']], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/me', 'get');
    });
});
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/list/users', 'list');
        Route::delete('/user/{id}', 'toggleDelete');
        Route::patch('/user/{id}/locked', 'toggleLocked');
    });
    Route::controller(RoleController::class)->group(function () {
        Route::get('/role/{id}', 'get');
        Route::get('/roles', 'table');
        Route::post('/role/{id?}', 'save');
        Route::get('/list/roles', 'list');
        Route::delete('/role/{id}', 'toggleDelete');
        Route::delete('/role/{id}/permission/{permission}', 'removePermission');
    });
    Route::controller(PermissionController::class)->group(function () {
        Route::get('/permission/{id}', 'get');
        Route::get('/permissions', 'table');
        Route::post('/permission/{id?}', 'save');
        Route::get('/list/permissions', 'list');
        Route::get('/list/role/{id}/permissions', 'list');
        Route::delete('/permission/{id}', 'toggleDelete');
    });
});