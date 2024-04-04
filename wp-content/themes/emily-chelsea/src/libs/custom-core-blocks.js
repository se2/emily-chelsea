const allowSpacing = ["core/heading", "core/paragraph"];

function addCoverAttribute(settings, name) {
	if (typeof settings.attributes !== "undefined") {
		if (allowSpacing.indexOf(name) >= 0) {
			settings.attributes = Object.assign(settings.attributes, {
				padding: {
					type: "string",
					default: "",
				},
				paddingTablet: {
					type: "string",
					default: "",
				},
				paddingMobile: {
					type: "string",
					default: "",
				},
				margin: {
					type: "string",
					default: "",
				},
				marginTablet: {
					type: "string",
					default: "",
				},
				marginMobile: {
					type: "string",
					default: "",
				},
			});
		}
	}
	return settings;
}

var el = wp.element.createElement;

const coverAdvancedControls = wp.compose.createHigherOrderComponent(
	(BlockEdit) => {
		return (props) => {
			const { attributes, setAttributes, isSelected, name } = props;
			if (!isSelected || allowSpacing.indexOf(name) < 0) {
				return el(wp.element.Fragment, {}, el(BlockEdit, props));
			}
			const {
				padding,
				paddingTablet,
				paddingMobile,
				margin,
				marginTablet,
				marginMobile,
			} = attributes;
			const spacing = [
				el(wp.components.TextControl, {
					label: wp.i18n.__("Padding", "ttg"),
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					value: padding,
					onChange: (newValue) => {
						setAttributes({
							padding: newValue,
						});
					},
				}),
				el(wp.components.TextControl, {
					label: wp.i18n.__("Padding Tablet", "ttg"),
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					value: paddingTablet,
					onChange: (newValue) => {
						setAttributes({
							paddingTablet: newValue,
						});
					},
				}),
				el(wp.components.TextControl, {
					label: wp.i18n.__("Padding Mobile", "ttg"),
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					value: paddingMobile,
					onChange: (newValue) => {
						setAttributes({
							paddingMobile: newValue,
						});
					},
				}),
				el(wp.components.TextControl, {
					label: wp.i18n.__("Margin", "ttg"),
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					value: margin,
					onChange: (newValue) => {
						setAttributes({
							margin: newValue,
						});
					},
				}),
				el(wp.components.TextControl, {
					label: wp.i18n.__("Margin Tablet", "ttg"),
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					value: marginTablet,
					onChange: (newValue) => {
						setAttributes({
							marginTablet: newValue,
						});
					},
				}),
				el(wp.components.TextControl, {
					label: wp.i18n.__("Margin Mobile", "ttg"),
					value: marginMobile,
					help: wp.i18n.__(
						"Syntax: top right bottom left (unit px or %)\nExample: 10px 0px 30% 0px",
						"ttg",
					),
					onChange: (newValue) => {
						setAttributes({
							marginMobile: newValue,
						});
					},
				}),
			];
			return el(
				wp.element.Fragment,
				{},
				el(BlockEdit, props),
				el(wp.blockEditor.InspectorAdvancedControls, {}, spacing),
			);
		};
	},
	"coverAdvancedControls",
);

function coverApplyStyle(extraProps, blockType, attributes = {}) {
	const { name } = blockType;
	if (allowSpacing.indexOf(name) >= 0) {
		const {
			padding = "",
			paddingTablet = "",
			paddingMobile = "",
			margin = "",
			marginTablet = "",
			marginMobile = "",
		} = attributes || {};
		let haveCustom = false;

		if (padding.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: { ...extraProps.style, "--custom-p": padding.trim() },
			});
		}

		if (paddingTablet.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: {
					...extraProps.style,
					"--custom-p-tablet": paddingTablet.trim(),
				},
			});
		}

		if (paddingMobile.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: {
					...extraProps.style,
					"--custom-p-m": paddingMobile.trim(),
				},
			});
		}

		if (margin.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: { ...extraProps.style, "--custom-mar": margin.trim() },
			});
		}
		if (marginTablet.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: {
					...extraProps.style,
					"--custom-mar-tablet": marginTablet.trim(),
				},
			});
		}
		if (marginMobile.trim() != "") {
			haveCustom = true;
			Object.assign(extraProps, {
				style: {
					...extraProps.style,
					"--custom-mar-m": marginMobile.trim(),
				},
			});
		}

		if (haveCustom) {
			const className =
				typeof extraProps.className != "undefined"
					? extraProps.className + " custom-spacing"
					: "custom-spacing";
			extraProps.className = className;
		}
	}

	return extraProps;
}

wp.hooks.addFilter(
	"blocks.getSaveContent.extraProps",
	"ttg/cover-apply-style",
	coverApplyStyle,
);

wp.hooks.addFilter(
	"editor.BlockEdit",
	"ttg/cover-advanced-control",
	coverAdvancedControls,
);

wp.hooks.addFilter(
	"blocks.registerBlockType",
	"ttg/cover-custom-attribute",
	addCoverAttribute,
);
