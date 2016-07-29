var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix
            .sass('main.scss')
            .scripts([
                './bower_components/jquery/dist/jquery.js',
                './bower_components/underscore/underscore.js',
                './bower_components/backbone/backbone.js'
                ], "public/js/vendor.js")
            .scripts([
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/affix.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/alert.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/dropdown.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/tooltip.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/modal.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/transition.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/button.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/popover.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/carousel.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/scrollspy.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/collapse.js',
                './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/tab.js'
            ], "public/js/plugins.js")
            .scripts(['resources/assets/js/**/*.js'], "public/js/app.js")
            .copy("resources/assets/images", "public/images")
            .copy("resources/assets/fonts", "public/fonts")
    ;

});
