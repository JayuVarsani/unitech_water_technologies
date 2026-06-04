<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes For Api
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api/v1', 'as' => 'api.v1.', 'middleware' => 'api.setLocale'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {});

    Route::group(['prefix' => 'work', 'as' => 'work.', 'middleware' => ['auth:sanctum', 'api.activeUser']], function () {});
});
