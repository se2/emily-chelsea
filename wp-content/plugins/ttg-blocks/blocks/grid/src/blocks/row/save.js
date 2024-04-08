/**
 * WordPress dependencies
 */
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

const Save = (props) => {
	const { attributes } = props;
	const {
		align,
		align_tablet,
		align_m,
		justify,
		justify_tablet,
		justify_m,
		gutter,
		gutter_tablet,
		gutter_m,
	} = attributes;
	const blockProps = useBlockProps.save({
		style: {
			"--ttg-column-gutter": `${gutter}px`,
			"--ttg-column-gutter-tablet": `${gutter_tablet}px`,
			"--ttg-column-gutter-m": `${gutter_m}px`,
			"--ttg-column-mar": `${gutter * -1}px`,
			"--ttg-column-mar-tablet": `${gutter_tablet * -1}px`,
			"--ttg-column-mar-m": `${gutter_m * -1}px`,
		},
		className: `d-flex flex-wrap justify-content-${justify_m} justify-content-${justify_tablet} justify-content-lg-${justify} align-items-${align_m} align-items-${align_tablet} align-items-lg-${align} ttg-row__inner`,
	});

	return (
		<div className="ttg-row">
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		</div>
	);
};
export default Save;
