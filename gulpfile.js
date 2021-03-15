// Определяем переменную "preprocessor"
var preprocessor = 'scss';

// Определяем константы Gulp
const { src, dest, parallel, series, watch } = require('gulp');

// Подключаем Browsersync
const browserSync = require('browser-sync').create();

// Подключаем gulp-concat
const concat = require('gulp-concat');

// Подключаем gulp-uglify-es
const uglify = require('gulp-uglify-es').default;

// Подключаем модули gulp-sass и gulp-less
const sass = require('gulp-sass');
// const scss = require('gulp-scss');
const less = require('gulp-less');

// Подключаем Autoprefixer
const autoprefixer = require('gulp-autoprefixer');

// Подключаем модуль gulp-clean-css
const cleancss = require('gulp-clean-css');

// Подключаем gulp-imagemin для работы с изображениями
const imagemin = require('gulp-imagemin');

// Подключаем модуль gulp-newer
const newer = require('gulp-newer');

// Подключаем модуль del
const del = require('del');

// Определяем логику работы Browsersync
function browsersync() {
	browserSync.init({ // Инициализация Browsersync
        proxy: "localhost:8001/wp-admin/admin.php?page=mrkv_ua_marketplaces",
		// server: { baseDir: "./" }, // Указываем папку сервера
		notify: true, // Отключаем уведомления
		online: true // Режим работы: true или false
	})
}

function scripts() {
	return src([ // Берём файлы из источников
		//'node_modules/jquery/dist/jquery.min.js', // Пример подключения библиотеки
		'src/js/mrkvmpscript.js' // Пользовательские скрипты, использующие библиотеку, должны быть подключены в конце
		])
	.pipe(concat('mrkvmpscript.min.js')) // Конкатенируем в один файл
	.pipe(uglify({
        mangle: false,
       ecma: 6
    })) // Сжимаем JavaScript
	.pipe(dest('assets/')) // Выгружаем готовый файл в папку назначения
	.pipe(browserSync.stream()) // Триггерим Browsersync для обновления страницы
}

function styles() {
	return src('src/' + 'scss' + '/mrkvmpstyle.' + 'scss' + '') // Выбираем источник: "app/sass/main.sass" или "app/less/main.less"
	.pipe(eval('sass')()) // Преобразуем значение переменной "preprocessor" в функцию
	.pipe(concat('mrkvmpstyle.min.css')) // Конкатенируем в файл app.min.js
	.pipe(autoprefixer({ overrideBrowserslist: ['last 10 versions'], grid: true })) // Создадим префиксы с помощью Autoprefixer
	.pipe(cleancss( { level: { 1: { specialComments: 0 } }/* , format: 'beautify' */ } )) // Минифицируем стили
	.pipe(dest('assets/')) // Выгрузим результат в папку "app/css/"
	.pipe(browserSync.stream()) // Сделаем инъекцию в браузер
}

// function images() {
// 	return src('app/images/src/**/*') // Берём все изображения из папки источника
// 	.pipe(newer('app/images/dest/')) // Проверяем, было ли изменено (сжато) изображение ранее
// 	.pipe(imagemin()) // Сжимаем и оптимизируем изображеня
// 	.pipe(dest('app/images/dest/')) // Выгружаем оптимизированные изображения в папку назначения
// }

// function cleanimg() {
// 	return del('app/images/dest/**/*', { force: true }) // Удаляем всё содержимое папки "app/images/dest/"
// }

// function buildcopy() {
// 	return src([ // Выбираем нужные файлы
// 		'app/css/**/*.min.css',
// 		'app/js/**/*.min.js',
// 		'app/images/dest/**/*',
// 		'app/**/*.html',
// 		], { base: 'app' }) // Параметр "base" сохраняет структуру проекта при копировании
// 	.pipe(dest('dist')) // Выгружаем в папку с финальной сборкой
// }

// function cleandist() {
// 	return del('dist/**/*', { force: true }) // Удаляем всё содержимое папки "dist/"
// }

function startwatch() {

	// Выбираем все файлы JS в проекте, а затем исключим с суффиксом .min.js
	// watch(['/src/js/**/*.js', '!src/js/**/*.min.js'], scripts);
	watch(['src/js/**/*.js', '!src/js/**/*.min.js'], scripts);

	// Мониторим файлы препроцессора на изменения
	// watch('src/**/' + preprocessor + '/**/*', styles);
	watch('src/' + preprocessor + '/**/*', styles);

	// Мониторим файлы HTML на изменения
	watch(['inc/**/*.php', 'templates/**/*.php', '!node_modules/**/*', '!vendor/**/*']).on('change', browserSync.reload);

	// Мониторим папку-источник изображений и выполняем images(), если есть изменения
	// watch('app/images/src/**/*', images);

}

