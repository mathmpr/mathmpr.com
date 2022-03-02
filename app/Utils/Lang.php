<?php

namespace App\Utils;

use App\Models\MainModel;
use App\Models\Translate;
use Carbon\Carbon;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MaxMind\Db\Reader;

/**
 * @param $id
 * @param array $replace
 * @param null|string $locale
 * @return array|Translator|string|null
 */
function _trans($id, $replace = [], $locale = null)
{
    return trans('messages.' . $id, $replace, $locale);
}

class Lang
{
    public static $lang = 'en';

    public static function setLang()
    {
        $lang = false;
        if (function_exists('geoip_country_code_by_name')) {

            $remote = false;

            foreach (
                [
                    'HTTP_CLIENT_IP',
                    'HTTP_CLIENT_IP',
                    'HTTP_X_FORWARDED_FOR',
                    'HTTP_X_FORWARDED_FOR',
                    'REMOTE_ADDR'
                ] as $key) {
                if (isset($_SERVER[$key])) {
                    $remote = $_SERVER[$key];
                    break;
                }
            }

            if (!$remote) return false;

            try {

                try {
                    $now = new Carbon();
                } catch (\Exception $exception) {
                    return false;
                }

                if ($remote === '::1' || $remote === '127.0.0.1') {
                    $remote = Config::get('app.localhost.remote');
                    $lastExecution = Config::get('app.localhost.date');
                    $forceUpdate = false;

                    if ($lastExecution) {
                        try {
                            $execution = new Carbon($lastExecution);
                        } catch (\Exception $exception) {
                            return false;
                        }
                        $now->add($execution->diff($now));
                        if (($now->getTimestamp() - $execution->getTimestamp()) >= 1800) {
                            $forceUpdate = true;
                        }
                    }

                    if ((!$remote && !$lastExecution) || $forceUpdate) {
                        $content = file_get_contents('http://checkip.dyndns.com/');
                        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)]?/', $content, $matches);
                        $remote = $matches[1];
                        if ($remote) {
                            Config::set('app.localhost.remote', $remote);
                            Config::set('app.localhost.date', date('Y-m-d H:i:s'));
                            ConfigFilePersistence::persist('app');
                        }
                    }
                }

                if (!$remote) return false;

                $reader = new Reader(storage_path('app/GeoLite2-Country.mmdb'));
                $record = $reader->get($remote);

                switch ($record->country->isoCode) {
                    case "DJ":
                    case "ER":
                    case "ET":

                        $lang = "aa";
                        break;

                    case "AE":
                    case "BH":
                    case "DZ":
                    case "EG":
                    case "IQ":
                    case "JO":
                    case "KW":
                    case "LB":
                    case "LY":
                    case "MA":
                    case "OM":
                    case "QA":
                    case "SA":
                    case "SD":
                    case "SY":
                    case "TN":
                    case "YE":

                        $lang = "ar";
                        break;

                    case "AZ":

                        $lang = "az";
                        break;

                    case "BY":

                        $lang = "be";
                        break;

                    case "BG":

                        $lang = "bg";
                        break;

                    case "BD":

                        $lang = "bn";
                        break;

                    case "BA":

                        $lang = "bs";
                        break;

                    case "CZ":

                        $lang = "cs";
                        break;

                    case "DK":

                        $lang = "da";
                        break;

                    case "AT":
                    case "CH":
                    case "DE":
                    case "LU":

                        $lang = "de";
                        break;

                    case "MV":

                        $lang = "dv";
                        break;

                    case "BT":

                        $lang = "dz";
                        break;

                    case "GR":

                        $lang = "el";
                        break;

                    case "AG":
                    case "AI":
                    case "AQ":
                    case "AS":
                    case "AU":
                    case "BB":
                    case "BW":
                    case "CA":
                    case "GB":
                    case "IE":
                    case "KE":
                    case "NG":
                    case "NZ":
                    case "PH":
                    case "SG":
                    case "US":
                    case "ZA":
                    case "ZM":
                    case "ZW":

                        $lang = "en";
                        break;

                    case "AD":
                    case "AR":
                    case "BO":
                    case "CL":
                    case "CO":
                    case "CR":
                    case "CU":
                    case "DO":
                    case "EC":
                    case "ES":
                    case "GT":
                    case "HN":
                    case "MX":
                    case "NI":
                    case "PA":
                    case "PE":
                    case "PR":
                    case "PY":
                    case "SV":
                    case "UY":
                    case "VE":

                        $lang = "es";
                        break;

                    case "EE":

                        $lang = "et";
                        break;

                    case "IR":

                        $lang = "fa";
                        break;

                    case "FI":

                        $lang = "fi";
                        break;

                    case "FO":

                        $lang = "fo";
                        break;

                    case "BE":
                    case "FR":
                    case "SN":

                        $lang = "fr";
                        break;

                    case "IL":

                        $lang = "he";
                        break;

                    case "IN":

                        $lang = "hi";
                        break;

                    case "HR":

                        $lang = "hr";
                        break;

                    case "HT":

                        $lang = "ht";
                        break;

                    case "HU":

                        $lang = "hu";
                        break;

                    case "AM":

                        $lang = "hy";
                        break;

                    case "ID":

                        $lang = "id";
                        break;

                    case "IS":

                        $lang = "is";
                        break;

                    case "IT":

                        $lang = "it";
                        break;

                    case "JP":

                        $lang = "ja";
                        break;

                    case "GE":

                        $lang = "ka";
                        break;

                    case "KZ":

                        $lang = "kk";
                        break;

                    case "GL":

                        $lang = "kl";
                        break;

                    case "KH":

                        $lang = "km";
                        break;

                    case "KR":

                        $lang = "ko";
                        break;

                    case "KG":

                        $lang = "ky";
                        break;

                    case "UG":

                        $lang = "lg";
                        break;

                    case "LA":

                        $lang = "lo";
                        break;

                    case "LT":

                        $lang = "lt";
                        break;

                    case "LV":

                        $lang = "lv";
                        break;

                    case "MG":

                        $lang = "mg";
                        break;

                    case "MK":

                        $lang = "mk";
                        break;

                    case "MN":

                        $lang = "mn";
                        break;

                    case "MY":

                        $lang = "ms";
                        break;

                    case "MT":

                        $lang = "mt";
                        break;

                    case "MM":

                        $lang = "my";
                        break;

                    case "NP":

                        $lang = "ne";
                        break;

                    case "AW":
                    case "NL":

                        $lang = "nl";
                        break;

                    case "NO":

                        $lang = "no";
                        break;

                    case "PL":

                        $lang = "pl";
                        break;

                    case "AF":

                        $lang = "ps";
                        break;

                    case "AO":
                    case "BR":
                    case "PT":

                        $lang = "pt";
                        break;

                    case "RO":

                        $lang = "ro";
                        break;

                    case "RU":
                    case "UA":

                        $lang = "ru";
                        break;

                    case "RW":

                        $lang = "rw";
                        break;

                    case "AX":

                        $lang = "se";
                        break;

                    case "SK":

                        $lang = "sk";
                        break;

                    case "SI":

                        $lang = "sl";
                        break;

                    case "SO":

                        $lang = "so";
                        break;

                    case "AL":

                        $lang = "sq";
                        break;

                    case "ME":
                    case "RS":

                        $lang = "sr";
                        break;

                    case "SE":

                        $lang = "sv";
                        break;

                    case "TZ":

                        $lang = "sw";
                        break;

                    case "LK":

                        $lang = "ta";
                        break;

                    case "TJ":

                        $lang = "tg";
                        break;

                    case "TH":

                        $lang = "th";
                        break;

                    case "TM":

                        $lang = "tk";
                        break;

                    case "CY":
                    case "TR":

                        $lang = "tr";
                        break;

                    case "PK":

                        $lang = "ur";
                        break;

                    case "UZ":

                        $lang = "uz";
                        break;

                    case "VN":

                        $lang = "vi";
                        break;

                    case "CN":
                    case "HK":
                    case "TW":

                        $lang = "zh";
                        break;

                    default:
                        break;
                }

            } catch (\Exception $e) {
                $lang = false;
            }
            self::$lang = $lang;
        } else {
            // @todo get from env
        }
        return self::$lang;
    }

    /**
     * @param MainModel $model
     */
    public static function manageData($action, &$model)
    {
        if (is_int(stripos($action, 'saving')) || is_int(stripos($action, 'created')) || is_int(stripos($action, 'updated'))) {
            self::manageSaveData($model[0]);
        } else if (is_int(stripos($action, 'retrieved'))) {
            self::manageFetchData($model[0]);
        } else if (is_int(stripos($action, 'deleted'))) {
            self::manageDeleteData($model[0]);
        }
    }

    /**
     * @param MainModel $model
     */
    private static function manageSaveData(&$model)
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
                    if (!in_array($lang, $languages)) $languages[] = $lang;
                    if (!in_array($field, $fields)) $fields[] = $field;
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
    private static function manageFetchData(&$model)
    {
        if (!$model->translationsLoaded()) {
            $model->refreshLang();
        }
    }

    /**
     * @param MainModel $model
     */
    private static function manageDeleteData(&$model)
    {
        $class = str_replace('\\', '/', get_class($model));
        if ($model->getSoftDeleteAttribute()) {
            DB::table('translates')
                ->where('object_id', $model->id)
                ->where('object_class', $class)->delete();
        }
    }


}
