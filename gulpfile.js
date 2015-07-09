var gulp = require('gulp'),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        minify = require('gulp-minify-css'),
        notify = require('gulp-notify'),
        livereload = require('gulp-livereload'),
        connect = require('gulp-connect-php'),
        browserSync = require('browser-sync');

gulp.task('minify-js', function () {
  return gulp.src([
    './public/js/lms.js',
    './public/js/envio.js',
    './public/js/angular/app_cpp.js'
  ]).pipe(concat('app.min.js'))
          .pipe(uglify({mangle: false}))
          .pipe(gulp.dest('./public/build'))
          .pipe(livereload());
});

gulp.task('minify-css', function () {
  return gulp.src([
    './public/css/lms.css',
  ]).pipe(concat('app.min.css'))
          .pipe(minify())
          .pipe(gulp.dest('./public/build'))
          .pipe(livereload());
});

gulp.task('minify-tpl', function () {
  return gulp.src('./app/views/**/*.tpl')
          .pipe(livereload());
});

gulp.task('minify-libs-js', function () {
  return gulp.src([
    './public/libs/jquery/jquery-1.9.1.js',
    './public/libs/jquery-ui/jquery-ui-1.9.2.custom.min.js',
    './public/libs/tipped/js/tipped/tipped.js',
    './public/libs/tipped/js/spinners/spinners.min.js',
    './public/libs/alertify/src/alertify.js',
    './public/libs/bootstrap/js/bootstrap.min.js',
    './public/libs/angularjs/angular.min.js',
  ]).pipe(concat('lib.min.js'))
          .pipe(uglify({mangle: false}))
          .pipe(gulp.dest('./public/build'))
          .pipe(livereload());    
});

gulp.task('minify-libs-css', function () {
  return gulp.src([
    './public/libs/normalize/normalize.css',
    './public/libs/bootstrap/css/bootstrap.min.css',
    './public/libs/tipped/css/tipped/tipped.css',
    './public/libs/font-awesome/css/font-awesome.min.css',
    './public/libs/alertify/themes/alertify.core.css',
    './public/libs/alertify/themes/alertify.default.css'
  ]).pipe(concat('lib.min.css'))
          //.pipe(minify())
          .pipe(gulp.dest('./public/build'))
          .pipe(livereload());    
});

gulp.task('php-sync', function () {
  connect.server({}, function () {
    browserSync({
      proxy: 'localhost:1234'
    });
  });

  gulp.watch('./app/views/**/*.tpl').on('change', function () {
    browserSync.reload();
  });
  gulp.watch('./app/controllers/**/*.php').on('change', function () {
    browserSync.reload();
  });
  gulp.watch('./app/models/**/*.php').on('change', function () {
    browserSync.reload();
  });
});

gulp.task('default', function () {
  livereload.listen();
  gulp.run('minify-libs-js');
  gulp.run('minify-libs-css');
  gulp.run('minify-js');
  gulp.run('minify-css');
  gulp.run('php-sync');
  gulp.watch('./public/js/**/*.js', ['minify-js']);
  gulp.watch('./public/css/**/*.css', ['minify-css']);
});


