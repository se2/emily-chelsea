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
	title: "TTG Flexible Col",
	/* translators: block description */
	description: "",
	supports: {
		align: false,
		alignWide: false,
		alignFull: false,
	},
	getEditWrapperProps(attributes) {
		const {
			wid = 100,
			wid_tablet = 100,
			wid_m = 100,
			order = 0,
			order_tablet = 0,
			order_m = 0,
		} = attributes;
		const style = {
			"--ttg-column-width": `${wid}%`,
			"--ttg-column-width-m": `${wid_m}%`,
			"--ttg-column-width-tablet": `${wid_tablet}%`,
			"--ttg-column-order": `${order}`,
			"--ttg-column-order_tablet": `${order_tablet}`,
			"--ttg-column-order_m": `${order_m}`,
		};
		return {
			style,
		};
	},
	edit,
	save,
	attributes,
};

export { name, category, metadata, settings, attributes };
