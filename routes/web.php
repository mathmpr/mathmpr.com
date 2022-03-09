<?php

use Illuminate\Support\Facades\Route;
use App\Utils\Lang;

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

Route::get('/', function () {
    return redirect(Lang::$lang);
});

Route::prefix('{lang}')->group(function () {

    Route::get('/admin', function () {
        return post_view(view('backend/dashboard'));
    });

    Route::get('/', function () {
        return post_view(view('frontend/home'));
    });

    Route::get('/{slug}', function ($single) {
        return post_view(view('frontend/single'));
    });

});


