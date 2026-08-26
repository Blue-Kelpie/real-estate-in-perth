import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import metadata from './block.json';
import './editor.scss';

registerBlockType(metadata.name, {
	edit: Edit,
});

function Edit() {
	const blockProps = useBlockProps();
	return <div {...blockProps}>Editor</div>;
}
