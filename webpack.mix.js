const mix = require('laravel-mix');

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

mix.scripts([
    'public/assets/js/jquery-2.2.4.js',
    'public/assets/js/jquery-ui.min.js',
    'public/assets/js/popper.min.js',
    'public/assets/js/bootstrap.min.js',
    'public/assets/js/moment/moment.min.js',
    'public/assets/js/bootstrap-datetimepicker.min.js',
    'public/assets/js/datatables.min.js',
    'public/assets/js/slick.min.js',
    'public/assets/js/custom.js',
    'resources/js/custom.js',
    'resources/js/slide.js',
], 'public/js/all.js').version();
   // .sass('resources/sass/app.scss', 'public/css');
