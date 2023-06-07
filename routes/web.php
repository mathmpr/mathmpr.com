<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\FallbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('{lang?}')->group(function () {
    require __DIR__ . '/backend.php';
    require __DIR__ . '/frontend.php';

    require __DIR__ . '/commons.php';
});

Route::any('{catchall}', [FallbackController::class, 'handle'])
    ->where('catchall', '.*');
