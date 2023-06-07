<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $id
 * @property $post_id
 * @property $order
 * @property $content
 * @property $lang
 * @property $type
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class PostContent extends MainModel
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'post_id',
        'order',
        'content',
        'lang',
        'type'
    ];
}
