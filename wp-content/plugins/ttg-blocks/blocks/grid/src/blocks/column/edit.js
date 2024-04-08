/**
 * WordPress dependencies
 */
import { Fragment } from "@wordpress/element";
import {
	useBlockProps,
	InspectorControls,
	InnerBlocks,
} from "@wordpress/block-editor";
import { TextControl, PanelBody } from "@wordpress/components";

const Edit = ({ attributes, setAttributes }) => {
	const {
		wid = 100,
		wid_tablet = 100,
		wid_m = 100,
		order = 0,
		order_tablet = 0,
		order_m = 0,
	} = attributes;
	const { style, className } = useBlockProps({
		style: {
			"--ttg-column-width": `${wid}%`,
			"--ttg-column-width_tablet": `${wid_tablet}%`,
			"--ttg-column-width-m": `${wid_m}%`,
			"--ttg-column-order": `${order}`,
			"--ttg-column-order_tablet": `${order_tablet}`,
			"--ttg-column-order_m": `${order_m}`,
		},
		className: "ttg-column",
	});

	return (
		<Fragment>
			<div style={style} className={className.replace(/wp-block/gi, "")}>
				<div className="ttg-column__inner">
					<InnerBlocks />
				</div>
			</div>
			<InspectorControls>
				<PanelBody title="Column size">
					<TextControl
						label="Width"
						value={wid}
						onChange={(val) => {
							setAttributes({ wid: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Width (Tablet)"
						value={wid_tablet}
						onChange={(val) => {
							setAttributes({ wid_tablet: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Width (Mobile)"
						value={wid_m}
						onChange={(val) => {
							setAttributes({ wid_m: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Order"
						value={order}
						onChange={(val) => {
							setAttributes({ order: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Order"
						value={order_tablet}
						onChange={(val) => {
							setAttributes({ order_tablet: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Order (Mobile)"
						value={order_m}
						onChange={(val) => {
							setAttributes({ order_m: Number(val) });
						}}
						type="number"
					/>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
};
export default Edit;
