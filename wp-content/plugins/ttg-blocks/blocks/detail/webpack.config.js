/**
 * WordPress dependencies
 */
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

/**
 * External dependencies
 */
const path = require("path");

module.exports = {
	...defaultConfig,
	entry: {
		index: path.resolve(process.cwd(), "index.js"),
		styles: path.resolve(process.cwd(), "styles.scss"),
	},

	output: {
		...defaultConfig.output,
		path: path.resolve(process.cwd(), "build/"),
	},

	plugins: [...defaultConfig.plugins]
};

// Set parallelism to 1 in CircleCI.
if (process.env.CI) {
	module.exports.parallelism = 1;
}
