<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class LogoutController extends Controller
{
    public function logout(): RedirectResponse
    {
        auth()->logout();
        return redirect(App::getLocale() . '/login');
    }
}
