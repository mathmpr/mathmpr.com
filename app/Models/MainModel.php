<?php

namespace App\Models;

use App\Utils\Lang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MainModel extends Model
{
    use HasFactory;

    public array $translations = [];
    protected array $translatable = [];
    public bool $translationsSaved = false;
    public bool $translationsLoaded = false;

    public function setLangValue($key, $value, $lang = null): bool
    {
        $lang = $lang ? $lang : Lang::$lang;
        if (property_exists($this, 'translatable')) {
            if (in_array($key, $this->{"translatable"})) {
                if (!isset($lang, $this->translations)) {
                    $this->translations[$lang] = [];
                }
                $this->translations[$lang][$key] = $value;
                $this->translationsSaved(false);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $key
     * @param null $lang
     * @return false|mixed
     */
    public function getLangValue($key, $lang = null)
    {
        $lang = $lang ? $lang : Lang::$lang;
        if (property_exists($this, 'translatable')) {
            if (in_array($key, $this->{"translatable"})) {

                if (isset($this->translations[$lang]) && isset($this->translations[$lang][$key])) {
                    return $this->translations[$lang][$key];
                }

                if ($this->id) {
                    $translate = Translate::where('object_class', get_class($this))
                        ->where('object_id', $this->id)
                        ->where('lang', $lang)
                        ->where('field', $key)
                        ->first();
                    if ($translate) {
                        if (!isset($this->translations[$lang])) {
                            $this->translations[$lang] = [];
                        }
                        $this->translations[$lang][$key] = $translate->value;
                        return $translate->value;
                    }
                }
            }
        }
        return false;
    }

    public function refreshLang()
    {
        $class = str_replace('\\', '/', get_class($this));
        $translations = DB::table('translates')
            ->where('object_id', $this->id)
            ->where('object_class', $class)->get();
        if (!blank($translations)) {
            foreach ($translations as $translate) {
                $this->setLangValue($translate->field, $translate->value, $translate->lang);
            }
        }
    }

    public function refresh()
    {
        $this->refreshLang();
        return parent::refresh();
    }

    public function translationsSaved($saved = null): bool
    {
        if (is_bool($saved)) {
            $this->translationsSaved = $saved;
        }
        return $this->translationsSaved;
    }

    public function translationsLoaded($loaded = null): bool
    {
        if (is_bool($loaded)) {
            $this->translationsLoaded = $loaded;
        }
        return $this->translationsLoaded;
    }

    public function hasTranslates()
    {
        return !empty($this->translations) ? $this->translations : false;
    }

    public function hasTranslate($key, $lang = null): bool
    {
        if ($this->hasTranslates()) {
            return $this->getLangValue($key, $lang);
        }
        return false;
    }

    public function getSoftDeleteAttribute(): bool
    {
        return in_array(SoftDeletes::class, class_uses($this)) && !$this->forceDeleting;
    }

    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function fill(array $attributes): MainModel
    {
        if (!empty($attributes)) {
            $columns = $this->getTableColumns();
            foreach ($attributes as $key => $value) {
                if (!in_array($key, $columns) && !in_array($key, $this->fillable) && in_array($key, $this->translatable)) {
                    $this->setLangValue($key, $value);
                    unset($attributes[$key]);
                }
            }
        }
        return parent::fill($attributes);
    }

    public function __set($key, $value)
    {
        $setLang = $this->setLangValue($key, $value);
        if (!$setLang) parent::__set($key, $value);
    }

    public function __get($key)
    {
        $getLang = $this->getLangValue($key);
        return $getLang ? $getLang : parent::__get($key);
    }

    public function translationsToArray($lang = false)
    {
        $translates = [];
        $lang = $lang ? $lang : Lang::$lang;
        if (property_exists($this, 'translatable')) {
            foreach ($this->{"translatable"} as $field) {
                $translates[$field] = $this->{$field};
            }
        }
        return $translates;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), $this->translationsToArray());
    }

    public function toJson($options = 0)
    {
        dd('json ?');
        return parent::toJson($options);
    }

}
