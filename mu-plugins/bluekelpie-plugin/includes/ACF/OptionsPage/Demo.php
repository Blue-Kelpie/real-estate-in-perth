<?php
/**
 * Demo.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF\OptionsPage;

use BlueKelpiePlugin\ACF\AbstractOptionsPage;

/**
 * Demo.
 *
 * @phpstan-import-type FieldGroups from \BlueKelpiePlugin\ACF\AbstractPostType
 */
class Demo extends AbstractOptionsPage {

	/**
	 * Get the page title.
	 *
	 * @return string
	 */
	public function get_page_title() {
		return esc_html__( 'Demo', 'bluekelpie-plugin' );
	}

	/**
	 * Get the menu slug.
	 *
	 * @return string
	 */
	public function get_menu_slug() {
		return 'demo';
	}

	/**
	 * Get the menu position.
	 *
	 * @return int|null
	 */
	public function get_menu_position() {
		return 3;
	}

	/**
	 * Returns ACF field groups for this options page.
	 *
	 * @return FieldGroups
	 */
	public function get_field_groups() {
		return [];
	}
}
