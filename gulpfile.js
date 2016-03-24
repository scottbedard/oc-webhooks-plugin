var gulp = require('gulp');

//
// Build and watch everything
//
gulp.task('default', [
    'js',
    'scss',
    'js-watch',
    'scss-watch'
]);

//
// Styles
//
var buildScss = require('./build/styles');
gulp.task('scss', buildScss);
gulp.task('scss-watch', () => gulp.watch('./assets/scss/**/*', buildScss));

//
// Javascript
//
var buildJs = require('./build/scripts');
gulp.task('js', buildJs);
gulp.task('js-watch', () => gulp.watch('./assets/js/**/*', buildJs));
