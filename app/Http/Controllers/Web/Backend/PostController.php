<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class PostController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return Controller::autoDiscoverView('post/create');
    }

    public function edit(Request $request, $id)
    {

    }

    public function store(Request $request)
    {

    }

    public function destroy(Request $request) {

    }
}
