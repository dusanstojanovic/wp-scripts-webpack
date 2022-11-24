const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const IgnoreEmitWebPackPlugin = require('ignore-emit-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		style: path.resolve(process.cwd(), 'src/scss', 'styles.scss'),

		app: path.resolve(process.cwd(), 'src/js', 'app.js'),
		jquery: path.resolve(process.cwd(), 'src/js', 'jquery.js'),
		sliders: path.resolve(process.cwd(), 'src/js', 'sliders.js'),
	},
	output: {
		path: path.resolve(__dirname, 'dist'),
	},
	module: {
		...defaultConfig.module,
		rules: [...defaultConfig.module.rules],
	},
	plugins: [
		...defaultConfig.plugins,
		new IgnoreEmitWebPackPlugin(['style.js']),
		new SVGSpritemapPlugin('./src/icons/**/*.svg', {
			output: {
				filename: '/images/icons.svg',
				svgo: true,
				svg4everybody: false,
			},
			sprite: {
				prefix: false,
			},
		}),
		new WebpackBuildNotifierPlugin({
			title: 'Webpack Build',
			suppressSuccess: false,
			successSound: 'tink',
			failureSound: 'Basso',
		}),
		new BrowserSyncPlugin({
			// prettier-ignore
			files: [
				'./../',
				'./',
				'!./node_modules',
				'!./package.json',
			],
			reloadDelay: 1,
			// https: {
			// 	key: '/Users/dusan/Library/Application Support/Local/run/router/nginx/certs/dtsquared.local.key',
			// 	cert: '/Users/dusan/Library/Application Support/Local/run/router/nginx/certs/dtsquared.local.crt',
			// },
		}),
	],
};
