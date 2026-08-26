const [
	scriptConfig,
	moduleConfig,
] = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

const blockEntries = glob
	.sync('src/css/blocks/**/*.scss')
	.reduce((entries, file) => {
		const key = file.replace('src/', '').replace('.scss', '');
		entries[key] = path.resolve(__dirname, file);
		return entries;
	}, {});

const pageEntries = glob
	.sync('src/css/pages/**/*.scss')
	.reduce((entries, file) => {
		const key = file.replace('src/', '').replace('.scss', '');
		entries[key] = path.resolve(__dirname, file);
		return entries;
	}, {});

const pageEntriesJS = glob
	.sync('src/js/pages/**/*.ts')
	.reduce((entries, file) => {
		const key = file.replace('src/', '').replace('.ts', '');
		entries[key] = path.resolve(__dirname, file);
		return entries;
	}, {});

module.exports = [
	{
		...scriptConfig,
		resolve: {
			...scriptConfig.resolve,
			alias: {
				...scriptConfig.resolve?.alias,
				'@svg': path.resolve(__dirname, 'src/svg'),
			},
		},
		entry: {
			...scriptConfig.entry(),
			'js/block-editor-js': path.resolve(
				__dirname,
				'src/js/block-editor/index.ts'
			),
			'js/frontend': path.resolve(__dirname, 'src/js/frontend.ts'),
			'css/frontend': path.resolve(__dirname, 'src/css/frontend.scss'),

			...blockEntries,
			...pageEntries,
		},
	},
	{
		...moduleConfig,
		entry: {
			...moduleConfig.entry(),
			...pageEntriesJS,
			'js/gform-store': path.resolve(__dirname, 'src/js/gform-store.ts'),
		},
	},
];
