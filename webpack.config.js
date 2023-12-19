/**
 * Webpack configuration file.
 *
 * Imports the default WordPress webpack config and extends it with custom configuration.
 *
 * Adds entry points for SCSS and JS files.
 * Configures output path.
 * Extends module rules to handle images and fonts.
 * Adds plugins for cleaning build folder, ignoring files, copying images, generating SVG sprite,
 * notifying on builds, and enabling live browser refresh.
 * Exports the modified webpack config object.
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const IgnoreEmitWebPackPlugin = require('ignore-emit-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');
const CopyPlugin = require('copy-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const path = require('path');

// all JS entries
const fs = require('fs');
const jsSourcePath = path.resolve(process.cwd(), 'src/js');
const entry = fs.readdirSync(jsSourcePath).reduce((acc, fileName) => {
	if (fileName.endsWith('.js')) {
		const entryName = path.basename(fileName, '.js');
		acc[entryName] = path.resolve(jsSourcePath, fileName);
	}
	return acc;
}, {});

module.exports = {
	...defaultConfig,
	entry: {
		style: path.resolve(process.cwd(), 'src/scss', 'styles.scss'),
		...entry,
	},
	output: {
		path: path.resolve(__dirname, 'dist'),
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(png|jpe?g|gif|svg|webp)$/,
				type: 'asset',
				parser: {
					dataUrlCondition: {
						maxSize: 2 * 1024, // 2kb
					},
				},
				generator: {
					filename: 'img/[name][ext][query]',
				},
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]', // Removed [contenthash]
				},
			},
		],
	},
	plugins: [
		...defaultConfig.plugins,
		new CleanWebpackPlugin(),
		new IgnoreEmitWebPackPlugin(['style.js']),
		new CopyPlugin({
			patterns: [
				{
					from: './src/img',
					to: './img',
				},
			],
		}),
		new SVGSpritemapPlugin('./src/icons/**/*.svg', {
			output: {
				filename: '/img/icons.svg',
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
			// 	key: '/Users/YOURACCOUNTNAME/Library/Application Support/Local/run/router/nginx/certs/websitename.local.key',
			// 	cert: '/Users/YOURACCOUNTNAME/Library/Application Support/Local/run/router/nginx/certs/websitename.local.crt',
			// },
		}),
	],
};
