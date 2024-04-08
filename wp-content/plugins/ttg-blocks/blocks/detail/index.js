const NAMESPACE = "ttg-block-detail/";

const createHigherOrderComponent = wp.compose.createHigherOrderComponent;

function isComponent({ name }) {
	return name == "core/details";
}

function buildStyleFromAttrs(attrs, oldStyle = {}){
    const { headingBgColor, expandIconColor } = attrs;
	
    let style = {
        ...oldStyle
    };

    if(headingBgColor){
        style = {
            ...style,
            "--heading-bg-color": headingBgColor,
        }
    }

    if(expandIconColor){
        style = {
            ...style,
            "--expand-icon-color": expandIconColor,
        }
    }

    return style;
}

function addAttributes(settings, name) {
	if (typeof settings.attributes !== "undefined" && isComponent({ name })) {
		settings.attributes = Object.assign(settings.attributes, {
			headingBgColor: {
				type: "string",
			},
            expandIconColor: {
                type: "string",
            }
		});
	}
	return settings;
}
wp.hooks.addFilter(
	"blocks.registerBlockType",
	`${NAMESPACE}addAttributes`,
	addAttributes,
);

const addControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { Fragment } = wp.element;
		const { PanelColorSettings } = wp.editor;
		const { InspectorControls } = wp.blockEditor;
		const { attributes, setAttributes, isSelected, name } = props;
        const isDetailBlock = isComponent({ name });

		if (!isDetailBlock) {
			return <BlockEdit {...props} />;
		}

		const { headingBgColor, expandIconColor } = attributes;

		return (
			<Fragment>
				{isSelected && isDetailBlock && (
					<InspectorControls group="styles">
						<PanelColorSettings
							title="Extra Color Settings"
							colorSettings={[
								{
									value: headingBgColor,
									onChange: (color) => {
										setAttributes({
											headingBgColor: color,
										});
									},
									label: "Heading Background Color",
								},
                                {
									value: expandIconColor,
									onChange: (color) => {
										setAttributes({
											expandIconColor: color,
										});
									},
									label: "Expand Icon Color",
								},
							]}
						/>
					</InspectorControls>
				)}
                <BlockEdit {...props} />
			</Fragment>
		);
	};
}, "addControls");
wp.hooks.addFilter("editor.BlockEdit", `${NAMESPACE}addControls`, addControls);

function saveContent(extraProps, blockType, attributes) {
    const {style} = extraProps;
	const { name } = blockType;

    if(!isComponent({ name })){
        return extraProps;
    }

	return {
        ...extraProps,
        style: buildStyleFromAttrs(attributes, style)
    };
}
wp.hooks.addFilter(
	"blocks.getSaveContent.extraProps",
	`${NAMESPACE}saveContent`,
	saveContent,
);

const editContent = createHigherOrderComponent((BlockListBlock) => {
	return (props) => {
		const { name } = props;

		if (!isComponent({ name })) {
			return <BlockListBlock {...props} />;
		}

		const { attributes } = props;

		return (
            <BlockListBlock
                {...props}
                wrapperProps={{
                    style: buildStyleFromAttrs(attributes),
                }}
            />
        );
	};
}, "editContent");
wp.hooks.addFilter(
	"editor.BlockListBlock",
	`${NAMESPACE}editContent`,
	editContent,
);
