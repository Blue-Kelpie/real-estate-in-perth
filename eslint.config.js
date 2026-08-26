const wpPlugin = require('@wordpress/eslint-plugin');

module.exports = [
	{
		ignores: ['**/build/**', '**/vendor/**', '**/node_modules/**'],
	},
	...wpPlugin.configs.recommended,
	{
		rules: {
			'import/no-unresolved': ['error', { ignore: ['@wordpress/.*'] }],
		},
	},
];
