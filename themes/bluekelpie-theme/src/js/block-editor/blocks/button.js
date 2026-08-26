import domReady from '@wordpress/dom-ready';
import { unregisterBlockStyle, registerBlockStyle } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
	registerBlockExtension,
	registerIcons,
	IconPicker,
} from '@10up/block-components';
import {
	PanelBody,
	ToggleControl,
	PanelRow,
	Button,
	TextareaControl,
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

const blockName = 'core/button';

/**
 * Register block styles
 *
 * @type {Array} Styles to register for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */
const registerBlockStyles = [
	{
		name: 'secondary',
		label: 'Secondary',
	},
	{
		name: 'plain',
		label: 'Plain',
	},
];

/**
 * Remove block styles
 *
 * @type {Array} Styles to remove for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */
const unregisterBlockStyles = ['fill', 'outline'];

/**
 * Here we hook into the editor initialization and call the above functions
 */
domReady(() => {
	registerBlockStyle(blockName, registerBlockStyles);
	unregisterBlockStyle(blockName, unregisterBlockStyles);
});

/**
 * Update editor UI
 *
 * @param {Object} props block props
 * @see https://developer.wordpress.org/news/2024/08/how-to-extend-a-wordpress-block/#using-javascript
 * @see https://gutenberg.10up.com/guides/html-tag-processor/#code-example
 *
 * @return {HTMLElement} UI
 */
const SizeBlockEdit = (props) => {
	const {
		attributes: { size },
		setAttributes,
	} = props;

	const handleSizeSelection = (value) => {
		setAttributes({
			size: value,
		});
	};

	const sizes = [
		{ label: 'Small', value: 'sm' },
		{ label: 'Medium', value: 'md' },
		{ label: 'Large', value: 'lg' },
	];

	return (
		<InspectorControls>
			<PanelBody title={__('Size')}>
				<PanelRow className="block-editor-block-styles__variants">
					{sizes.map(({ label, value }) => (
						<>
							<Button
								className={`${value === size && `is-active`} components-button block-editor-block-styles__item`}
								onClick={() => handleSizeSelection(value)}
							>
								<span className="block-editor-block-styles__item-text">
									{label}
								</span>
							</Button>
						</>
					))}
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
};

registerBlockExtension(blockName, {
	extensionName: 'button-updates',
	attributes: {
		size: {
			type: 'string',
			default: 'md',
		},
	},
	classNameGenerator: (attributes) => {
		const { size } = attributes;
		return `size-${size}`;
	},
	inlineStyleGenerator: () => null,
	Edit: SizeBlockEdit,
});

// todo: glob files in /svg and register icons in separate file
registerIcons({
	name: 'bluekelpie/lucide',
	label: 'Lucide Icons',
	icons: [
		{
			source: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>',
			name: 'search',
			label: 'Search',
		},
		{
			source: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>',
			name: 'arrow-left',
			label: 'Arrow Left',
		},
		{
			source: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>',
			name: 'arrow-right',
			label: 'Arrow Right',
		},
		{
			source: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>',
			name: 'pin',
			label: 'Pin',
		},
	],
});
/**
 * Update editor UI
 *
 * @param {Object} props block props
 * @see https://developer.wordpress.org/news/2024/08/how-to-extend-a-wordpress-block/#using-javascript
 * @see https://gutenberg.10up.com/guides/html-tag-processor/#code-example
 *
 * @return {HTMLElement} UI
 */
const IconBlockEdit = (props) => {
	const {
		attributes: { icon },
		setAttributes,
	} = props;

	const handleNoIcon = () =>
		setAttributes({
			icon: { name: '', iconSet: '', beforeText: true },
		});

	const handleIconSelection = (value) => {
		if (value.name === icon.name) {
			handleNoIcon();
		} else {
			setAttributes({
				icon: { ...icon, name: value.name, iconSet: value.iconSet },
			});
		}
	};

	return (
		<InspectorControls>
			<PanelBody title={__('Icon')}>
				<PanelRow>
					<IconPicker value={icon} onChange={handleIconSelection} />
				</PanelRow>
				<PanelRow>
					<ToggleControl
						label={__('Position before text')}
						checked={icon.beforeText}
						onChange={(value) =>
							setAttributes({
								icon: { ...icon, beforeText: value },
							})
						}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
};

registerBlockExtension(blockName, {
	extensionName: 'button-updates',
	attributes: {
		icon: {
			type: 'object',
			default: {
				name: '',
				iconSet: '',
				beforeText: true,
			},
		},
	},
	classNameGenerator: (attributes) => {
		const { icon } = attributes;
		if (icon.name === '') {
			return '';
		}
		let className = 'has-icon';
		if (icon.beforeText) {
			className = className.concat(' icon-before');
		}
		return className;
	},
	inlineStyleGenerator: () => null,
	Edit: IconBlockEdit,
});

/**
 * BlockEdit
 *
 * a react component that will get mounted in the Editor when the block is
 * selected. It is recommended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {Object} props block props
 * @return {HTMLElement} UI
 */
const AccessibleTextBlockEdit = (props) => {
	const {
		attributes: { accessibleText },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<PanelBody title={__('Additional Information')}>
				<TextareaControl
					value={accessibleText}
					label={__('Accessible Text')}
					help={__(
						'This replaces what screenreader will read as the link text.',
						'bluekelpie-theme'
					)}
					onChange={(value) => {
						setAttributes({
							accessibleText: value,
						});
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
};

registerBlockExtension(blockName, {
	extensionName: 'button-updates-accessible-text',
	attributes: {
		accessibleText: {
			type: 'string',
			default: '',
		},
	},
	classNameGenerator: () => null,
	inlineStyleGenerator: () => null,
	Edit: AccessibleTextBlockEdit,
});

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'custom-theme/disable-block-settings',
	(settings, name) => {
		// Define the blocks and their corresponding settings to disable
		const disabledBlocks = {
			'core/button': [
				'typography',
				'color',
				'spacing',
				'__experimentalBorder',
				'shadow',
			], // Disable settings for Button block
		};

		// Check if the block name is in the disabled blocks list
		if (disabledBlocks[name]) {
			settings.supports = settings.supports || {};

			// Loop through the settings to be removed for the block
			disabledBlocks[name].forEach((setting) => {
				delete settings.supports[setting];
			});
		}

		return settings;
	}
);
