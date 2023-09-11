<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $id
 * @property $node_id
 * @property $order
 * @property $content
 * @property $lang
 * @property $type
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class NodeContent extends MainModel
{
    use SoftDeletes;

    protected ?array $decodedContent = null;

    protected $fillable = [
        'id',
        'node_id',
        'order',
        'content',
        'lang',
        'type'
    ];

    public function decodedContent(): array
    {
        $decoded = json_decode($this->content, true);
        $this->decodedContent = $decoded;
        return $decoded;
    }

    public function typeIs(string $type): bool
    {
        return strtolower($this->type) === $type;
    }

    /**
     * @return bool|string
     */
    public function getMediaLocation(): bool|string
    {
        $this->decodedContent();
        if ($this->typeIs('media')) {
            return str_replace('//', '/public/', base_path($this->decodedContent['local']));
        }
        return false;
    }

    public function deleteMedia(): bool
    {
        if ($media = $this->getMediaLocation()) {
            if (file_exists($media)) {
                return unlink($media);
            }
        }
        return false;
    }
}
