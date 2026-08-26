<?php
/**
 * Login.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\Admin;

use BlueKelpiePlugin\ModuleInterface;

/**
 * Login.
 */
class Login implements ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'login_head', [ $this, 'enqueue_login_styles' ] );
		add_filter( 'login_headerurl', [ $this, 'change_login_logo_url' ] );
	}

	/**
	 * Enqueue login styles
	 *
	 * @return void
	 */
	public function enqueue_login_styles() {
		wp_enqueue_style( 'bluekelpie-plugin-sign-in-css' );
	}

	/**
	 * Change the URL of the login logo.
	 *
	 * @param string $url Url.
	 * @return string
	 */
	public function change_login_logo_url( $url ) {
		return 'https://bluekelpie.com.au/';
	}
}
