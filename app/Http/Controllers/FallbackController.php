<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Lang;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class FallbackController extends Controller
{
    public static int $runTimes = 0;

    public function handle(Request $request)
    {
        return $this->firstNotNullValue($this->handleLang($request), $this->handleNotFound());
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    private function handleNotFound()
    {
        return response(view('web/404')->render(), 404);
    }

    /**
     * @param Request $request
     * @return bool|Application|RedirectResponse|Request|Redirector
     */
    private function handleLang(Request $request)
    {
        $request = Lang::commonHandle($request);
        if (get_class($request) != Request::class) {
            return $request;
        }
        if (self::$runTimes === 0) {
            self::$runTimes++;
            return app('router')->dispatch($request);
        }
        return false;
    }
}
