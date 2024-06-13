<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MediaLibraryController;
use App\Http\Controllers\Api\NodeController;
use App\Http\Controllers\Api\NodeContentController;
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

    Route::get('nodes', [NodeController::class, 'index'])
        ->name('api.nodes.index');

    Route::get('nodes/{slug}', [NodeController::class, 'show'])
        ->name('api.nodes.view');

    Route::middleware('auth:api')->group(function () {

        Route::post('nodes', [NodeController::class, 'store'])
            ->name('api.nodes.create');

        Route::put('nodes/{slug}', [NodeController::class, 'store'])
            ->name('api.nodes.update');

        Route::delete('nodes/{slug}/content', [NodeController::class, 'destroyContent'])
            ->name('api.nodes.content.delete');

        Route::post('nodes/{slug}/content', [NodeContentController::class, 'store'])
            ->name('api.nodes.content.create');

        Route::put('nodes/{slug}/content', [NodeContentController::class, 'store'])
            ->name('api.nodes.content.update');

        Route::post('nodes/{slug}/content/reorder', [NodeContentController::class, 'reorder'])
            ->name('api.nodes.content.reorder');

        Route::resource('media-library', MediaLibraryController::class);
    });

    require __DIR__ . '/commons.php';
});

Route::any('{catchall}', [FallbackController::class, 'handle'])
    ->where('catchall', '.*');
