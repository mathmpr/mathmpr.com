<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class NodeController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        return Controller::autoDiscoverView('node/create', [
            'id' => null
        ]);
    }

    public function edit(Request $request, $lang, $slug)
    {
        return Controller::autoDiscoverView('node/create', [
            'id' => $slug
        ]);
    }

    public function store(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }
}
