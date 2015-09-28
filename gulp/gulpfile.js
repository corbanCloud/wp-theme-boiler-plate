/*====================================
=            Dependencies            =
====================================*/

var browserSync    = require('browser-sync').create();
var htmlInjector   = require('bs-html-injector');
var fs             = require('fs');
var gulp           = require('gulp-param')(require('gulp'), process.argv);
var base64         = require('gulp-base64');
var versionBump    = require('gulp-bump');
var concat         = require('gulp-concat');
var cssmin         = require('gulp-cssmin');
//var filter         = require('gulp-filter');
//var gulp         = require('gulp');
var gulpif         = require('gulp-if');
var imagemin       = require('gulp-imagemin');
var newer          = require('gulp-newer');
var notify         = require('gulp-notify');
var prompt         = require('gulp-prompt');
var rename         = require('gulp-rename');
var replace        = require('gulp-replace');
var sassCompiler   = require('gulp-sass');
var sftp           = require('gulp-sftp');
var srcmaps        = require('gulp-sourcemaps');
var uglify         = require('gulp-uglify');
//var utils        = require('gulp-util');
var pngquant       = require('imagemin-pngquant');
var sprity         = require('sprity');
var spritySass     = require('sprity-sass');
var sassbeautify = require('gulp-sassbeautify');
//var ignore         = require('gulp-ignore');
//var rimraf         = require('gulp-rimraf');

/*-----  End of Dependencies  ------*/

/*====================================
=            Build Config            =
=====================================*/

var	
    lib     = '../lib',
    src     = '../lib/src',
    pub     = '../lib/pub',
    maps    = '../../lib/maps',
    projectRoot = './../..';

var css = {};
        css.src       = src + '/css';
        css.build     = css.src;
        css.buildFile = css.build + '/master.css';
        css.public    = pub + '/css';
        
var sass = {};
        sass.src      = src + '/scss';
        sass.srcFiles = sass.src + '/**/*.scss';
        sass.build    = src + '/css';
        
var img = {};
        img.src      = src + '/img/';
        img.srcFiles = img.src + '/*.{png,jpg,jpeg}';
        img.build    = src + '/img';
        img.public   = pub + '/img';
        img.cache = lib + '/.img-cache';
    
var sprites = {};
        sprites.src       = img.src + '/icon*.png';
        sprites.build     = img.src;
        sprites.buildName = 'sprite-sheet';
    
var js = {};
        js.src       = src + '/js';
        js.srcFiles  = [js.src + '/plugins.js', js.src + '/script.js'];
        js.build     = src + '/js';
        js.buildName = 'master.js';
        js.buildFile = js.build + '/' + js.buildName;
        js.public    = pub + '/js';
    
/*-----  End of Build Config  ------*/

/*=========================================
=            Deployment Config            =
=========================================*/

var servers = {
    dev : {
        host: 'kpg.demo.nimbus.digital',
        user: 'root',
        remotePath: '/var/www/kpg.demo/public_html/wp-content/themes/KPG-Custom',
        port: 2222,
        niceName: 'Development Server'
    }
};
var deployCache = './.deploy-cache';


/*-----  End of Deployment Config  ------*/


/*==============================
=            Builds            =
==============================*/
 
gulp.task('beautify-scss', function () {
    gulp.src(sass.srcFiles)
        .pipe(sassbeautify())
        .pipe(gulp.dest(sass.src))

});

gulp.task('build-sass', function () {
   
    /* *
     * compiles .scss files, 
     * feeds styles to browsersync if available
     * */
    var sassStream = gulp.src(sass.srcFiles)
        .pipe(srcmaps.init())
        .pipe(sassCompiler()
            .on('error', sassCompiler.logError))        
        .pipe(srcmaps.write(maps));

    if(browserSync && browserSync.active) 
        sassStream.pipe(browserSync.stream({match: '**/*.css'}));
        
    return sassStream
        .pipe(gulp.dest(sass.build));

});

gulp.task('build-scripts', function() {
    /* *
    * Joins plugin and script files
    * */
    var scriptStream = gulp.src(js.srcFiles)
        .on('error', function (err) {
            notify(err.message);
        })
        .pipe(srcmaps.init())
        .pipe(concat(js.buildName))       
        .pipe(srcmaps.write(maps))
    //if(browserSync && browserSync.active)
        .pipe(gulp.dest(js.build));

});

