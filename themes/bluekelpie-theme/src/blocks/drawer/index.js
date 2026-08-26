import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import metadata from './block.json';
import '@tailwindplus/elements';
import './editor.scss';
import './style.scss';

registerBlockType(metadata.name, {
	edit: Edit,
	save: Save,
});

function Edit() {
	const [open, setOpen] = useState(false);
	const blockProps = useBlockProps();

	return (
		<>
			<div {...blockProps}>
				<button onClick={() => setOpen(true)}>Show</button>
			</div>
			<el-dialog>
				<dialog id="drawer" {...(open ? { open } : null)}>
					<el-dialog-backdrop></el-dialog-backdrop>
					<div tabIndex="0" className="panel-container">
						<el-dialog-panel>
							<div className="panel">
								<div className="panel-head-container">
									<div className="panel-head">
										<h2 id="menu" className="sr-only">
											Menu
										</h2>
										<div className="button-container">
											<button
												type="button"
												onClick={() => setOpen(false)}
											>
												<span className="sr-only">
													Close menu
												</span>
												<svg
													viewBox="0 0 24 24"
													fill="none"
													stroke="currentColor"
													strokeWidth="1.5"
													data-slot="icon"
													aria-hidden="true"
												>
													<path
														d="M6 18 18 6M6 6l12 12"
														strokeLinecap="round"
														strokeLinejoin="round"
													/>
												</svg>
											</button>
										</div>
									</div>
								</div>
								<div className="panel-body-container">
									<InnerBlocks />
								</div>
							</div>
						</el-dialog-panel>
					</div>
				</dialog>
			</el-dialog>
		</>
	);
}

function Save() {
	return <InnerBlocks.Content />;
}
