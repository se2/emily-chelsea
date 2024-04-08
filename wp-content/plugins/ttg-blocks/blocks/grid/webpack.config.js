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
		"ttg-gutenberg": path.resolve(process.cwd(), "src/blocks.js"),
	},

	output: {
		...defaultConfig.output,
		path: path.resolve(process.cwd(), "scripts/"),
	},

	plugins: [...defaultConfig.plugins],
};

// Set parallelism to 1 in CircleCI.
if (process.env.CI) {
	module.exports.parallelism = 1;
}
