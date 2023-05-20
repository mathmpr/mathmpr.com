<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return View|Factory
     */
    static function autoDiscoverView(string $view = '', array $data = [], array $mergeData = []): Factory|View
    {
        $exception = new Exception();
        $path = explode('Controllers', $exception->getTrace()[0]['file']);
        $path = explode('/', end($path));
        array_pop($path);
        $path = strtolower(
                join('/', $path)
            ) . '/';
        if (file_exists(Config::get('view.paths')[0] . $path . $view . '.blade.php')) {
            $path = explode('/', $path);
            array_shift($path);
            $path = join('/', $path) . $view;
        } else {
            $path = $view;
        }
        return view($path, $data, $mergeData);
    }

    protected function firstNotNullValue()
    {
        foreach (func_get_args() as $arg) {
            if ($arg) {
                return $arg;
            }
        }
        return false;
    }
}
