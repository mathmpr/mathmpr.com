<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string type
 * @property string name
 * @property string local
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Media extends MainModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'local',
        'mime',
        'width',
        'height'
    ];

    public function internalPath(): string
    {
        return public_path() . $this->local;
    }
}
