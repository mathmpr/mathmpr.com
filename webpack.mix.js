const mix = require('laravel-mix');
const webpack = require('webpack');

mix.options({
    fileLoaderDirs: {
        fonts: './mathmpr.com/fonts'
    },
    hmrOptions: {
        port: 3001
    },

});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.browserSync('127.0.0.1:8000');

mix.js('resources/assets/js/frontend/app.js', 'public/js/frontend')
    .sass('resources/assets/css/frontend.scss', 'public/css')
    .sass('resources/assets/css/frontend/home.scss', 'public/css/frontend/')
    .sass('resources/assets/css/frontend/single.scss', 'public/css/frontend/');


mix.js('resources/assets/js/backend/app.js', 'public/js/backend')
    .sass('resources/assets/css/backend.scss', 'public/css')
    .sass('resources/assets/css/backend/dashboard.scss', 'public/css/backend/')
    .sass('resources/assets/css/backend/login.scss', 'public/css/backend/');

mix.copyDirectory('resources/assets/images/', 'public/images');

mix.minify('public/js/frontend/app.js');
mix.minify('public/js/backend/app.js');
