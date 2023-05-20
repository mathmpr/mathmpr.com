<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
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
            $previous = Str::random(6);
            $request->session()->put($previous, [
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'query' => $request->query->all(),
                'cookies' => $request->query->all(),
                'files' => $request->allFiles(),
                'server' => $request->server->all(),
                'content' => $request->all()
            ]);
            array_shift($urlLang);
            if ($isApiCall) {
                array_shift($urlLang);
            }
            $redirectUrl = '/'
                . ($isApiCall ? 'api/' : '')
                . join('/', array_merge([$lang], $urlLang));
            if (stripos($redirectUrl, '?') !== false) {
                $redirectUrl .= '&_l=' . $previous;
            } else {
                $redirectUrl .= '?_l=' . $previous;
            }
            return redirect($redirectUrl);
        }
        return $request;
    }

    public function handle(Request &$request, Closure $next)
    {
        $request = Lang::commonHandle($request);
        if (get_class($request) != Request::class) {
            return $request;
        }
        if ($request->get('_l') && $previous = $request->session()->get($request->get('_l'))) {
            $request->setMethod($previous['method']);
            $request->query->replace($previous['query']);
            $request->cookies->replace($previous['cookies']);
            $request->files->replace($previous['files']);
            $request->server->replace($previous['server']);
            $request->headers->replace($previous['headers']);
            $request->replace($previous['content']);
        }
        return $next($request);
    }
}
