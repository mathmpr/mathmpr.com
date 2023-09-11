<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder as OverrideBuilder;
use App\Override\OverrideBuilder as Builder;

/**
 * @method static make(array $attributes = [])
 * @method static withGlobalScope($identifier, $scope)
 * @method static withoutGlobalScope($scope)
 * @method static withoutGlobalScopes(array $scopes = null)
 * @method static removedScopes()
 * @method static whereKey($id)
 * @method static whereKeyNot($id)
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 * @method static orWhere($column, $operator = null, $value = null)
 * @method static latest($column = null)
 * @method static oldest($column = null)
 * @method static hydrate(array $items)
 * @method static fromQuery($query, $bindings = [])
 * @method static find($id, $columns = ['*'])
 * @method static findMany($ids, $columns = ['*'])
 * @method static findOrFail($id, $columns = ['*'])
 * @method static findOrNew($id, $columns = ['*'])
 * @method static firstOrNew(array $attributes = [], array $values = [])
 * @method static firstOrCreate(array $attributes = [], array $values = [])
 * @method static updateOrCreate(array $attributes, array $values = [])
 * @method static firstOrFail($columns = ['*'])
 * @method static firstOr($columns = ['*'], \Closure $callback = null)
 * @method static sole($columns = ['*'])
 * @method static value($column)
 * @method static valueOrFail($column)
 * @method static get($columns = ['*'])
 * @method static getModels($columns = ['*'])
 * @method static eagerLoadRelations(array $models)
 * @method static getRelation($name)
 * @method static cursor()
 * @method static pluck($column, $key = null)
 * @method static paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method static simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method static cursorPaginate($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null)
 * @method static create(array $attributes = [])
 * @method static forceCreate(array $attributes)
 * @method static update(array $values)
 * @method static upsert(array $values, $uniqueBy, $update = null)
 * @method static increment($column, $amount = 1, array $extra = [])
 * @method static decrement($column, $amount = 1, array $extra = [])
 * @method static delete()
 * @method static forceDelete()
 * @method static onDelete(\Closure $callback)
 * @method static hasNamedScope($scope)
 * @method static scopes($scopes)
 * @method static applyScopes()
 * @method static with($relations, $callback = null)
 * @method static without($relations)
 * @method static withOnly($relations)
 * @method static newModelInstance($attributes = [])
 * @method static withCasts($casts)
 * @method static getQuery()
 * @method static setQuery($query)
 * @method static toBase()
 * @method static getEagerLoads()
 * @method static setEagerLoads(array $eagerLoad)
 * @method static getModel()
 * @method static setModel(Model $model)
 * @method static qualifyColumn($column)
 * @method static qualifyColumns($columns)
 * @method static getMacro(string $name)
 * @method static hasMacro(string $name)
 * @method static clone()
 * @method static whereTranslationExists(array|string $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 */
class MainModel extends Model
{
    use HasFactory;

    public array $translations = [];
    protected array $translatable = [];
    public bool $translationsSaved = false;
    public bool $translationsLoaded = false;

    public function setLangValue($key, $value, $lang = null): bool
    {
        $lang = $lang ?? App::getLocale();
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
     * @return null|mixed
     */
    public function getLangValue($key, $lang = null)
    {
        $lang = $lang ?? App::getLocale();
        if (property_exists($this, 'translatable')) {
            if (in_array($key, $this->{"translatable"})) {

                if (isset($this->translations[$lang]) && isset($this->translations[$lang][$key])) {
                    return $this->translations[$lang][$key];
                }

                if ($this->id) {
                    $translate = Translate::where('object_class', str_replace('\\', '/', get_class($this)))
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
        return null;
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
        return $getLang ?? parent::__get($key);
    }

    public function translationsToArray()
    {
        $translates = [];
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
        return parent::toJson($options);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param OverrideBuilder $query
     * @return Builder
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }
}
