import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, RichText } from '@wordpress/block-editor';
import metadata from './block.json';
import '@tailwindplus/elements';
import './editor.scss';
import './style.scss';

registerBlockType(metadata.name, {
	edit: Edit,
	save: Save,
});

function Edit({ attributes, setAttributes, clientId }) {
	const blockProps = useBlockProps();
	const popoverId = `popover-${clientId}`;

	return (
		<>
			<div {...blockProps}>
				<RichText
					tagName="button"
					value={attributes.label}
					onChange={(label) => setAttributes({ label })}
					placeholder="Button label…"
					popoverTarget={popoverId}
					allowedFormats={[]}
				/>
			</div>
			<el-popover id={popoverId} popover="">
				<div
					aria-hidden="true"
					className="bottom-shadow-container"
				></div>
				<div tabIndex="0" className="container">
					<div className="content">
						<InnerBlocks />
					</div>
				</div>
			</el-popover>
		</>
	);
}

function Save() {
	return <InnerBlocks.Content />;
}
