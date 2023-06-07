<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MediaLibraryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\FallbackController;
use Illuminate\Support\Facades\Route;

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
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::get('posts', [PostController::class, 'index'])
        ->name('api.posts.index');

    Route::get('posts/{slug}', [PostController::class, 'show'])
        ->name('api.posts.view');

    Route::middleware('auth:api')->group(function () {

        Route::post('posts', [PostController::class, 'store'])
            ->name('api.posts.create');

        Route::post('posts/{slug}/reorder', [PostController::class, 'reorder'])
            ->name('api.posts.reorder');

        Route::put('posts/{slug}', [PostController::class, 'store'])
            ->name('api.posts.update');

        Route::resource('media-library', MediaLibraryController::class);
    });

    require __DIR__ . '/commons.php';
});

Route::any('{catchall}', [FallbackController::class, 'handle'])
    ->where('catchall', '.*');
