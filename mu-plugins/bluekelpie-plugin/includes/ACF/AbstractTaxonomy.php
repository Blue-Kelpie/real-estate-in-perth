<?php
/**
 * AbstractTaxonomy
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF;

/**
 * Abstract Taxonomy.
 *
 * @phpstan-import-type FieldGroups from \BlueKelpiePlugin\ACF\AbstractPostType
 * @phpstan-import-type Options from \BlueKelpiePlugin\ACF\AbstractPostType
 */
abstract class AbstractTaxonomy extends BaseAcfModule {

	/**
	 * Used to alter the order in which classes are initialized.
	 *
	 * Lower number will be initialized first.
	 *
	 * @return int
	 */
	public function load_order() {
		return 9;
	}

	/**
	 * Get the taxonomy name.
	 *
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Get the singular taxonomy label.
	 *
	 * @return string
	 */
	abstract public function get_singular_label();

	/**
	 * Get the plural taxonomy label.
	 *
	 * @return string
	 */
	abstract public function get_plural_label();

	/**
	 * Is the taxonomy hierarchical?
	 *
	 * @return bool
	 */
	public function is_hierarchical() {
		return false;
	}

	/**
	 * Register hooks and actions.
	 *
	 * @uses $this->get_name() to get the taxonomy's slug.
	 * @return bool
	 */
	public function register() {
		register_taxonomy(
			$this->get_name(),
			$this->get_post_types(),
			$this->get_options()
		);
		$this->register_field_groups();

		$this->after_register();

		return true;
	}

	/**
	 * Get the options for the taxonomy.
	 *
	 * @return Options
	 */
	public function get_options() {
		return [
			'labels'            => $this->get_labels(),
			'hierarchical'      => $this->is_hierarchical(),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'public'            => true,
		];
	}

	/**
	 * Get the labels for the taxonomy.
	 *
	 * @return array<string, string>
	 */
	public function get_labels() {
		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		// phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment -- ignoring template strings without translators placeholder since this is dynamic
		$labels = [
			'name'                       => $plural_label, // Already translated via get_plural_label().
			'singular_name'              => $singular_label, // Already translated via get_singular_label().
			'search_items'               => sprintf( __( 'Search %s', 'bluekelpie-plugin' ), $plural_label ),
			'popular_items'              => sprintf( __( 'Popular %s', 'bluekelpie-plugin' ), $plural_label ),
			'all_items'                  => sprintf( __( 'All %s', 'bluekelpie-plugin' ), $plural_label ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'bluekelpie-plugin' ), $singular_label ),
			'update_item'                => sprintf( __( 'Update %s', 'bluekelpie-plugin' ), $singular_label ),
			'add_new_item'               => sprintf( __( 'Add %s', 'bluekelpie-plugin' ), $singular_label ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'bluekelpie-plugin' ), $singular_label ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'not_found'                  => sprintf( __( 'No %s found.', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'not_found_in_trash'         => sprintf( __( 'No %s found in Trash.', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'view_item'                  => sprintf( __( 'View %s', 'bluekelpie-plugin' ), $singular_label ),
		];
		// phpcs:enable WordPress.WP.I18n.MissingTranslatorsComment

		return $labels;
	}

	/**
	 * Setting the post types to null to ensure no post type is registered with
	 * this taxonomy. Post Type classes declare their supported taxonomies.
	 *
	 * @return array<string>
	 */
	public function get_post_types() {
		return [];
	}

	/**
	 * Run any code after the taxonomy has been registered.
	 *
	 * @return void
	 */
	public function after_register() {
		// Do nothing.
	}

	/**
	 * Registers the taxonomies declared with the current post type.
	 *
	 * @return void
	 */
	public function register_field_groups() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$field_groups = $this->get_field_groups();
		if ( ! empty( $field_groups ) ) {
			foreach ( $field_groups as $field_group ) {
				acf_add_local_field_group( $field_group );
			}
		}
	}

	/**
	 * Returns ACF field groups. The subclass should declare the
	 * field groups that it requires here.
	 *
	 * @return FieldGroups
	 */
	public function get_field_groups() {
		return [];
	}
}
