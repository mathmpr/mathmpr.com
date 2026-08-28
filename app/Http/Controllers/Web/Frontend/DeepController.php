<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Node;

class DeepController extends Controller
{
    public function view()
    {
        $posts = Node::latest()
            ->limit(40)
            ->get()
            ->filter(fn (Node $node) => $node->title && $node->slug)
            ->take(14)
            ->values();

        return Controller::autoDiscoverView('home', [
            'posts' => $posts,
            'pageTitle' => trans('frontend.nav.about') . ' | ' . trans('frontend.site.title'),
        ]);
    }
}
