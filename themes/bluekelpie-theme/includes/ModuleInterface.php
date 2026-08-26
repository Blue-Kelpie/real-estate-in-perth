<?php
/**
 * ModuleInterface
 *
 * @package BlueKelpieTheme
 */

declare(strict_types = 1);

namespace BlueKelpieTheme;

/**
 * Interface for the Module trait.
 */
interface ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init();
}
