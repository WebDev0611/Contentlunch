
var gulp = require('gulp'),
		autoprefixer = require('gulp-autoprefixer'),
		minifycss = require('gulp-minify-css'),
		jshint = require('gulp-jshint'),
		uglify = require('gulp-uglify'),
		imagemin = require('gulp-imagemin'),
		rename = require('gulp-rename'),
		clean = require('gulp-clean'),
		concat = require('gulp-concat'),
		notify = require('gulp-notify'),
		cache = require('gulp-cache'),
		livereload = require('gulp-livereload'),
		lr = require('tiny-lr'),
		embedlr = require('gulp-embedlr'),
		server = lr();
var gutil = require('gulp-util');

gulp.task('styles', function() {
	return gulp.src(['src/css/main.css',
					'./bower_components/bootstrap/dist/css/bootstrap.css',
					'./bower_components/bootstrap/dist/css/bootstrap-theme.css.css'])
    .pipe(gulp.dest('public/assets/css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('public/assets/css'))
    .pipe(livereload(server))
    .pipe(notify({ message: 'Styles task complete' }));
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
    .pipe(livereload(server))
    .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('fonts-eot', function () {
	return gulp.src('./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.eot')
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server))
		.pipe(notify({ message: 'Font EOT task complete' }));
});

gulp.task('fonts-svg', function () {
	return gulp.src('./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.svg')
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server))
		.pipe(notify({ message: 'Font SVG task complete' }));
});

gulp.task('fonts-ttf', function () {
	return gulp.src('./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf')
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server))
		.pipe(notify({ message: 'Font TTF task complete' }));
});

gulp.task('fonts-woff', function () {
	return gulp.src('./bower_components/bootstrap/dist/fonts/glyphicons-halflings-regular.woff')
		.pipe(gulp.dest('./public/assets/fonts'))
		.pipe(livereload(server))
		.pipe(notify({ message: 'Font WOFF task complete' }));
});

gulp.task('views', function () {
	return gulp.src('src/views/**/*.html')
	//	.pipe(embedlr())
		.pipe(gulp.dest('public/assets/views'))
		.pipe(livereload(server));
		//.pipe(notify({ message: 'Views task complete' }));
});

gulp.task('images', function() {
	return gulp.src('src/images/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('public/assets/images'))
    .pipe(livereload(server))
    .pipe(notify({ message: 'Images task complete' }));
});

gulp.task('clean', function() {
	return gulp.src(['public/assets/css', 'public/assets/js', 'public/assets/images', 'public/assets/views'], {read: false})
		.pipe(clean());
});

// Run clean task first as dependency
gulp.task('default', ['clean'], function() {
	gulp.start('styles', 'scripts', 'views', 'images', 'fonts-eot', 'fonts-svg', 'fonts-ttf', 'fonts-woff')
});

gulp.task('watch', function() {
	// Listen on port 35729
	server.listen(35729, function(err) {
		if (err) {
			return console.log(err);
		}
		gulp.watch('src/css/**/*.scss', ['styles']);
		gulp.watch('src/js/**/*.js', ['scripts']);
		gulp.watch('src/views/**/*.html', ['views']);
		gulp.watch('src/images/**/*', ['images']);
	});
});
