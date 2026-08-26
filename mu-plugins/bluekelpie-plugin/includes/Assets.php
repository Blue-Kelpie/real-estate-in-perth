<?php
/**
 * Assets module.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin;

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
		$this->register();
	}

	/**
	 * Register any hooks and filters.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_stylesheets' ], 10 );
		add_action( 'init', [ $this, 'register_scripts' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'enqueue_login_scripts' ] );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_iframe_assets' ] );
	}

	/**
	 * Register stylesheets.
	 *
	 * @return void
	 */
	public function register_stylesheets() {
		$stylesheets = glob( BLUEKELPIE_PLUGIN_BUILD_PATH . 'css/**/*.css' );

		if ( empty( $stylesheets ) ) {
			return;
		}

		foreach ( $stylesheets as $stylesheet_path ) {
			$file_name = str_replace( BLUEKELPIE_PLUGIN_BUILD_PATH . 'css/', '', $stylesheet_path );
			$file_name = str_replace( '.css', '', $file_name );

			wp_register_style(
				"bluekelpie-plugin-{$file_name}",
				BLUEKELPIE_PLUGIN_BUILD_URL . 'css/' . $file_name . '.css',
				get_asset_info( $file_name, 'dependencies' ),
				get_asset_info( $file_name, 'version' ),
			);
		}
	}

	/**
	 * Register scripts.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$scripts = glob( BLUEKELPIE_PLUGIN_BUILD_PATH . '**/*.js' );

		if ( empty( $scripts ) ) {
			return;
		}

		foreach ( $scripts as $script_path ) {
			$file_name = str_replace( BLUEKELPIE_PLUGIN_BUILD_PATH . '/js', '', $script_path );
			$file_name = str_replace( BLUEKELPIE_PLUGIN_BUILD_PATH . 'js/', '', $script_path );
			$file_name = str_replace( '.js', '', $file_name );

				wp_register_script(
					"bluekelpie-plugin-{$file_name}",
					BLUEKELPIE_PLUGIN_BUILD_URL . 'js/' . $file_name . '.js',
					get_asset_info( $file_name, 'dependencies' ),
					get_asset_info( $file_name, 'version' ),
					[ 'in_footer' => true ]
				);
		}
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'bluekelpie-plugin-admin/login' ); // for block editor sign-out prompt.
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function enqueue_login_scripts() {
		wp_enqueue_style( 'bluekelpie-plugin-admin/login' );
	}

	/**
	 * Enqueue block editor iframe assets.
	 * The `enqueue_block_assets` action is triggered both on the front-end and in the editor iframe.
	 *
	 * @return void
	 */
	public function enqueue_block_editor_iframe_assets() {
		if ( is_admin() ) {
			wp_enqueue_style( 'bluekelpie-plugin-admin/editor-iframe' );
		}
	}
}
