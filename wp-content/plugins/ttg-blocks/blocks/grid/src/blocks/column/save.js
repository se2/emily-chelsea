/**
 * WordPress dependencies
 */
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

const Save = (props) => {
	const { attributes } = props;
	const {
		wid = 100,
		wid_tablet = 100,
		wid_m = 100,
		order = 0,
		order_tablet = 0,
		order_m = 0,
	} = attributes;
	const blockProps = useBlockProps.save({
		style: {
			"--ttg-column-width": `${wid}%`,
			"--ttg-column-width-m": `${wid_m}%`,
			"--ttg-column-width-tablet": `${wid_tablet}%`,
			"--ttg-column-order": `${order}`,
			"--ttg-column-order_tablet": `${order_tablet}`,
			"--ttg-column-order_m": `${order_m}`,
		},
		className: "ttg-column",
	});

	return (
		<div {...blockProps}>
			<div className="ttg-column__inner">
				<InnerBlocks.Content />
			</div>
		</div>
	);
};
export default Save;
