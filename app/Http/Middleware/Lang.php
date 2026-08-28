<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class Lang
{
    /**
     * @param Request $request
     * @return Application|RedirectResponse|Request|Redirector
     */
    public static function commonHandle(Request &$request)
    {
        $lang = null;
        $urlLang = $request->url();
        $urlLang = explode('//', $urlLang);
        $urlLang = explode('/', end($urlLang));
        $isApiCall = false;
        $key = 1;
        if (count($urlLang) > $key && $urlLang[$key] === 'api') {
            $key++;
            $isApiCall = true;
        }
        if (count($urlLang) > $key && in_array($urlLang[$key], config('app.available_locales'))) {
            $lang = $urlLang[$key];
        }
        if (!$lang) {
            $lang = App::getLocale();
            array_shift($urlLang);
            if ($isApiCall) {
                array_shift($urlLang);
            }
            return redirect('/'
                . ($isApiCall ? 'api/' : '')
                . join('/', array_merge([$lang], $urlLang)));
        }
        return $request;
    }

    public function handle(Request &$request, Closure $next)
    {
        $request = Lang::commonHandle($request);
        if (get_class($request) != Request::class) {
            return $request;
        }
        return $next($request);
    }
}
