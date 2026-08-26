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
class Integrations extends AbstractOptionsPage {

	/**
	 * Get the page title.
	 *
	 * @return string
	 */
	public function get_page_title() {
		return esc_html__( 'Integrations', 'bluekelpie-plugin' );
	}

	/**
	 * Get the menu slug.
	 *
	 * @return string
	 */
	public function get_menu_slug() {
		return 'integrations';
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
		return [
			[
				'title'        => 'Integrations',
				'key'          => 'integrations',
				'fields'       => [
					[
						'label'         => 'Google Integrations ID',
						'name'          => 'google_integrations_id',
						'key'           => 'field_google_integrations_id',
						'instructions'  => 'Enter your Google Integrations ID.',
						'type'          => 'text',
						'required'      => 0,
						'default_value' => '',
					],
					[
						'label'         => 'Google Tag Manager Container ID',
						'name'          => 'google_tag_manager_container_id',
						'key'           => 'field_google_tag_manager_container_id',
						'instructions'  => 'Enter your Google Tag Manager Container ID.',
						'type'          => 'text',
						'required'      => 0,
						'default_value' => '',
					],
					[
						'label'         => 'Facebook Pixel ID',
						'name'          => 'facebook_pixel_id',
						'key'           => 'field_facebook_pixel_id',
						'instructions'  => 'Enter your Facebook Pixel ID.',
						'type'          => 'text',
						'required'      => 0,
						'default_value' => '',
					],
				],
				'location'     => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => $this->get_menu_slug(),
						],
					],
				],
				'show_in_rest' => true,
			],
		];
	}
}
