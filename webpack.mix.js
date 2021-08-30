const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

mix.js('resources/js/guest.js', 'public/js');
mix.js('resources/js/common.js', 'public/js');

/* select2 */

mix.copy( 'node_modules/select2/dist/css/select2.min.css', 'public/vendor/select2/');
mix.copy( 'node_modules/select2/dist/js/i18n/ru.js', 'public/vendor/select2/');
mix.copy( 'node_modules/select2/dist/js/select2.min.js', 'public/vendor/select2/');

/* flatpickr datepicker*/

mix.copy( 'node_modules/flatpickr/dist/flatpickr.min.js', 'public/vendor/flatpickr/')
mix.copy( 'node_modules/flatpickr/dist/l10n/ru.js', 'public/vendor/flatpickr/')
mix.copy( 'node_modules/flatpickr/dist/flatpickr.min.css', 'public/vendor/flatpickr/')


if (mix.inProduction()) {
    mix.version();
}
