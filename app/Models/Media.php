<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends MainModel
{
    use SoftDeletes;

    protected array $translatable = [
        'name', 'type', 'local'
    ];
}
