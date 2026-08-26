<?php
/**
 * Demo Taxonomy
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF\Taxonomies;

use BlueKelpiePlugin\ACF\AbstractTaxonomy;

/**
 * Demo Taxonomy.
 *
 * @phpstan-import-type FieldGroups from \BlueKelpiePlugin\ACF\AbstractPostType
 */
class Demo extends AbstractTaxonomy {

	/**
	 * Get the taxonomy name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bluekelpie-tax-demo';
	}

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	public function get_singular_label() {
		return esc_html__( 'Demo Term', 'bluekelpie-plugin' );
	}

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	public function get_plural_label() {
		return esc_html__( 'Demo Terms', 'bluekelpie-plugin' );
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
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => $this->get_name(),
						],
					],
				],
			],
		];
	}
}
