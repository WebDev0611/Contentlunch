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
                './bower_components/backbone/backbone.js',
                './bower_components/moment/moment.js',
                './bower_components/tinymce/tinymce.min.js',
                ], "public/js/vendor.js")
            .scripts(['resources/assets/js/content/*.js','resources/assets/js/helpers.js'], "public/js/app.js")
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

            /* content scripts and main app */
            .scripts(['resources/assets/js/content/*.js','resources/assets/js/helpers.js'], "public/js/app.js")

            /* plan module scripts */
            /* trends */
            .scripts(['resources/assets/js/plan/topic.js'],"public/js/topic.js")
            .scripts([
                'resources/assets/js/models/trends.js',
                'resources/assets/js/views/trends.js',
                'resources/assets/js/collections/trends.js',
                'resources/assets/js/plan/trends.js'
                ],"public/js/trends.js")

            /* ideas */
            .scripts([
                'resources/assets/js/models/ideas.js',
                'resources/assets/js/collections/ideas.js',
                'resources/assets/js/views/ideas.js',
                'resources/assets/js/plan/ideas.js'
                ],"public/js/ideas.js")

            .scripts(['resources/assets/js/plan/editor.js'],"public/js/idea_editor.js")

            /* influencers scripts */
            .scripts(['resources/assets/js/collaborate/influencers.js'],"public/js/influencers.js")

            /* campaign scripts */
            .scripts([
                'resources/assets/js/models/campaigns.js',
                'resources/assets/js/collections/campaigns.js',
                'resources/assets/js/views/campaign.js',
                'resources/assets/js/campaign/campaign.js'
                ],"public/js/campaign.js")

            /* calendar scripts */
            .scripts(['resources/assets/js/calendar/calendar.js'],"public/js/calendar.js")

            /* home area scripts */
            .scripts([
                'resources/assets/js/models/ideas.js',
                'resources/assets/js/models/campaigns.js',
                'resources/assets/js/collections/ideas.js',
                'resources/assets/js/collections/campaigns.js',
                'resources/assets/js/views/ideas.js',
                'resources/assets/js/dashboard/dashboard.js'
                ],"public/js/dashboard.js")

            .scripts([
                'resources/assets/js/models/ideas.js',
                'resources/assets/js/models/campaigns.js',
                'resources/assets/js/collections/ideas.js',
                'resources/assets/js/collections/campaigns.js',
                'resources/assets/js/views/ideas.js',
                'resources/assets/js/dashboard/performance.js'
                ],"public/js/performance.js")

            /* twitter scripts */
            .scripts([ 'resources/assets/js/collaborate/twitter.js' ], 'public/js/twitter.js')

            /* tiny mce assets */
            .copy('./bower_components/tinymce/themes', "public/js/themes")
            .copy('./bower_components/tinymce/skins', "public/js/skins")
            .copy('./bower_components/tinymce/plugins', "public/js/plugins")

            .copy("resources/assets/images", "public/images")
            .copy("resources/assets/fonts", "public/fonts")
    ;

});
