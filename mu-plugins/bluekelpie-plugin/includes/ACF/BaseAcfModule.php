<?php
/**
 * Base ACF Module.
 *
 * Base class for ACF modules. Defers registration until ACF Pro is active.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF;

use BlueKelpiePlugin\ModuleInterface;

/**
 * Base ACF Module.
 */
abstract class BaseAcfModule implements ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'maybe_register' ] );
	}

	/**
	 * Maybe register the module.
	 *
	 * @return void
	 */
	public function maybe_register() {
		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) && class_exists( 'ACF' ) ) {
			$this->register();
		}
	}

	/**
	 * Registers the module with WordPress and ACF.
	 *
	 * @return void
	 */
	abstract public function register();
}
