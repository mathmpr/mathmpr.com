<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\Frontend\IndexController;
use App\Http\Controllers\Web\Frontend\NodeController;
use App\Http\Controllers\Web\Backend\LoginController;

Route::get('/', [IndexController::class, 'view'])
    ->name('frontend.index');

Route::get('/login', [LoginController::class, 'view'])
    ->name('backend.login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('backend.login.perform');

Route::get('/{slug}', [NodeController::class, 'view']);
