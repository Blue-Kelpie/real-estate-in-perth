const [
	scriptConfig,
	moduleConfig,
] = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const glob = require('glob');

const scssEntries = glob.sync('src/css/**/*.scss').reduce((entries, file) => {
	const key = file.replace('src/', '').replace('.scss', '');
	entries[key] = path.resolve(__dirname, file);
	return entries;
}, {});

module.exports = [
	{
		...scriptConfig,
		entry: {
			...scriptConfig.entry(),
			...scssEntries,
		},
	},
	moduleConfig,
];
