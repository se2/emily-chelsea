/**
 * WordPress dependencies
 */
import { Fragment } from "@wordpress/element";
import {
	useBlockProps,
	InspectorControls,
	InnerBlocks,
} from "@wordpress/block-editor";
import { SelectControl, TextControl, PanelBody } from "@wordpress/components";

import { options } from "./const";

const Edit = ({ attributes, setAttributes }) => {
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
	const { style, className } = useBlockProps({
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
		<Fragment>
			<div className="ttg-row">
				<div
					style={style}
					className={className.replace(/wp-block/gi, "")}>
					<InnerBlocks allowedBlocks={["ttg/column"]} />
				</div>
			</div>
			<InspectorControls>
				<PanelBody title="Column size">
					<SelectControl
						label="Align"
						options={options}
						value={align}
						onChange={(val) => {
							setAttributes({ align: val });
						}}
						type="number"
					/>
					<SelectControl
						label="Align  (Tablet)"
						options={options}
						value={align_tablet}
						onChange={(val) => {
							setAttributes({ align_tablet: val });
						}}
						type="number"
					/>
					<SelectControl
						label="Align (Mobile)"
						options={options}
						value={align_m}
						onChange={(val) => {
							setAttributes({ align_m: val });
						}}
						type="number"
					/>

					<SelectControl
						label="Justify"
						options={options}
						value={justify}
						onChange={(val) => {
							setAttributes({ justify: val });
						}}
						type="number"
					/>
					<SelectControl
						label="Justify (Tablet)"
						options={options}
						value={justify_tablet}
						onChange={(val) => {
							setAttributes({ justify_tablet: val });
						}}
						type="number"
					/>
					<SelectControl
						label="Justify (Mobile)"
						options={options}
						value={justify_m}
						onChange={(val) => {
							setAttributes({ justify_m: val });
						}}
						type="number"
					/>

					<TextControl
						label="Gutter"
						value={gutter}
						onChange={(val) => {
							setAttributes({ gutter: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Gutter (Tablet)"
						value={gutter_tablet}
						onChange={(val) => {
							setAttributes({ gutter_tablet: Number(val) });
						}}
						type="number"
					/>
					<TextControl
						label="Gutter (Mobile)"
						value={gutter_m}
						onChange={(val) => {
							setAttributes({ gutter_m: Number(val) });
						}}
						type="number"
					/>
				</PanelBody>
			</InspectorControls>
		</Fragment>
	);
};
export default Edit;
