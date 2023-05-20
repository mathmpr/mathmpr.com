<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\FallbackController;

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

Route::prefix('{lang?}')->group(function () {
    Route::resource('media-library', MediaLibraryController::class);

    Route::post('register', [PassportAuthController::class, 'register']);

    Route::post('login', [PassportAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::resource('posts', PostController::class);
    });
});

Route::any('{catchall}', [FallbackController::class, 'handle'])
    ->where('catchall', '.*');
