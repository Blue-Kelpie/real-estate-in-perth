import {
	unregisterBlockType,
	unregisterBlockStyle,
	unregisterBlockVariation,
	registerBlockStyle,
	registerBlockVariation,
} from '@wordpress/blocks';
import { dispatch } from '@wordpress/data';
import domReady from '@wordpress/dom-ready';

const blockName = 'namespace/name';

/**
 * Register block styles
 *
 * @type {Array} Styles to register for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */
const registerBlockStyles = [
	// {
	// 	name: "hero",
	// 	label: "Hero",
	// },
];

/**
 * Register block variations
 *
 * @type {Array} Variations to register for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/
 */
const registerBlockVariations = [
	// {
	// 	name: "three-columns-wide-left",
	// 	title: "50 / 25 / 25",
	// 	description: "Three columns; wide left column",
	// 	innerBlocks: [
	// 		["core/column", { width: "50%" }],
	// 		["core/column", { width: "25%" }],
	// 		["core/column", { width: "25%" }],
	// 	],
	// 	scope: ["block"],
	// },
];

/**
 * Unregister blocks
 *
 * @type {Array} Add the names of blocks to unregister here
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#using-a-deny-list
 */
const unregisterBlocks = [
	// "core/verse"
];

/**
 * Remove editor panels
 *
 * @type {Array} Add the names of panels to remove here
 * @see https://developer.wordpress.org/block-editor/reference-guides/data/data-core-edit-post/#removeeditorpanel
 */
const removeEditorPanels = [
	//"discussion-panel"
];

/**
 * Remove block styles
 *
 * @type {Array} Styles to remove for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */
const unregisterBlockStyles = [
	// "outline",
];

/**
 * Remove block variations
 *
 * @type {Array} Variations to remove for this block
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/
 */
const unregisterBlockVariations = [
	// "two-columns-two-thirds-one-third",
];

/**
 * Here we hook into the editor initialization and unregister the blocks,
 * remove editor panels, remove block styles, remove block variations,
 * register block styles, and register block variations – all as defined above.
 */
domReady(() => {
	registerBlockStyle(blockName, registerBlockStyles);
	registerBlockVariations.forEach((variation) => {
		registerBlockVariation(blockName, variation);
	});
	unregisterBlocks.forEach((block) => {
		unregisterBlockType(block);
	});

	// Only run if we are in the post editor
	if (null !== dispatch('core/edit-post')) {
		const { removeEditorPanel } = dispatch('core/edit-post');
		removeEditorPanels.forEach((panel) => {
			removeEditorPanel(panel);
		});
	}
	unregisterBlockStyle(blockName, unregisterBlockStyles);
	unregisterBlockVariations.forEach((variation) => {
		unregisterBlockVariation(blockName, variation);
	});
});
