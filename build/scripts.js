var gulp        = require('gulp');
var babelify    = require('babelify');
var stringify   = require('stringify');
var browserify  = require('browserify');
var notify      = require('gulp-notify');
var uglify      = require('gulp-uglify');
var buffer      = require('vinyl-buffer');
var sourcemaps  = require('gulp-sourcemaps');
var source      = require('vinyl-source-stream');

//
// Build javascript
//
module.exports = function() {
    browserify('./assets/js/main.js', { debug: true, extensions: ['.js'] })
        .transform(stringify({
            extensions: ['.htm'],
            minify: true,
            minifier: { extensions: ['.htm'] },
        }))
        .transform(babelify, {presets: ["es2015"]})
        .bundle()
        .on('error', notify.onError({
            title: "Compile Error",
            message: "<%= error.message %>"
        }))
        .pipe(source('webhooks.min.js'))
        .pipe(buffer())
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./assets/compiled'))
        .pipe(notify({ message: 'Javascript compiled!', onLast: true }));
};
