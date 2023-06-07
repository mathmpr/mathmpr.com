<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * @method static Translate | Builder whereId($operator = null, $value = null)
 * @method static Translate | Builder whereObjectId($operator = null, $value = null)
 * @method static Translate | Builder whereObjectClass($operator = null, $value = null)
 * @method static Translate | Builder whereLang($operator = null, $value = null)
 * @method static Translate | Builder whereField($operator = null, $value = null)
 * @method static Translate | Builder whereValue($operator = null, $value = null)
 */
class Translate extends MainModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'object_id',
        'object_class',
        'lang',
        'field',
        'value'
    ];

}
