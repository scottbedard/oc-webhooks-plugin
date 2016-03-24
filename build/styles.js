var gulp            = require('gulp');
var sass            = require('gulp-sass');
var notify          = require('gulp-notify');
var rename          = require('gulp-rename');
var plumber         = require('gulp-plumber');
var minifycss       = require('gulp-minify-css');
var autoprefixer    = require('gulp-autoprefixer');

//
// Build css
//
module.exports = function() {
    return gulp.src('./assets/scss/main.scss')
        .pipe(plumber({
            errorHandler: notify.onError("Error: <%= error.message %>"),
        }))
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 10 versions'],
            cascade: false,
        }))
        .pipe(minifycss())
        .pipe(rename('webhooks.min.css'))
        .pipe(gulp.dest('./assets/compiled'))
        .pipe(notify({ message: "Styles compiled!", onLast: true }));
};
