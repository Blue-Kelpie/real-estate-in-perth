<?php
/**
 * ModuleInterface
 *
 * @package BlueKelpiePlugin
 */

declare(strict_types = 1);

namespace BlueKelpiePlugin;

/**
 * Interface for modules.
 */
interface ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init();
}
