var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var minify = require('gulp-minify');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var jsFiles = ['js/main.js', 'js/jquery.cPanorama.js'];
var jsConcat = ['js/jquery.min.js', 'js/jquery.parallax.min.js','js/main.min.js','js/jquery.cPanorama.min.js'];

// Static Server + watching scss/html files
gulp.task('serve', ['sass'], function () {

    /*browserSync.init({
        server: "./"
    });*/

    gulp.watch("css/*.scss", ['sass']);
    /*gulp.watch(jsFiles, ['compress_js','concat_js']);*/
    /*gulp.watch(['*.html', '*.php', 'js/*.js']).on('change', browserSync.reload);*/
});

// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', function () {
    return gulp.src("css/*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer({browsers: ['last 5 versions'], cascade: false}))
        .pipe(sourcemaps.write(''))
        .pipe(gulp.dest('css'));
        /*.pipe(browserSync.stream());*/
});

gulp.task('concat_js', function() {
    return gulp.src(jsConcat)
        .pipe(concat('all.js'))
        .pipe(gulp.dest('js/'));
});

gulp.task('compress_js', function () {
    gulp.src(jsFiles)
        .pipe(minify({
            ext: {
                src: '.js',
                min: '.min.js'
            },
            exclude: ['tasks'],
            ignoreFiles: ['*.combo.js', '*.min.js']
        }))
        .pipe(gulp.dest('js'))
});

gulp.task('default', ['serve']);