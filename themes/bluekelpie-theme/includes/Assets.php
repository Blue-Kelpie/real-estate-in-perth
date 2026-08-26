<?php
/**
 * Assets module.
 *
 * @package BlueKelpieTheme
 */

namespace BlueKelpieTheme;

use function BlueKelpiePlugin\get_asset_info;

/**
 * Assets module.
 */
class Assets implements ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'maybe_enqueue_page_stylesheet' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'maybe_enqueue_editor_page_stylesheet' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'maybe_enqueue_page_module' ] );
	}

	/**
	 * Enqueue assets on the front-end.
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets() {
		wp_enqueue_style(
			'bluekelpie-theme-frontend',
			BLUEKELPIE_THEME_BUILD_URL . 'css/frontend.css',
			[],
			get_asset_info( 'css/frontend', 'version', BLUEKELPIE_THEME_BUILD_PATH )
		);

		wp_enqueue_script(
			'bluekelpie-theme-frontend',
			BLUEKELPIE_THEME_BUILD_URL . 'js/frontend.js',
			get_asset_info( 'js/frontend', 'dependencies', BLUEKELPIE_THEME_BUILD_PATH ),
			get_asset_info( 'js/frontend', 'version', BLUEKELPIE_THEME_BUILD_PATH ),
			true
		);

		wp_enqueue_script_module(
			'bluekelpie-theme-gform-store',
			BLUEKELPIE_THEME_BUILD_URL . 'js/gform-store.js',
			get_asset_info( 'js/gform-store', 'dependencies', BLUEKELPIE_THEME_BUILD_PATH ),
			get_asset_info( 'js/gform-store', 'version', BLUEKELPIE_THEME_BUILD_PATH ),
		);
	}

	/**
	 * Enqueue core block filters, styles and variations.
	 *
	 * @return void
	 */
	public function enqueue_block_editor_scripts() {
		wp_enqueue_script(
			'block-editor-js',
			BLUEKELPIE_THEME_BUILD_URL . 'js/block-editor-js.js',
			get_asset_info( 'block-editor-js', 'dependencies', BLUEKELPIE_THEME_BUILD_PATH ),
			get_asset_info( 'block-editor-js', 'version', BLUEKELPIE_THEME_BUILD_PATH ),
			true
		);
	}

	/**
	 * Enqueue page stylesheet.
	 *
	 * @return void
	 */
	public function maybe_enqueue_page_stylesheet() {
		$slug = $this->get_current_slug();
		if ( ! file_exists( BLUEKELPIE_THEME_BUILD_PATH . "css/pages/$slug.css" ) ) {
			return;
		}
		wp_enqueue_style( "bluekelpie-block-theme-$slug", BLUEKELPIE_THEME_BUILD_URL . "css/pages/$slug.css", [], get_asset_info( "css/pages/$slug", 'version', BLUEKELPIE_THEME_BUILD_PATH ) );
	}

	/**
	 * Enqueue page stylesheet in the block editor.
	 *
	 * @return void
	 */
	public function maybe_enqueue_editor_page_stylesheet() {
		$slug = $this->get_current_slug();
		if ( '' === $slug || ! file_exists( BLUEKELPIE_THEME_BUILD_PATH . "css/pages/$slug.css" ) ) {
			return;
		}
		add_editor_style( "/build/css/pages/$slug.css" );
	}

	/**
	 * Enqueue page script.
	 *
	 * @return void
	 */
	public function maybe_enqueue_page_module() {
		$slug = $this->get_current_slug();
		if ( ! file_exists( BLUEKELPIE_THEME_BUILD_PATH . "js/pages/$slug.js" ) ) {
			return;
		}

		wp_enqueue_script_module(
			"bluekelpie-block-theme-$slug",
			BLUEKELPIE_THEME_BUILD_URL . "js/pages/$slug.js",
			get_asset_info( "js/pages/$slug", 'dependencies', BLUEKELPIE_THEME_BUILD_PATH ),
			get_asset_info( "js/pages/$slug", 'version', BLUEKELPIE_THEME_BUILD_PATH )
		);
	}

	/**
	 * Returns the slug for the current page.
	 *
	 * @return string
	 */
	public function get_current_slug() {
		$queried_obj = get_queried_object();
		if ( ! $queried_obj ) {
			$queried_obj = get_post(); // Block-editor support.
		}

		$slug = '';
		if ( $queried_obj instanceof \WP_Post ) {
			if ( 'page' === $queried_obj->post_type ) {
				$slug = $queried_obj->post_name;
			} else {
				$slug = $queried_obj->post_type;
			}
		}

		if ( $queried_obj instanceof \WP_Term ) {
			$taxonomy = get_taxonomy( $queried_obj->taxonomy );
			if ( $taxonomy instanceof \WP_Taxonomy ) {
				// Assuming the taxonomy is only assigned to one post type.
				$slug = $taxonomy->object_type[0];
			}
		}

		if ( $queried_obj instanceof \WP_Post_Type ) {
			$slug = $queried_obj->name;
		}

		// Differentiate wp_post pages from wp_post_type and wp_term pages.
		if ( is_archive() ) {
			$slug = "$slug-archive";
		}

		return $slug;
	}
}
