/* global gulp, $ */
/* jshint node: true */
'use strict';

var PATHS = global.PATHS;
var PLUGINS = require('./common-plugins');

var pathsScriptsToDocument = [
  PATHS.src.scripts + '**/*.js',
  '!' + PATHS.src.scripts + 'vendor-custom/**/*.js'
];
var pathsScriptsReadyToDocument = './docs/js/scripts-to-document/**/*.js';

/**
 * Docs
 *
 * @access public
 */
gulp.task('docs', [/*'_docs-docco', '_docs-jsdoc',*/ '_docs-jsduck']);

/**
 * Docs Prepare Scripts
 *
 * @access private
 */
gulp.task('_docs-prepare-scripts', function() {
  return gulp.src(pathsScriptsToDocument)
    .pipe($.trimlines(PLUGINS.trimlines))
    .pipe($.include())
    .pipe(gulp.dest('./docs/js/scripts-to-document'));
});

/**
 * Docs -> docco
 *
 * @access private
 */
gulp.task('_docs-docco', ['_docs-prepare-scripts'], function() {
  return gulp.src(pathsScriptsReadyToDocument)
    .pipe($.docco())
    .pipe(gulp.dest('./docs/js/docco'));
});

/**
 * Docs -> jsdoc
 *
 * @access private
 */
gulp.task('_docs-jsdoc', ['_docs-prepare-scripts'], function() {
  // return gulp.src('./docs/js/scripts-to-document/*.js')
  return gulp.src([pathsScriptsReadyToDocument,
    '!./docs/js/scripts-to-document/*.js'])
  // return gulp.src(pathsScriptsReadyToDocument)
    .pipe($.jsdoc('./docs/js/jsdoc'));
});

/**
 * Docs -> jsduck (through grunt)
 *
 * @access private
 */
gulp.task('_docs-jsduck', ['_docs-prepare-scripts'], function() {
  return gulp.start('grunt-docs');
});