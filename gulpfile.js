
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
		concat = require('gulp-concat'),
		notify = require('gulp-notify'),
		cache = require('gulp-cache'),
		livereload = require('gulp-livereload'),
		lr = require('tiny-lr'),
		embedlr = require('gulp-embedlr'),
		server = lr();
var gutil = require('gulp-util');

gulp.task('styles', function() {
	return gulp.src([
			'./bower_components/bootstrap/dist/css/bootstrap.css',
			'./bower_components/bootstrap/dist/css/bootstrap-theme.css.css'
		])
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('less', function () {
	return gulp.src(['src/css/*.less'])
		.pipe(less({
			paths: [path.join(__dirname, 'less', 'includes')]
		}))
		.pipe(gulp.dest('public/assets/css'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(minifycss())
		.pipe(gulp.dest('public/assets/css'))
		.pipe(livereload(server));
});

gulp.task('scripts', function() {
	gulp.src(['./bower_components/jquery/dist/jquery.js',
				'./bower_components/bootstrap/dist/js/bootstrap.js',
				'./bower_components/angular/angular.js',
				'./bower_components/angular-route/angular-route.js',
				'./bower_components/angular-resource/angular-resource.js'])
    .pipe(concat('build.js'))
    .pipe(gulp.dest('./public/assets/js'));
	return gulp.src(['src/js/app.js', 'src/js/**/*.js'])
		.pipe(jshint('.jshintrc'))
		.pipe(jshint.reporter('jshint-stylish'))
		.pipe(concat('app.js'))
		.pipe(gulp.dest('./public/assets/js'))
		.pipe(livereload(server));
});

gulp.task('fonts-eot', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.eot',
			'./app/assets/fonts/**/*.eot'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-svg', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.svg',
			'./app/assets/fonts/**/*.svg'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-ttf', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf',
			'./app/assets/fonts/**/*.ttf'])
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server));
});

gulp.task('fonts-woff', function () {
	return gulp.src(['./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.woff',
			'./app/assets/fonts/**/*.woff'])
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
	return gulp.src('src/images/**/*')
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
		gulp.watch('src/css/**/*.css', ['styles']).on('change', function (e) { console.log('File "' + e.path + '" changed; CSS task complete'); });
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
	gulp.start('styles', 'less', 'scripts', 'views', 'images', 'fonts-eot', 'fonts-svg', 'fonts-ttf', 'fonts-woff');
});