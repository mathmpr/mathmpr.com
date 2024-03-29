<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

/**
 * @property $title
 * @property $description
 * @property $slug
 */
class Node extends MainModel
{
    use SoftDeletes;

    protected array $translatable = [
        'title',
        'description',
        'slug'
    ];

    public function contents()
    {
        return $this->hasMany(NodeContent::class)
            ->where(['lang' => App::getLocale()])
            ->orderBy('order', 'desc');
    }

}
