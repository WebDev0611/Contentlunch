/*
 | Disables elixir notifications
 */
process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

require('laravel-elixir-livereload');
require('laravel-elixir-vueify');

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
        .sass('main.scss', 'public/css/main.css')
        .version([
            'public/css/main.css',
            'public/css/vendor.css',
            'public/js/vendor.js',
            'public/js/vendor.js',
            'public/js/vue-app.js',
            'public/js/vue.js',
            'public/js/plugins.js',
            'public/js/app.js',
            'public/js/topic.js',
            'public/js/trends.js',
            'public/js/avatar_view.js',
            'public/js/onboarding.js',
            'public/js/content.js',
            'public/js/content-create.js',
            'public/js/content-custom.js',
            'public/js/ideas.js',
            'public/js/idea_editor.js',
            'public/js/campaign.js',
            'public/js/calendar.js',
            'public/js/campaign-calendar.js',
            'public/js/calendar-helpers.js',
            'public/js/dashboard.js',
            'public/js/performance.js',
            'public/js/content-settings.js',
            'public/js/account-settings.js',
            'public/js/subscriptions.js',
            'public/js/task_editor.js',
            'public/js/twitter.js'
        ], 'public')

        .combine([
            './resources/assets/admin_theme/font-awesome/css/font-awesome.min.css',
        ], 'public/css/vendor.css')

        .sass('admin/admin.scss', 'public/css/admin.css')

        .scripts([
            './bower_components/jquery/dist/jquery.min.js',
            './bower_components/jquery-sticky/jquery.sticky.js',
            './bower_components/jquery.cookie/jquery.cookie.js',
            './bower_components/underscore/underscore.js',
            './bower_components/backbone/backbone.js',
            './bower_components/moment/moment.js',
            './bower_components/tinymce/tinymce.min.js',
            './bower_components/dropzone/dist/dropzone.js',
            './bower_components/sweetalert2/dist/sweetalert2.min.js',
            './bower_components/fastselect/dist/fastselect.standalone.min.js'
        ], "public/js/vendor.js")

        .browserify('vue-app.js')

        .scripts([
            './bower_components/vue/dist/vue.js'
        ], "public/js/vue.js")

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
            './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap/tab.js',
            'resources/assets/js/plugins/*.js',
        ], "public/js/plugins.js")

        /* content scripts and main app */
        .babel([
            'resources/assets/js/noautodiscover.js',
            'resources/assets/js/models/**/*.js',
            'resources/assets/js/collections/**/*.js',
            'resources/assets/js/views/**/*.js',
            'resources/assets/js/helpers.js'
        ], "public/js/app.js")

        /* plan module scripts */
        /* trends */
        .babel('resources/assets/js/plan/topic.js', "public/js/topic.js")
        .babel('resources/assets/js/plan/trends.js', "public/js/trends.js")

        /* Avatar View */
        .babel('resources/assets/js/views/avatar.js', 'public/js/avatar_view.js')

        /* Onboarding */
        .babel('resources/assets/js/onboarding/*.js', 'public/js/onboarding.js')

        /* Content Editor */
        .babel('resources/assets/js/content/*.js', 'public/js/content.js')
        .babel('resources/assets/js/content/create/*.js', 'public/js/content-create.js')
        .babel('resources/assets/js/content/custom/*.js', 'public/js/content-custom.js')
        // TODO: Make sure the {{ elixir('/js/content-custom.js', null) }} works in templates

        /* ideas */
        .babel('resources/assets/js/plan/ideas.js', "public/js/ideas.js")

        .babel([
            'resources/assets/js/plan/editor.js',
            'resources/assets/js/plan/idea_collaborators.js',
        ], "public/js/idea_editor.js")

        /* campaign scripts */
        .babel('resources/assets/js/campaign/campaign.js',"public/js/campaign.js")

        /* calendar scripts */
        .babel('resources/assets/js/calendar/calendar.js', "public/js/calendar.js")
        // TODO: be sure that "{{ elixir('/js/calendar.js', null) }}" works in templates
        .babel('resources/assets/js/calendar/campaign-calendar.js', "public/js/campaign-calendar.js")
        // TODO: be sure that {{ elixir('/js/campaign-calendar.js', null) }} will work with templates
        .babel('resources/assets/js/calendar/calendar-helpers.js', "public/js/calendar-helpers.js")

        /* home area scripts */
        .babel('resources/assets/js/dashboard/dashboard.js', "public/js/dashboard.js")
        .babel('resources/assets/js/dashboard/performance.js', "public/js/performance.js")
        // TODO: be sure that {{ elixir('/js/performance.js', 'public') }} will work in templates

        /* settings scripts */
        .babel('resources/assets/js/settings/content/*.js', 'public/js/content-settings.js')
        .babel('resources/assets/js/settings/account/*.js', 'public/js/account-settings.js')
        .babel('resources/assets/js/settings/subscriptions/*.js', 'public/js/subscriptions.js')

        /* task editor */
        .babel('resources/assets/js/task/*.js', 'public/js/task_editor.js')

        /* twitter scripts */
        .babel('resources/assets/js/collaborate/twitter.js', 'public/js/twitter.js')
        // TODO: Be sure that {{ elixir('/js/twitter.js', 'public') }} will work with templates

        /* tiny mce assets */
        .copy('./bower_components/tinymce/themes', "public/js/themes")
        .copy('./bower_components/tinymce/skins', "public/js/skins")
        .copy('./bower_components/tinymce/plugins', "public/js/plugins")

        /* SweetAlert2 assets */
        .copy('./bower_components/sweetalert2/dist/sweetalert2.min.css', 'public/css/plugins/sweetalert2/')

        /* Fastselect */
        .copy('./bower_components/fastselect/dist/fastselect.min.css', 'public/css/plugins/fastselect/')

        .copy("resources/assets/images", "public/images")
        .copy("resources/assets/fonts", "public/fonts")
        .copy("resources/assets/downloads", "public/downloads")

        /*
         |--------------------------------------------------------------------------
         | Administrative Dashboard
         |--------------------------------------------------------------------------
         |
         | The following files are used in the administrative dashboard of the app.
         | It uses an existing theme, called Inspinia, to speed up the front-end
         | development and maintain a consistent look throught the dashboard.
         |
         */
        .scripts([

            './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js',
            './bower_components/moment/min/moment.min.js',
            './resources/assets/admin_theme/js/plugins/metisMenu/jquery.metisMenu.js',
            './resources/assets/admin_theme/js/plugins/slimscroll/jquery.slimscroll.min.js',
            './resources/assets/admin_theme/js/plugins/chartJs/Chart.min.js',
            './resources/assets/admin_theme/js/plugins/datapicker/bootstrap-datepicker.js',
            './resources/assets/admin_theme/js/inspinia.js',

        ], 'public/js/admin/admin_vendor.js')

        .combine([

            './resources/assets/admin_theme/font-awesome/css/font-awesome.min.css',
            './resources/assets/admin_theme/css/animate.css',
            './resources/assets/admin_theme/css/plugins/datapicker/datepicker3.css',

        ], 'public/css/admin_vendor.css')

        .copy('./resources/assets/admin_theme/font-awesome/fonts', 'public/fonts')
        .copy('./resources/assets/admin_theme/css/patterns', 'public/css/patterns')
    ;
    mix.livereload()

    .browserSync({
        proxy: 'localhost:3000'
    })
});

