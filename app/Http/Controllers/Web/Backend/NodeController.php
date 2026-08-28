<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index()
    {
        $nodes = auth()->user()
            ->nodes()
            ->latest()
            ->paginate(15);

        return Controller::autoDiscoverView('node/index', [
            'nodes' => $nodes
        ]);
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
