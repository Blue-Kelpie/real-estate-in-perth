<?php
/**
 * Helper functions.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Get asset info from a built asset file.
 *
 * @see https://github.com/10up/wp-framework/blob/develop/src/Assets/GetAssetInfo.php
 *
 * @param string      $path       Asset path relative to the build directory.
 * @param string|null $attribute  Optional attribute to return (version|dependencies).
 * @param string|null $build_path Optional build directory path. Defaults to BLUEKELPIE_PLUGIN_BUILD_PATH.
 *
 * @return ($attribute is null ? array{version: string, dependencies: array<string>} : $attribute is 'dependencies' ? array<string> : string)
 */
function get_asset_info( $path, $attribute = null, $build_path = null ) {
	$build_path = $build_path ?? BLUEKELPIE_PLUGIN_BUILD_PATH;
	$asset_file = $build_path . $path . '.asset.php';

	if ( file_exists( $asset_file ) ) {
		/** @var array{version: string, dependencies: array<string>} $asset */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$asset = require $asset_file;
	} else {
		$asset = [
			'version'      => BLUEKELPIE_PLUGIN_VERSION,
			'dependencies' => [],
		];
	}

	switch ( $attribute ) {
		case null:
			return $asset;

		case 'dependencies':
			return $asset['dependencies'];

		case 'version':
			return $asset['version'];

		default:
			return '';
	}
}
