<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\Lang;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Lang::setLang();

        //'retrieved', 'creating', 'created', 'updating', 'updated','saving', 'saved', 'restoring', 'restored', 'replicating', 'deleting', 'deleted', 'forceDeleted'

        Event::listen(['eloquent.updated: *', 'eloquent.created: *', 'eloquent.saving: *', 'eloquent.loaded: *', 'eloquent.deleted: *', 'eloquent.retrieved: *'], function ($action, &$model) {
            $reflection = new \ReflectionClass($model[0]);
            $location = explode('/', str_replace([base_path(), '\\'], ['', '/'], $reflection->getFileName()));
            array_pop($location);
            $location = join('/', $location);
            if (str_contains($location, 'app/Models')) {
                Lang::manageData($action, $model);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Lang::$lang = 'pt';
        app('translator')->setLocale(Lang::$lang);
    }
}