gulp.task('build-sprites', function(){

    /* *
    * Creates the site's sprite sheet
    * */
    return sprity.src({
        src: sprites.src,
        style: '_sprite.scss',
        name: sprites.buildName,
        processor: 'sass',  
        cssPath: '../img',
        dimension: [{
                ratio: 1, dpi: 72
            }],
        })
        .pipe(gulpif('*.png', 
            gulp.dest(sprites.build), 
            gulp.dest(sass.src + '/modules/mixins/')));
});

 /*-----  End of Builds  ------*/

/*====================================================
=            Compression And Optimization            =
====================================================*/
gulp.task('prepare-images', ['build-sprites'], function(){

    return gulp.src(img.srcFiles)
        .pipe(newer(img.cache))
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest(img.cache))
        .pipe(gulp.dest(img.build));

});

gulp.task('prepare-styles', ['build-sass','prepare-images'], function(){

    return gulp.src(css.buildFile)
        .pipe(base64())
        .pipe(cssmin())
        .pipe(rename({
             suffix: '.min'
        }))
        .pipe(gulp.dest(css.build));
});

gulp.task('prepare-scripts', ['build-scripts'], function(){

    return gulp.src(js.buildFile)
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        })) 
        .pipe(gulp.dest(js.build));

});



/*-----  End of Compression & Opt.  ------*/


/*===================================
=            Publication            =
===================================*/

gulp.task('publish-styles', ['prepare-styles'] ,function() {

    /**
     * Moves styles to public directory
     * corrects any dev url references
     * base64 encodes any images referenced in css file
     */
    return gulp.src(css.build + '/*')
        .pipe(gulp.dest(css.public));
   
});

gulp.task('publish-scripts', ['prepare-scripts'] ,function(){
    
    /**
     * Moves the
     */
    return gulp.src(js.build + '/*')
        .pipe(gulp.dest(js.public));
  
});

gulp.task('publish-images', ['prepare-images'], function(){
    /* *
     * Moves images build to public directory
     * */
    return gulp.src(img.build + '/*')
        .pipe(gulp.dest(img.public));

});


/*-----  End of Asset Optimization  ------*/

/*============================
=            Meta            =
============================*/
gulp.task('browser-sync-init', function(){

    browserSync.use(htmlInjector);
    browserSync.init({
        proxy: "localhost:8888/kendalls",
        open: false
    });

});

gulp.task('serve', ['browser-sync-init'], function(){


    gulp.watch('../**/*.php', htmlInjector);
    gulp.watch('functions.php', browserSync.reload);
    gulp.watch(js.srcFiles, ['build-scripts'], browserSync.reload);
    gulp.watch(sass.srcFiles, ['build-sass']);
    gulp.watch(sprites.srcFiles, ['build-sprites']);

});

gulp.task('version-bump', function(bumpType){

    var bumpStream = gulp.src('../theme-meta.json');
    var bumpWPVersion = function() {
        var package  = JSON.parse(fs.readFileSync('./package.json'));
        
        newVersion = package.version;

        gulp.src('../style.css')
            .pipe(replace(/(Version\: .*)/, 'Version: ' + newVersion))
            .pipe(gulp.dest('../'));

    };

    bumpStream.on('end', bumpWPVersion);

    //If a bump was passed use that
     if(bumpType){

        bumpStream
            .pipe(versionBump({type: bumpType}))
            .pipe(gulp.dest('../'));

    //else prompt for input
    } else {

        bumpStream
            .pipe(prompt.prompt({
        
                type: 'checkbox',
                name: 'bumpType',
                message: 'What type of version bump is this?',
                choices: ['patch', 'minor', 'major']
            
            }, function(res){

                //value is in res.bump (as an array) 
                var bumpType = res.bumpType[0];

                bumpStream
                    .pipe(versionBump({type: bumpType}))
                    .pipe(gulp.dest('../'));
            }
        ));
    }

});

gulp.task('package', ['publish-scripts','publish-images','publish-styles']);

gulp.task('deploy', function(location, fullUpload){

    fullUpload = fullUpload || false;
    location = location || 'dev';

    if(servers[location]){

        settings = servers[location];

    } else {

        console.log('Location not found in configuration \r\n Please check your input and try again');
        return false;
    
    }
    uploadFiles = [
        '../*.php',
        '../partials/*.php',
        '../lib/pub/**/*',
        '../acf-json/**/*'
    ];

    if(!fullUpload){
         uploadFiles.push('!./lib/src/**');
         uploadFiles.push('!./package.json');
    }

    return gulp.src(uploadFiles)
        //.pipe(newer(deployCache))
        .pipe(sftp(settings))
        //.pipe(gulp.dest(deployCache))
        //.pipe(notify('Deployed to ' + servers[location].niceName));

});