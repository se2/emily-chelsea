/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

// Register Blocks
import * as column from "./blocks/column";
import * as row from "./blocks/row";

const Icon = () => {
	return (
		<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
			<path
				fill="#bf0000"
				d="M18 1H6.084L0 8l12 15L24 8.084 18 1zm-6.067 5.52L8.413 3h6.452l-2.932 3.52zM9.586 7H3.52l2.82-3.246L9.586 7zM11 9v9.55L3.36 9H11zm2 0h7.695L13 18.566V9zm7.46-2h-6.325l3.138-3.766L20.46 7z"></path>
		</svg>
	);
};

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 */
const registerBlock = (block) => {
	if (!block) {
		return;
	}

	let { category } = block;

	const { name, settings } = block;
	console.log("block", block);
	registerBlockType(name, {
		category,
		...settings,
		icon: Icon,
	});
};

/**
 * Function to register blocks provided by CoBlocks.
 */
export const registerCoBlocksBlocks = () => {
	[column, row].forEach(registerBlock);
};

registerCoBlocksBlocks();
