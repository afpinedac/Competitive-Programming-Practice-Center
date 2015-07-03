var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    minify = require('gulp-minify-css'),
    notify = require('gulp-notify');
    
 gulp.task('minify-js', function(){
  return gulp.src([
    './public/libs/jquery/jquery-1.9.1.js',
    './public/libs/jquery-ui/jquery-ui-1.9.2.custom.min.js',
    './public/libs/tipped/js/tipped/tipped.js',
    './public/libs/tipped/js/spinners/spinners.min.js',
    './public/libs/alertify/src/alertify.js',
    './public/libs/bootstrap/js/bootstrap.min.js',
    './public/js/lms.js',
    './public/js/envio.js',
    './public/libs/angularjs/angular.min.js',
    './public/js/angular/app_cpp.js'
  ]).pipe(concat('app.min.js'))
          .pipe(uglify({mangle: false}))
          .pipe(gulp.dest('./public/js/prod'))
          .pipe(notify({message: 'Finished minifying JavaScript'}));      
});



gulp.task('default', function(){
    gulp.run('minify-js');
});


