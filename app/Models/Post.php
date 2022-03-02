<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends MainModel
{
    use SoftDeletes;

    protected array $translatable = [
        'title', 'description'
    ];
}
