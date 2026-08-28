const mix = require('laravel-mix');
const webpack = require('webpack');

mix.options({
    fileLoaderDirs: {
        fonts: './mathmpr.com/fonts'
    },
    hmrOptions: {
        port: 3001
    },
    uglify: true,
});

mix.webpackConfig({
    experiments: {
        topLevelAwait: true
    }
})

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

mix.browserSync({
    proxy: process.env.BROWSERSYNC_PROXY || '127.0.0.1:8000',
    host: process.env.BROWSERSYNC_HOST || '0.0.0.0',
    port: Number(process.env.BROWSERSYNC_PORT || 3000),
    ui: {
        port: Number(process.env.BROWSERSYNC_UI_PORT || 3001)
    },
    open: false,
    notify: false
});

let types = {
    frontend: {
        files: [
            "frontend.scss",
            "frontend/home.scss",
            "frontend/single.scss"
        ]
    },
    backend: {
        files: [
            "backend.scss",
            "backend/dashboard.scss",
            "backend/login.scss"
        ]
    }
}

mix.js('resources/assets/js/frontend/app.js', 'public/js/frontend');
mix.js('resources/assets/js/backend/app.js', 'public/js/backend');
for (let type in types) {
    let files = types[type].files;
    files.forEach((file) => {
        let path = file.split('/');
        path.pop();
        if (path.length > 0) {
            mix.sass(`resources/assets/css/${file}`, `public/css/${path.join('/')}/`);
        } else {
            mix.sass(`resources/assets/css/${file}`, `public/css/`);
        }
    });
}

mix.copyDirectory('resources/assets/images/', 'public/images');

mix.minify('public/js/frontend/app.js');
mix.minify('public/js/backend/app.js');
