<?php

namespace App\Providers;

use App\Http\Middleware\Lang;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::share('lang', App::getLocale());
    }
}
