/**
 * WordPress dependencies
 */
import metadata from "./block.json";
import edit from "./edit";
import save from "./save";

// Destructure the json file to get the name of the block
// For more information on how this works, see: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment
const { name, category, attributes } = metadata;

const settings = {
	/* translators: block name */
	title: "TTG Flexible Row",
	/* translators: block description */
	description: "",
	supports: {
		align: false,
		alignWide: false,
		alignFull: false,
	},
	edit,
	save,
	attributes,
};

export { name, category, metadata, settings, attributes };
