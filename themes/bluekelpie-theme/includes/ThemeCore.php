<?php
/**
 * ThemeCore module.
 *
 * @package BlueKelpieTheme
 */

namespace BlueKelpieTheme;

/**
 * ThemeCore module.
 */
class ThemeCore {

	/**
	 * Plugin singleton instance.
	 *
	 * @var ThemeCore|null
	 */
	public static $instance = null;

	/**
	 * Lazy initialize the plugin
	 *
	 * @return ThemeCore
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new ThemeCore();
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin and fires an action other plugins can hook into.
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'bluekelpie_theme_before_init' );

		$modules = [
			new Assets(),
			new Blocks(),
			new BlockOverrides\CoreButton(),
		];

		foreach ( $modules as $module ) {
			$module->init();
		}

		do_action( 'bluekelpie_theme_init' );
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
		do_action( 'bluekelpie_theme_loaded' );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @return void
	 */
	public function theme_setup() {
		add_theme_support( 'editor-styles' );
		add_editor_style( '/build/css/frontend.css' );

		/**
		 * Remove support for the core block pattern library.
		 *
		 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#disabling-the-default-block-patterns
		 */
		remove_theme_support( 'core-block-patterns' );
	}
}
