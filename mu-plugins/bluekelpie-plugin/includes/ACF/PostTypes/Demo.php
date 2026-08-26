<?php
/**
 * Demo Post Type
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF\PostTypes;

use BlueKelpiePlugin\ACF\AbstractPostType;

/**
 * Demo post type.
 *
 * @phpstan-import-type FieldGroups from \BlueKelpiePlugin\ACF\AbstractPostType
 */
class Demo extends AbstractPostType {

	/**
	 * Get the post type name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bluekelpie-demo';
	}

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	public function get_singular_label() {

		return esc_html__( 'Demo', 'bluekelpie-plugin' );
	}

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	public function get_plural_label() {
		return esc_html__( 'Demos', 'bluekelpie-plugin' );
	}

	/**
	 * Get the menu icon for the post type.
	 *
	 * This can be a base64 encoded SVG, a dashicons class or 'none' to leave it empty so it can be filled with CSS.
	 *
	 * @see https://developer.wordpress.org/resource/dashicons/
	 *
	 * @return string
	 */
	public function get_menu_icon() {
		return 'dashicons-chart-pie';
	}

	/**
	 * Returns the default supported taxonomies. The subclass should declare the
	 * Taxonomies that it supports here if required.
	 *
	 * @return array<string>
	 */
	public function get_supported_taxonomies() {
		return [
			'bluekelpie-tax-demo',
		];
	}

	/**
	 * Default post type supported feature names.
	 *
	 * @return array<string>
	 */
	public function get_editor_supports() {
		$supports = [
			'title',
			'author',
			'thumbnail',
			'excerpt',
			'revisions',
		];

		return $supports;
	}

	/**
	 * Returns ACF field groups. The subclass should declare the
	 * field groups that it requires here.
	 *
	 * @return FieldGroups
	 */
	public function get_field_groups() {
		return [
			[
				'title'    => 'FAQ Data',
				'key'      => 'group_faq_fields',
				'fields'   => [
					[
						'label'    => 'Question',
						'name'     => 'faq_question',
						'key'      => 'field_faq_question',
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'    => 'Answer',
						'name'     => 'faq_answer',
						'key'      => 'field_faq_answer',
						'type'     => 'wysiwyg',
						'required' => 1,
					],
				],
				'location' => [
					[
						[
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => $this->get_name(),
						],
					],
				],
			],
		];
	}
}