// Экспортируем функцию browsersync() как таск browsersync. Значение после знака = это имеющаяся функция.
exports.browsersync = browsersync;

// Экспортируем функцию scripts() в таск scripts
exports.scripts = scripts;

// Экспортируем функцию styles() в таск styles
exports.styles = styles;

// Экспорт функции images() в таск images
// exports.images = images;

// Экспортируем функцию cleanimg() как таск cleanimg
// exports.cleanimg = cleanimg;

// Создаём новый таск "build", который последовательно выполняет нужные операции
// exports.build = series(cleandist, styles, scripts, images, buildcopy);
exports.build = series(styles, scripts);

// Экспортируем дефолтный таск с нужным набором функций
exports.default = parallel(styles, scripts, browsersync, startwatch);



// Load Gulp...of course
// var gulp         = require( 'gulp' );
//
// // CSS related plugins
// var sass         = require( 'gulp-sass' );
// var autoprefixer = require( 'gulp-autoprefixer' );
// var minifycss    = require( 'gulp-uglifycss' );
//
// // JS related plugins
// var concat       = require( 'gulp-concat' );
// var uglify       = require( 'gulp-uglify' );
// var babelify     = require( 'babelify' );
// var browserify   = require( 'gulp-browserify' );
// var source       = require( 'vinyl-source-stream' );
// var buffer       = require( 'vinyl-buffer' );
// var stripDebug   = require( 'gulp-strip-debug' );
//
// // Utility plugins
// var rename       = require( 'gulp-rename' );
// var sourcemaps   = require( 'gulp-sourcemaps' );
// var notify       = require( 'gulp-notify' );
// var plumber      = require( 'gulp-plumber' );
// var options      = require( 'gulp-options' );
// var gulpif       = require( 'gulp-if' );
//
// // Browers related plugins
// var browserSync  = require( 'browser-sync' ).create();
// var reload       = browserSync.reload;
//
// // Project related variables
// var projectURL   = 'localhost:8001';
//
// var styleSRC     = './src/scss/mrkvmpstyle.scss';
// var styleURL     = './assets/';
// var mapURL       = './';
//
// var jsSRC        = './src/js/mrkvmpscript.js';
// var jsURL        = './assets/';
//
// var styleWatch   = './src/scss/**/*.scss';
// var jsWatch      = './src/js/**/*.js';
// var phpWatch     = './**/*.php';
//
// // Tasks
// gulp.task( 'browser-sync', function() {
// 	browserSync.init({
// 		proxy: projectURL,
// 		// https: {
// 		// 	key: '/Users/alecaddd/.valet/Certificates/test.dev.key',
// 		// 	cert: '/Users/alecaddd/.valet/Certificates/test.dev.crt'
// 		// },
// 		injectChanges: true,
// 		open: false
// 	});
// });
//
// gulp.task( 'styles', function(done) {
// 	gulp.src( styleSRC )
// 		.pipe( sourcemaps.init() )
// 		.pipe( sass({
// 			errLogToConsole: true,
// 			outputStyle: 'compressed'
// 		}) )
// 		.on( 'error', console.error.bind( console ) )
// 		.pipe( autoprefixer({ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }) )
// 		.pipe( sourcemaps.write( mapURL ) )
// 		.pipe( gulp.dest( styleURL ) )
// 		.pipe( browserSync.stream() );
//     done();
// });
//
// gulp.task( 'js', async function(done) {
// 	return browserify({
// 		entries: [jsSRC]
// 	})
// 	// .transform( babelify, { presets: [ 'env' ] } )
// 	// .bundle()
// 	.pipe( source( 'mrkvmpscript.js' ) )
// 	.pipe( buffer() )
// 	.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
// 	.pipe( sourcemaps.init({ loadMaps: true }) )
// 	.pipe( uglify() )
// 	.pipe( sourcemaps.write( '.' ) )
// 	.pipe( gulp.dest( jsURL ) )
// 	.pipe( browserSync.stream() );
//     done();
//  });
//
// function triggerPlumber( src, url ) {
// 	return gulp.src( src )
// 	.pipe( plumber() )
// 	.pipe( gulp.dest( url ) );
// }
//
//  gulp.task( 'default', gulp.series('styles', 'js', function(done) {
// 	gulp.src( jsURL + 'mrkvmpscript.min.js' )
// 		.pipe( notify({ message: 'Assets Compiled!' }) );
//     done();
//  }));
//
//  gulp.task( 'watch', gulp.series('default', 'browser-sync', function() {
// 	gulp.watch( phpWatch, reload );
// 	gulp.watch( styleWatch, [ 'styles' ] );
// 	gulp.watch( jsWatch, [ 'js', reload ] );
// 	gulp.src( jsURL + 'mrkvmpscript.min.js' )
// 		.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
//  }));
