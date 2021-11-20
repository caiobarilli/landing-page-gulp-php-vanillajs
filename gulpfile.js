"use strict";

// Load plugins
const autoprefixer = require("gulp-autoprefixer");
const browsersync = require("browser-sync").create();
const cleanCSS = require("gulp-clean-css");
const del = require("del");
const gulp = require("gulp");
const header = require("gulp-header");
const merge = require("merge-stream");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require("gulp-sass");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const mergeMedia = require("gulp-merge-media-queries");

// Load package.json for banner
const pkg = require("./package.json");

// Set the banner content
const banner = [
  "/*!\n",
  " * <%= pkg.title %> - v<%= pkg.version %> \n",
  " * Copyright " + new Date().getFullYear(),
  " <%= pkg.author %>\n",
  " */\n",
  "\n"
].join("");

// BrowserSync
function browserSync(done) {
  browsersync.init({
    proxy: "localhost/landing-page/"
  });
  done();
}

// BrowserSync reload
function browserSyncReload(done) {
  browsersync.reload();
  done();
}

// Clean vendor
function clean() {
  return del(["./assets/vendor/", "./assets/js/vendor/"]);
}

// Bring third party dependencies from node_modules into vendor directory
function modules() {
  // jQuery
  var jquery = gulp
    .src([
      "./node_modules/jquery/dist/jquery.js",
      "!./node_modules/jquery/dist/core.js"
    ])
    .pipe(
      rename({
        prefix: "1."
      })
    )
    .pipe(gulp.dest("./assets/js/vendor/"));

  // jQuery Easing
  var jqueryEasing = gulp
    .src("./node_modules/jquery.easing/jquery.easing.js")
    .pipe(
      rename({
        prefix: "4."
      })
    )
    .pipe(gulp.dest("./assets/js/vendor/"));

  // Bootstrap
  var bootstrap = gulp
    .src("./node_modules/bootstrap/scss/**/*")
    .pipe(gulp.dest("./assets/vendor/bootstrap/scss"));
  var bootstrapJS = gulp
    .src("./node_modules/bootstrap/dist/js/bootstrap.bundle.js")
    .pipe(
      rename({
        prefix: "2."
      })
    )
    .pipe(gulp.dest("./assets/js/vendor/"));

  // Swiper
  var swiperJS = gulp
    .src("./node_modules/swiper/js/swiper.js")
    .pipe(
      rename({
        prefix: "3."
      })
    )
    .pipe(gulp.dest("./assets/js/vendor/"));

  // Font Awesome
  var fontAwesome = gulp
    .src("./node_modules/@fortawesome/fontawesome-free/scss/**/*")
    .pipe(gulp.dest("./assets/vendor/fontawesome-free/scss"));
  var fontAwesomeWebfonts = gulp
    .src("./node_modules/@fortawesome/fontawesome-free/webfonts/**/*")
    .pipe(gulp.dest("./assets/vendor/fontawesome-free/webfonts"));

  return merge(
    bootstrap,
    bootstrapJS,
    swiperJS,
    fontAwesome,
    fontAwesomeWebfonts,
    jquery,
    jqueryEasing
  );
}

// CSS task
function css() {
  return gulp
    .src(["./assets/scss/**/*.scss"])
    .pipe(plumber())
    .pipe(
      sass({
        outputStyle: "expanded",
        includePaths: "./node_modules"
      })
    )
    .on("error", sass.logError)
    .pipe(
      autoprefixer({
        cascade: false
      })
    )
    .pipe(mergeMedia({
      log: true
    }))
    .pipe(cleanCSS())
    .pipe(
      rename({
        basename: "styles",
        suffix: ".min"
      })
    )
    .pipe(
      header(banner, {
        pkg: pkg
      })
    )
    .pipe(gulp.dest("./"))

    .pipe(mergeMedia())
    .pipe(
      rename({
        basename: "styles"
      })
    )
    .pipe(gulp.dest("./"))
    .pipe(browsersync.stream());
}

// Custom JS task
function customJS() {
  return gulp
    .src(["./assets/js/custom/*.js", "!./assets/js/*.min.js"])
    .pipe(concat("main.js"))
    .pipe(
      rename({
        basename: "scripts"
      })
    )
    .pipe(
      header(banner, {
        pkg: pkg
      })
    )
    .pipe(gulp.dest("./assets/js/"))
    .pipe(uglify())
    .pipe(
      rename({
        basename: "scripts",
        suffix: ".min"
      })
    )
    .pipe(
      header(banner, {
        pkg: pkg
      })
    )
    .pipe(gulp.dest("./"))
    .pipe(browsersync.stream());
}

// Vendor JS task
function vendorJS() {
  return gulp
    .src(["./assets/js/vendor/*.js", "!./assets/js/*.min.js"])
    .pipe(concat("vendor.js"))
    .pipe(
      rename({
        basename: "vendor"
      })
    )
    .pipe(
      header(banner, {
        pkg: pkg
      })
    )
    .pipe(gulp.dest("./assets/js/"))
    .pipe(concat("vendor.js"))
    .pipe(uglify())
    .pipe(
      rename({
        basename: "vendor",
        suffix: ".min"
      })
    )
    .pipe(
      header(banner, {
        pkg: pkg
      })
    )
    .pipe(gulp.dest("./"))
    .pipe(browsersync.stream());
}

// Watch files
function watchFiles() {
  gulp.watch(["./assets/scss/**/*"], css);
  gulp.watch(["./assets/js/custom/**/*", "!./*.min.js"], customJS);
  gulp.watch(["./assets/js/vendor/**/*", "!./*.min.js"], vendorJS);
  gulp.watch("./**/*{.html,.php,.scss}", browserSyncReload);
}

// Define complex tasks
const vendor = gulp.series(clean, modules);
const build = gulp.series(vendor, gulp.parallel(css, customJS, vendorJS));
const watch = gulp.series(build, gulp.parallel(watchFiles, browserSync));

// Export tasks
exports.css = css;
exports.js = vendorJS;
exports.js = customJS;
exports.clean = clean;
exports.vendor = vendor;
exports.build = build;
exports.watch = watch;
exports.default = build;
