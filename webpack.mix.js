let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
   .copy('resources/js/lib', 'public/js')
   .copy('resources/img', 'public/img')
   .copy('resources/favicon*', 'public')
   .copy('resources/index.php', 'public')
   .copy('resources/htaccess', 'public/.htaccess')
   .copy('resources/robots.txt', 'public')
   .less('resources/less/app.less', 'public/css')
   .js('resources/js/app.js', 'public/js')
   .webpackConfig({
        resolve: {
            modules: [
                'node_modules'
            ],
            alias: {
                'vue$': mix.inProduction() ? 'vue/dist/vue.min.js' : 'vue/dist/vue.js',

            }
        }
   });

if (mix.inProduction()) {
    mix.version();
}