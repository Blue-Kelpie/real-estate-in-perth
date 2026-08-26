<?php
/**
 * Helper functions.
 *
 * @package BlueKelpieTheme
 */

namespace BlueKelpieTheme;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Get an svg's markup.
 *
 * @param string $icon_name Icon name to retrieve.
 * @return string
 */
function get_icon_svg( $icon_name ) {
	if ( ! file_exists( BLUEKELPIE_THEME_SRC_PATH . 'svg/' . $icon_name . '.svg' ) ) {
		return '';
	}

	$response = wp_remote_get( BLUEKELPIE_THEME_SRC_URL . "svg/$icon_name.svg" );
	if ( is_wp_error( $response ) ) {
		return '';
	}
	return $response['body'];
}
