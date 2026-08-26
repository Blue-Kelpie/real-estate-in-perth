<?php
/**
 * Gutenberg blocks setup.
 *
 * @package BlueKelpieTheme
 */

namespace BlueKelpieTheme;

use function BlueKelpiePlugin\get_asset_info;

/**
 * Blocks module.
 */
class Blocks implements ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'enqueue_block_specific_styles' ], 10, 0 );
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_action( 'init', [ $this, 'register_block_pattern_categories' ] );
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		// Prevents third-party blocks from being suggested in the block inserter.
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	}

	/**
	 * Register blocks.
	 *
	 * @return void
	 */
	public function register_blocks() {
		$blocks_manifest_path = BLUEKELPIE_THEME_BUILD_PATH . 'blocks-manifest.php';
		if ( ! file_exists( $blocks_manifest_path ) ) {
				return;
		}

		if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
			wp_register_block_types_from_metadata_collection(
				BLUEKELPIE_THEME_BUILD_PATH . 'blocks',
				$blocks_manifest_path
			);
		} elseif ( function_exists( 'wp_register_block_metadata_collection' ) ) {
			wp_register_block_metadata_collection(
				BLUEKELPIE_THEME_BUILD_PATH . 'blocks',
				$blocks_manifest_path
			);
		} else {
			// Generic fallback.
			$manifest_data = include $blocks_manifest_path;

			if ( is_array( $manifest_data ) ) {
				foreach ( array_keys( $manifest_data ) as $block_type ) {
					register_block_type( BLUEKELPIE_THEME_BUILD_PATH . 'blocks/' . $block_type );
				}
			}
		}
	}

	/**
	 * Enqueue block specific styles.
	 *
	 * @return void
	 */
	public function enqueue_block_specific_styles() {
		$stylesheets = glob( BLUEKELPIE_THEME_BUILD_PATH . 'css/blocks/*/*.css' );

		if ( empty( $stylesheets ) ) {
			return;
		}

		foreach ( $stylesheets as $stylesheet_path ) {
			$block_type = str_replace( BLUEKELPIE_THEME_BUILD_PATH . 'css/blocks/', '', $stylesheet_path );
			$block_type = str_replace( '.css', '', $block_type );

			wp_register_style(
				"bluekelpie-theme-{$block_type}",
				BLUEKELPIE_THEME_BUILD_URL . 'css/blocks/' . $block_type . '.css',
				get_asset_info( 'css/blocks/' . $block_type, 'dependencies', BLUEKELPIE_THEME_BUILD_PATH ),
				get_asset_info( 'css/blocks/' . $block_type, 'version', BLUEKELPIE_THEME_BUILD_PATH ),
			);

			wp_enqueue_block_style(
				$block_type,
				[
					'handle' => "bluekelpie-theme-{$block_type}",
					'path'   => $stylesheet_path,
				]
			);
		}
	}

	/**
	 * Register block pattern categories
	 *
	 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
	 * @return void
	 */
	public function register_block_pattern_categories() {
		register_block_pattern_category(
			'bluekelpie-theme',
			[ 'label' => __( 'BlueKelpie Theme', 'bluekelpie-theme' ) ]
		);
	}
}
