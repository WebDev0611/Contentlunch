
var gulp = require('gulp'),
		autoprefixer = require('gulp-autoprefixer'),
		minifycss = require('gulp-minify-css'),
		jshint = require('gulp-jshint'),
		uglify = require('gulp-uglify'),
		imagemin = require('gulp-imagemin'),
		rename = require('gulp-rename'),
		clean = require('gulp-clean'),
		less = require('gulp-less'),
		path = require('path'),
		//concat = require('gulp-concat-sourcemap'),
		concat = require('gulp-concat'),
		notify = require('gulp-notify'),
		cache = require('gulp-cache'),
		livereload = require('gulp-livereload'),
		lr = require('tiny-lr'),
		embedlr = require('gulp-embedlr'),
		autoprefixer = require('gulp-autoprefixer'),
		server = lr();
var gutil = require('gulp-util');

gulp.task('styles-bootstrap', function () {
	return gulp.src([
			'./bower_components/bootstrap/dist/css/bootstrap.css',
			'./bower_components/bootstrap/dist/css/bootstrap-theme.css'
	])
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('map-bootstrap', function () {
	return gulp.src([
			'./bower_components/bootstrap/dist/css/bootstrap.css.map',
			'./bower_components/bootstrap/dist/css/bootstrap-theme.css.map'
	])
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('bootstrap-components-css', function () {
	return gulp.src([
			'./bower_components/pnotify/jquery.pnotify.default.css',
			'./bower_components/ladda/dist/ladda.min.css',
			'./bower_components/ladda/dist/spin.min.css',
			'./bower_components/font-awesome/css/font-awesome.css',
			'./bower_components/select2/select2.css',
	])
		.pipe(concat('bootstrap-components.css'))
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('styles-angular-ui', function () {
	return gulp.src([
		'./bower_components/angular-ui/build/angular-ui.css',
		'./bower_components/fullcalendar/fullcalendar.css',
	])
	.pipe(concat('angular-ui.css'))
	.pipe(gulp.dest('public/assets/css'))
	.pipe(rename({ suffix: '.min' }))
	.pipe(minifycss())
	.pipe(gulp.dest('public/assets/css'))
	.pipe(livereload(server));
});

gulp.task('less', function () {
	return gulp.src(['src/css/main.less'])
		.pipe(less({
			paths: [path.join(__dirname, 'less', 'includes')]
		}))
		.pipe(autoprefixer())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('tinymce', function () {
  var dir = './bower_components/tinymce/';
  gulp.src([
    dir + 'tinymce.min.js', 
    dir + 'plugins/**/*', 
    dir + 'skins/**/*', 
    dir + 'themes/**/*'
  ], { base: dir })
    .pipe(gulp.dest('./public/assets/js/tinymce'))
    .pipe(livereload(server));
});

gulp.task('tinymce-scripts', function() {
	gulp.src(['./bower_components/tinymce/js/tinymce/**/*.js'])
		.pipe(gulp.dest('./public/assets/js'))
		.pipe(livereload(server));
});

gulp.task('tinymce-css', function () {
	gulp.src([
			'./bower_components/tinymce/js/tinymce/skins/lightgray/skin.min.css',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/content.min.css'
	])
		.pipe(gulp.dest('./public/assets/js/skins/lightgray'))
		.pipe(livereload(server));
});

gulp.task('tinymce-fonts', function () {
	gulp.src([
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce.svg',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce.eot',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce.ttf',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce.woff',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce.eot',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce-small.svg',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce-small.eot',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce-small.ttf',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce-small.woff',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/fonts/tinymce-small.eot'
	])
		.pipe(gulp.dest('./public/assets/js/skins/lightgray/fonts'))
		.pipe(livereload(server));
});

gulp.task('tinymce-images', function () {
	gulp.src([
			'./bower_components/tinymce/js/tinymce/skins/lightgray/img/anchor.gif',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/img/loader.gif',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/img/object.gif',
			'./bower_components/tinymce/js/tinymce/skins/lightgray/img/trans.gif'
	])
		.pipe(gulp.dest('./public/assets/js/skins/lightgray/img'))
		.pipe(livereload(server));
});

gulp.task('scripts', function() {
	gulp.src([
			'./bower_components/lodash/dist/lodash.js',
			'./bower_components/jquery/dist/jquery.js',
			'./bower_components/jquery-ui/ui/jquery-ui.js',
			'./bower_components/bootstrap/dist/js/bootstrap.js',
			'./bower_components/select2/select2.js',
			'./bower_components/jquery.dotdotdot/src/js/jquery.dotdotdot.js',
			'./bower_components/ng-file-upload/angular-file-upload-html5-shim.js',
			'./bower_components/ng-file-upload/angular-file-upload-shim.js',
			'./bower_components/angular/angular.js',
			'./bower_components/angular-route/angular-route.js',
			'./bower_components/angular-resource/angular-resource.js',
			'./bower_components/angular-sanitize/angular-sanitize.js',
			'./bower_components/angular-ui/build/angular-ui.js',
			'./bower_components/angular-bootstrap/ui-bootstrap-tpls.js',
			'./bower_components/angular-ui-select2/src/select2.js',
			'./bower_components/angular-ui-tinymce/src/tinymce.js',
			'./bower_components/momentjs/min/moment-with-langs.js',
			'./bower_components/pnotify/jquery.pnotify.js',
			'./bower_components/ladda/js/spin.js',
			'./bower_components/ladda/js/ladda.js',
			'./bower_components/ng-file-upload/angular-file-upload.js',
			// './bower_components/angular-ui-calendar/src/calendar.js',
			'./bower_components/fullcalendar/fullcalendar.js',
			'./bower_components/restangular/dist/restangular.js',
			'./bower_components/checklist-model/checklist-model.js',
			'./src/js/lib/angular.wijmo.3.20142.45.min.js'
			// './bower_components/fullcalendar/gcal.js', // only needed if we do gcal integration
		])
		.pipe(concat('build.js'))
		//.pipe(concat('build.js', {
		//	// sourceRoot: '/assets/src',
		//	sourcesContent: true
		//})).on('error', gutil.log)
		.pipe(gulp.dest('./public/assets/js'));
	return gulp.src(['src/js/app.js', 'src/js/**/*.js'])
		// .pipe(jshint('.jshintrc'))
		// .pipe(jshint.reporter('jshint-stylish'))
		.pipe(concat('app.js'))
		//.pipe(concat('app.js', {
		//	// sourceRoot: '/assets/src',
		//	sourcesContent: true
		//})).on('error', gutil.log)
		.pipe(gulp.dest('./public/assets/js'))
		.pipe(livereload(server));
});

gulp.task('fonts-eot', function() {
	return gulp.src([
			'./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.eot',
			'./bower_components/font-awesome/fonts/fontawesome-webfont.eot',
			'./src/fonts/**/*.eot'
		])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-svg', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.svg',
			'./bower_components/font-awesome/fonts/fontawesome-webfont.svg',
			'./src/fonts/**/*.svg'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-ttf', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf',
			'./bower_components/font-awesome/fonts/fontawesome-webfont.ttf',
			'./src/fonts/**/*.ttf'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-woff', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.woff',
			'./bower_components/font-awesome/fonts/fontawesome-webfont.woff',
			'./src/fonts/**/*.woff'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-otf', function () {
	return gulp.src(['./bower_components/font-awesome/fonts/FontAwesome.otf'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('views', function () {
	return gulp.src('src/views/**/*.html')
	//	.pipe(embedlr())
		.pipe(gulp.dest('public/assets/views'))
		.pipe(livereload(server));
});

gulp.task('images', function() {
	// select2 is looking in the css folder, so these are separate
	gulp.src([
			'./bower_components/select2/select2.png',
			'./bower_components/select2/select2-spinner.gif',
			'./bower_components/select2/select2x2.png'
		])
		.pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));

	return gulp.src([
			'src/images/**/*',
		])
		.pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
		.pipe(gulp.dest('public/assets/images'))
		.pipe(livereload(server));
});

gulp.task('clean', function() {
	return gulp.src(['public/assets/css', 'public/assets/js', 'public/assets/images', 'public/assets/views'], {read: false})
		.pipe(clean());
});

gulp.task('watch', function() {
	// Listen on port 35729
	server.listen(35729, function(err) {
		if (err) {
			return console.log(err);
		}
		gulp.watch('src/css/**/*.css', ['styles-bootstrap']).on('change', function (e) { console.log('File "' + e.path + '" changed; CSS task complete'); });
		gulp.watch('src/css/**/*.map', ['map-bootstrap']).on('change', function (e) { console.log('File "' + e.path + '" changed; MAP task complete'); });
		gulp.watch('src/fonts/**/*.eot', ['fonts-eot']).on('change', function (e) { console.log('File "' + e.path + '" changed; FONT-EOT task complete'); });
		gulp.watch('src/fonts/**/*.svg', ['fonts-svg']).on('change', function (e) { console.log('File "' + e.path + '" changed; FONT-SVG task complete'); });
		gulp.watch('src/fonts/**/*.ttf', ['fonts-ttf']).on('change', function (e) { console.log('File "' + e.path + '" changed; FONT-TTF task complete'); });
		gulp.watch('src/fonts/**/*.woff', ['fonts-woff']).on('change', function (e) { console.log('File "' + e.path + '" changed; FONT-WOFF task complete'); });
		gulp.watch('src/css/**/*.less', ['less']).on('change', function (e) { console.log('File "' + e.path + '" changed; LESS task complete'); });
		gulp.watch('src/js/**/*.js', ['scripts']).on('change', function (e) { console.log('File "' + e.path + '" changed; SCRIPTS task complete'); });
		gulp.watch('src/views/**/*.html', ['views']).on('change', function (e) { console.log('File "' + e.path + '" changed; VIEW task complete'); });
		gulp.watch('src/images/**/*', ['images']).on('change', function (e) { console.log('File "' + e.path + '" changed; IMAGE task complete'); });
	});
});

// Run clean task first as dependency
gulp.task('default', ['clean'], function () {
	gulp.start('styles-bootstrap', 'map-bootstrap', 'bootstrap-components-css', 'styles-angular-ui', 'less', 'tinymce', 'scripts', 'views', 'images', 'fonts-eot', 'fonts-svg', 'fonts-ttf', 'fonts-woff', 'fonts-otf');
});
