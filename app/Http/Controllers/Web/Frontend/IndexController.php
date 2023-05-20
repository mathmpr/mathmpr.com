<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class IndexController extends Controller
{
    public function view()
    {
        return Controller::autoDiscoverView('home');
    }
}
