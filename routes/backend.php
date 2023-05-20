<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Backend\LogoutController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\PostController;

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'view'])
        ->name('backend.index');

    Route::any('/logout', [LogoutController::class, 'logout'])
        ->name('backend.logout');

    Route::prefix('dashboard')->group(function () {

        Route::resource('posts', PostController::class);

    });

});
