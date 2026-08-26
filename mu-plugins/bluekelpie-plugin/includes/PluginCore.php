<?php
/**
 * PluginCore module.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin;

/**
 * PluginCore module.
 */
class PluginCore {

	/**
	 * Plugin singleton instance.
	 *
	 * @var PluginCore|null
	 */
	public static $instance = null;

	/**
	 * Lazy initialize the plugin
	 *
	 * @return PluginCore
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new PluginCore();
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin and fires an action other plugins can hook into.
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'bluekelpie_plugin_before_init' );

		$modules = [
			new ACF\Analytics(),
			new Assets(),
			new DevelopmentDependencies(),
			new ACF\OptionsPage\Integrations(),
			new GravityForms\GravityForms(),
			new GravityForms\Demo( new Api\DemoClient() ),
		];

		foreach ( $modules as $module ) {
			$module->init();
		}

		do_action( 'bluekelpie_plugin_init' );
		do_action( 'bluekelpie_plugin_loaded' );
	}

	/**
	 * Activate the plugin.
	 *
	 * @return void
	 */
	public function activate() {
		// Do nothing.
	}

	/**
	 * Deactivate the plugin.
	 *
	 * @return void
	 */
	public function deactivate() {
		// Do nothing.
	}
}
