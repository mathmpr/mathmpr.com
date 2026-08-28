<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $title
 * @property $description
 * @property $slug
 * @property $content
 * @property $cover_image
 */
class Node extends MainModel
{
    use SoftDeletes;

    protected array $translatable = [
        'title',
        'description',
        'content',
        'slug'
    ];
}
