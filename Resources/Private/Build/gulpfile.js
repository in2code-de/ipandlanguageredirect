/* jshint node: true */
'use strict';

const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const stylelint = require('stylelint')
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

var project = {
	css: '../../Public/Css',
	js: '../../Public/JavaScripts',
};

function css() {
	return src(['../Sass/*.scss'])
		.pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
		.pipe(postcss([
			autoprefixer()
		]))
		.pipe(dest(project.css))
		.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(dest(project.css));
}

async function lintSCSS() {
	const results = await stylelint.lint({
		files: '../Sass/*.scss',
		formatter: 'string'
	});

	if (results.output.length > 0) {
		console.log(results.output);
	}

	if (results.errored) {
		await Promise.reject(new Error());
	}

	await Promise.resolve();
}

function js() {
	return src(['../JavaScripts/*.js'])
		.pipe(uglify())
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(dest(project.js));
}

const buildSCSS = series(lintSCSS, css);
const watchSCSS = () => watch(['../Sass/*.scss'], buildSCSS);
const watchJS = () => watch(['../JavaScripts/*.js'], js);

module.exports = {
	buildAll: series(buildSCSS, js),
	watchAll: parallel(watchSCSS, watchJS),
};
