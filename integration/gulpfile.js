var gulp = require('gulp');
var bower = require('gulp-bower');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');
var mainBowerFiles = require('main-bower-files');
var watch = require('gulp-watch');

gulp.task('default', [
    'mv-bower',
    'less'
]);

// Instalation ou update bower
gulp.task('bower', function() {
    return bower({ cmd: 'update'})
        .pipe(gulp.dest('bower_components'));
});

// Mv des components bower
gulp.task('mv-bower', ['bower'], function () {
    return gulp.src(mainBowerFiles(), {
            base: 'bower_components'
        })
        .pipe(gulp.dest('../web/lib'));
});

// Compilation du fichier CSS
gulp.task('less', function() {
    return gulp.src('./less/bootstrap.less')
        .pipe(less())
        .pipe(minifyCss())
        .pipe(gulp.dest('../web/lib/bootstrap/dist/css'));
});

// Observation des modifications
gulp.task('watch', function () {
    gulp.watch(['./less/bootstrap.less', './less/style.less', './less/mixins.less', './less/variables.less'], ['less']);
});
