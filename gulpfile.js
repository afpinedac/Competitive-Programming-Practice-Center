var gulp = require('gulp'),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        minify = require('gulp-minify-css'),
        notify = require('gulp-notify');
livereload = require('gulp-livereload');
connect = require('gulp-connect-php'),
        browserSync = require('browser-sync');

gulp.task('minify-js', function () {
  return gulp.src([
    './public/js/lms.js',
    './public/js/envio.js',
    './public/js/angular/app_cpp.js'
  ]).pipe(concat('app.min.js'))
          .pipe(uglify({mangle: false}))
          .pipe(gulp.dest('./public/js/build'))
          .pipe(livereload());
  //.pipe(notify({message: 'Finished minifying my JavaScript'}));
});


gulp.task('minify-tpl', function () {
  return gulp.src('./app/views/**/*.tpl')
          .pipe(livereload());
});

gulp.task('minify-libs', function () {
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
          .pipe(gulp.dest('./public/js/build'))
          .pipe(livereload());
  //.pipe(notify({message: 'Finished minifying JavaScript libs'}));
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
  gulp.run('minify-libs');
  gulp.run('minify-js');
  gulp.run('php-sync');
  gulp.watch('./public/js/**/*.js', ['minify-js']);
});


