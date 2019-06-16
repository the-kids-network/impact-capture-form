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
   .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
   .copy('node_modules/sweetalert/dist/sweetalert.css', 'public/css/sweetalert.css')
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
                'vue$': 'vue/dist/vue.js'
            }
        }
   });