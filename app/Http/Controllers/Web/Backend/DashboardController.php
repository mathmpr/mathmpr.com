<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class DashboardController extends Controller
{
    public function view(): View|Factory
    {
        return Controller::autoDiscoverView('dashboard');
    }
}
