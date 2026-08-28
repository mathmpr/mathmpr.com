<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NodeController;
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

    Route::group(['middleware' => ['auth']], function () {

        Route::post('nodes', [NodeController::class, 'store'])
            ->name('api.nodes.create');

        Route::put('nodes/{slug}', [NodeController::class, 'update'])
            ->name('api.nodes.update');

        Route::post('nodes/{slug}/duplicate', [NodeController::class, 'duplicate'])
            ->name('api.nodes.duplicate');

        Route::delete('nodes/{slug}', [NodeController::class, 'destroy'])
            ->name('api.nodes.destroy');

        Route::post('nodes/{slug}/attachments', [NodeController::class, 'uploadAttachments'])
            ->name('api.nodes.attachments.upload');

        Route::post('nodes/{slug}/cover', [NodeController::class, 'uploadCover'])
            ->name('api.nodes.cover.upload');

    });

    require __DIR__ . '/commons.php';
});

Route::any('{catchall}', [FallbackController::class, 'handle'])
    ->where('catchall', '.*');
