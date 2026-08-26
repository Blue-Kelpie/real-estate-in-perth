<?php
/**
 * Plugin Name:       BlueKelpie Plugin Scaffold
 * Description:       A brief description of the plugin.
 * Version:           0.1.0
 * Requires at least: 4.9
 * Requires PHP:      8.2
 * Author:            Blue Kelpie
 * Author URI:        https://bluekelpie.com.au/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bluekelpie-plugin
 * Domain Path:       /languages
 * Update URI:        http://github.com/
 *
 * @package           BlueKelpiePlugin
 */

// Useful global constants.
define( 'BLUEKELPIE_PLUGIN_VERSION', '0.1.0' );
define( 'BLUEKELPIE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BLUEKELPIE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BLUEKELPIE_PLUGIN_SRC', BLUEKELPIE_PLUGIN_PATH . 'src/' );
define( 'BLUEKELPIE_PLUGIN_BUILD_URL', BLUEKELPIE_PLUGIN_URL . 'build/' );
define( 'BLUEKELPIE_PLUGIN_BUILD_PATH', BLUEKELPIE_PLUGIN_PATH . 'build/' );

// Bail if Composer autoloader is not found.
if ( ! file_exists( BLUEKELPIE_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	throw new \Exception(
		'Vendor autoload file not found. Please run `composer install`.'
	);
}

require_once BLUEKELPIE_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * Get PluginCore instance.
 *
 * @return \BlueKelpiePlugin\PluginCore
 */
function bluekelpie_plugin_get_core() {
	return BlueKelpiePlugin\PluginCore::get_instance();
}
bluekelpie_plugin_get_core()->init();

// Activation/Deactivation.
register_activation_hook( __FILE__, [ bluekelpie_plugin_get_core(), 'activate' ] );
register_deactivation_hook( __FILE__, [ bluekelpie_plugin_get_core(), 'deactivate' ] );
