let mix = require('laravel-mix');
const del = require('del');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.options({
   processCssUrls: false,
});

mix.js('resources/assets/javascripts/compile.js', 'resources/assets/javascripts/compile.min.js')
   .combine([
      'resources/assets/vendor/modernizr/modernizr.min.js',
      'resources/assets/javascripts/compile.min.js',
      'resources/assets/vendor/jquery/arrow-table.min.js',
      'resources/assets/vendor/chosen/chosen.jquery.min.js',
      'resources/assets/vendor/pnotify/pnotify.custom.js',
      'resources/assets/javascripts/theme.custom.js',
   ], 'public/backend/default/javascripts/main.js');

mix.combine(['resources/assets/javascripts/theme.js', 'resources/assets/javascripts/theme.init.js'],'public/backend/default/javascripts/script.js');   

mix.combine([
   'resources/assets/vendor/trumbowyg/trumbowyg.min.js', 
   'resources/assets/vendor/trumbowyg/trumbowyg.resizimg.min.js',
   'resources/assets/vendor/trumbowyg/trumbowyg.upload.min.js',
   'resources/assets/vendor/flatpickr/flatpickr.min.js',
   'resources/assets/vendor/mask/cleave.min.js',
   'resources/assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js',
],'public/backend/default/javascripts/pjax.js');   

mix.sass('resources/assets/stylesheets/theme.scss', 'public/backend/default/stylesheets/theme.css');

