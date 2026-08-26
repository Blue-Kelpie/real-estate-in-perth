<?php
/**
 * Theme constants and setup functions
 *
 * @package BlueKelpieTheme
 */

// Useful global constants.
define( 'BLUEKELPIE_THEME_VERSION', '1.0.0' );
define( 'BLUEKELPIE_THEME_TEMPLATE_URL', get_template_directory_uri() . '/' );
define( 'BLUEKELPIE_THEME_PATH', get_template_directory() . '/' );
define( 'BLUEKELPIE_THEME_BUILD_PATH', BLUEKELPIE_THEME_PATH . 'build/' );
define( 'BLUEKELPIE_THEME_BUILD_URL', BLUEKELPIE_THEME_TEMPLATE_URL . 'build/' );
define( 'BLUEKELPIE_THEME_SRC_PATH', BLUEKELPIE_THEME_PATH . 'src/' );
define( 'BLUEKELPIE_THEME_SRC_URL', BLUEKELPIE_THEME_TEMPLATE_URL . 'src/' );
define( 'BLUEKELPIE_THEME_INC_PATH', BLUEKELPIE_THEME_PATH . 'includes/' );

// Require Composer autoloader.
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	throw new Exception( 'Please run `composer install` in your theme directory.' );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Get ThemeCore instance.
 *
 * @return \BlueKelpieTheme\ThemeCore
 */
function bluekelpie_theme_get_core() {
	return BlueKelpieTheme\ThemeCore::get_instance();
}

bluekelpie_theme_get_core()->init();
