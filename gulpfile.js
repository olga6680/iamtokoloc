// Подключаем Gulp и все необходимые библиотеки
const { src, dest, parallel, series, watch } = require('gulp');
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const bourbon = require('node-bourbon');
const cleancss = require('gulp-clean-css');
const newer = require('gulp-newer');
const imagemin = require('gulp-imagemin');
const del = require('del');
const svgstore = require('gulp-svgstore');
const rename = require('gulp-rename');

sass.compiler = require('node-sass');

function browsersync() {
    browserSync.init({
        proxy: 'iamtokoloc/',
        notify: false,
        online: false
    })
}

function scripts() {
    return src([
            'node_modules/jquery/dist/jquery.min.js',
            'catalog/view/theme/iamtoko/js/**/*.js',
        ])
        .pipe(concat('theme.min.js'))
        .pipe(uglify())
        .pipe(dest('catalog/view/theme/iamtoko/js/'))
        .pipe(browserSync.stream())
}

function styles() {
    return src('catalog/view/theme/iamtoko/stylesheet/stylesheet.sass')
        .pipe(sass.sync({
            includePaths: bourbon.includePaths
        }).on('error', sass.logError))
        .pipe(concat('stylesheet.css'))
        .pipe(autoprefixer({ overrideBrowserslist: ['last 25 versions'], grid: true }))
        .pipe(cleancss(({ level: { 1: { specialComments: 0 } }, /*format: 'beautify'*/ })))
        .pipe(dest('catalog/view/theme/iamtoko/stylesheet/'))
        .pipe(browserSync.stream())
}

function images() {
    return src('image/catalog/**/*')
        .pipe(newer('image/cashe/catalog/'))
        .pipe(imagemin())
        .pipe(dest('image/cashe/catalog/'))
}

function cleanimg() {
    return del('image/cashe/catalog/**/*', { force: true })
}

function cleandist() {
    return del('dist/**/*', { force: true })
}

function sprite() {
    return src('image/icon-*.svg')
        .pipe(svgstore({
            inlineSvg: true
        }))
        .pipe(rename('sprite.svg'))
        .pipe(dest('image'));
}

function buildcopy() {
    return src([
            'catalog/view/theme/iamtoko/js/**/*min.js',
            'catalog/view/theme/iamtoko/stylesheet/**/*.min.css',
            'image/cashe/catalog/**/*',
            'catalog/view/theme/iamtoko/template/**/*.twig'
        ], { base: './' })
        .pipe(dest('dist'));
}

function startwatch() {
    watch('catalog/view/theme/iamtoko/stylesheet/stylesheet.sass', styles);
    watch(['catalog/view/theme/iamtoko/js/**/*.js', '!catalog/view/theme/iamtoko/js/**/*.min.js'], scripts);
    watch('catalog/view/theme/iamtoko/template/**/*.twig').on('change', browserSync.reload);
    watch('catalog/view/theme/iamtoko/libs/**/*').on('change', browserSync.reload);
    watch('image/catalog/**/*', images);
}

exports.browsersync = browsersync;
exports.scripts = scripts;
exports.styles = styles;
exports.images = images;
exports.cleanimg = cleanimg;
exports.sprite = sprite;
exports.build = series(cleandist, styles, scripts, images, buildcopy);

exports.default = parallel(scripts, styles, browsersync, startwatch);