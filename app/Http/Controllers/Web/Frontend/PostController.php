<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class PostController extends Controller
{
    public function view()
    {
        return Controller::autoDiscoverView('single');
    }
}
