<?php

use Illuminate\Support\Facades\Route;

Route::any('unauthorized', function () {
    if (isApiCall()) {
        return response()->json([
            'message' => 'unauthorized'
        ], 401);
    }
    return view('web.unauthorized');
})->name('unauthorized');
