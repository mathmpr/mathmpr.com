<?php

namespace App\Utils;

use App\Models\MainModel;
use App\Models\Translate;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @param $id
 * @param array $replace
 * @param string|null $locale
 * @return array|Translator|string|null
 */
function _trans($id, array $replace = [], string $locale = null): array|string|Translator|null
{
    return trans('messages.' . $id, $replace, $locale);
}

class Lang
{
    /**
     * @var string|null
     */
    public static ?string $lang = null;

    public static function discoverLang(): string
    {
        if (self::$lang) {
            return self::$lang;
        }
        $lang = config('app.config');
        $request = request();
        if ($browserLanguage = $request->headers->get('accept-language')) {
            $lang = explode(',', $browserLanguage);
            $lang = explode('-', $lang[0]);
            $lang = $lang[0];
        }
        $urlLang = $request->url();
        $urlLang = explode('//', $urlLang);
        $urlLang = explode('/', end($urlLang));
        $key = 1;
        if (count($urlLang) > $key && $urlLang[$key] === 'api') {
            $key++;
        }
        if (count($urlLang) > $key && in_array($urlLang[$key], config('app.available_locales'))) {
            $lang = $urlLang[$key];
        }
        self::$lang = $lang;
        return $lang ?? config('app.fallback_locale');
    }

    /**
     * @param string $action
     * @param $model
     */
    public static function manageData(string $action, &$model): void
    {
        if (is_int(stripos($action, 'saving')) || is_int(stripos($action, 'created')) || is_int(
                stripos($action, 'updated')
            )) {
            self::manageSaveData($model[0]);
        } else {
            if (is_int(stripos($action, 'retrieved'))) {
                self::manageFetchData($model[0]);
            } else {
                if (is_int(stripos($action, 'deleted'))) {
                    self::manageDeleteData($model[0]);
                }
            }
        }
    }

    /**
     * @param MainModel $model
     */
    private static function manageSaveData(MainModel &$model): void
    {
        $request = request();
        $class = str_replace('\\', '/', get_class($model));
        if ($model->id && ($translates = $model->hasTranslates()) && !$model->translationsSaved()) {
            $languages = [];
            $fields = [];

            $all = $request->all();
            foreach ($all as $key => $value) {
                $model->setLangValue($key, $value);
            }

            foreach ($translates as $lang => $translate) {
                foreach ($translate as $field => $value) {
                    if (!in_array($lang, $languages)) {
                        $languages[] = $lang;
                    }
                    if (!in_array($field, $fields)) {
                        $fields[] = $field;
                    }
                }
            }

            Translate::where('object_id', $model->id)
                ->where('object_class', $class)
                ->whereIn('field', $fields)
                ->whereIn('lang', $languages)
                ->delete();

            foreach ($translates as $lang => $translate) {
                foreach ($translate as $field => $value) {
                    Translate::create([
                        'object_id' => $model->id,
                        'object_class' => $class,
                        'field' => $field,
                        'lang' => $lang,
                        'value' => $value
                    ]);
                }
            }

            $model->translationsSaved(true);
        }
    }

    /**
     * @param MainModel $model
     */
    private static function manageFetchData(MainModel &$model): void
    {
        if (!$model->translationsLoaded()) {
            $model->refreshLang();
        }
    }

    /**
     * @param MainModel $model
     */
    private static function manageDeleteData(MainModel &$model): void
    {
        $class = str_replace('\\', '/', get_class($model));
        if ($model->getSoftDeleteAttribute()) {
            DB::table('translates')
                ->where('object_id', $model->id)
                ->where('object_class', $class)->delete();
        }
    }


}
