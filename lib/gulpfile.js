var gulp       = require('gulp');
var concat     = require('gulp-concat');
var uglify     = require('gulp-uglify');
var rename     = require('gulp-rename');
var imageop    = require('gulp-image-optimization');
var sass       = require('gulp-ruby-sass');
var cssmin     = require('gulp-cssmin');
var cache      = require('gulp-cache');
var sourcemaps = require('gulp-sourcemaps');
var livereload = require('gulp-livereload');


//Concatenate all scripts
gulp.task('concat-scripts', function() {

    return gulp.src(['source/js/plugin*.js', 'source/js/main.js'])
        .on('error', function (err) {
            console.error('Error', err.message);
        })

        .pipe(sourcemaps.init())
        .pipe(concat('scripts.js'))       
        .pipe(gulp.dest('public/js'))
        .pipe(sourcemaps.write('../../source/maps'))
        
        .pipe(gulp.dest('public/js'));
});

//Compress scripts after concatenating them
gulp.task('compress-scripts', ['concat-scripts'] ,function(){
    return gulp.src('public/js/scripts.js')
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest('public/js'))
        .pipe(livereload());
});



// Compiles SCSS files and generates source maps
gulp.task('sass', function() {
    return sass('source/scss/master.scss', { sourcemap: true })
    .pipe(gulp.dest('public/css'))
    .on('error', function (err) {
       console.error('Error', err.message);
    })

    .pipe(sourcemaps.write('../../source/maps', {
        includeContent: false,
        sourceRoot: '/source'
    }))

    .pipe(gulp.dest('public/css'));
});

// Minifies CSS file
// This way we have both .css and .min.css files available
gulp.task('css', function() {
    return gulp.src('public/css/master.css')
        .pipe(cssmin())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('public/css'))
        .pipe(livereload());
});

// Optimize all images
gulp.task('images', function(cb) {
    gulp.src(['source/img/*.png', 'source/img/*.jpg', 'source/img/*.gif', 'source/img/*.jpeg'])
        .pipe(imageop({
            optimizationLevel: 5,
            progressive: true,
            interlaced: true
        }))
        .pipe(gulp.dest('public/img'))
        .on('end', cb)
        .on('error', cb);
});

//Watch source directories
//Preform tasks on touch events
gulp.task('watch', function() {
    
    livereload.listen();


    // Watch .js files
    gulp.watch('source/js/*.js', ['compress-scripts']);
    // Watch .scss files
    gulp.watch('source/scss/**/*', ['sass']);
    // Watch image files
    gulp.watch('source/img/**/*', ['images']);
    //Watch CSS files
    gulp.watch('public/css/master.css', ['css']);

    
    gulp.watch('../**/*.php').on('change', function(file) {
        livereload.reload(file.path);
       // util.log(util.colors.yellow('PHP file changed' + ' (' + file.path + ')'));
    });

});

gulp.task('default', ['sass','css','concat-scripts','compress-scripts','images','watch']);

module.exports = gulp;