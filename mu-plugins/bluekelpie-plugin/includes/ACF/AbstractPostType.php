<?php
/**
 * Abstract Post Type.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF;

/**
 * Abstract Post Type.
 *
 * @phpstan-type FieldGroups list<array{
 *  key: string,
 *   title: string,
 *   fields: array<int, array{
 *     key: string,
 *     label: string,
 *     name: string,
 *     type: string,
 *     instructions?: string,
 *     required?: int|bool,
 *     conditional_logic?: int|bool|array<int, array<int, array{
 *       field: string,
 *       operator: string,
 *       value: string
 *     }>>,
 *     wrapper?: array{
 *       width?: string,
 *       class?: string,
 *       id?: string
 *     },
 *     default_value?: mixed,
 *     placeholder?: string,
 *     prepend?: string,
 *     append?: string,
 *     maxlength?: int,
 *     readonly?: int|bool,
 *     disabled?: int|bool,
 *     choices?: array<string, string>,
 *     multiple?: int|bool,
 *     allow_null?: int|bool,
 *     ui?: int|bool,
 *     ajax?: int|bool,
 *     layout?: string,
 *     return_format?: string,
 *     sub_fields?: array<int, array<string, mixed>>,
 *     min?: int,
 *     max?: int,
 *     layouts?: array<int, array{
 *       key: string,
 *       name: string,
 *       label: string,
 *       display: string,
 *       sub_fields: array<int, array<string, mixed>>,
 *       min?: int,
 *       max?: int
 *     }>
 *   }>,
 *   location: array<int, array<int, array{
 *     param: string,
 *     operator: string,
 *     value: string
 *   }>>,
 *   menu_order?: int,
 *   position?: 'acf_after_title'|'normal'|'side',
 *   style?: 'default'|'seamless',
 *   label_placement?: 'top'|'left',
 *   instruction_placement?: 'label'|'field',
 *   hide_on_screen?: array<int, string>,
 *   active?: bool,
 *   description?: string,
 *   show_in_rest?: int|bool
 * }>
 *
 * @phpstan-type Options array{
 *      labels?: array<string|int, string>,
 *      description?: string,
 *      public?: bool,
 *      hierarchical?: bool,
 *      exclude_from_search?: bool,
 *      publicly_queryable?: bool,
 *      show_ui?: bool,
 *      show_in_menu?: bool,
 *      show_in_nav_menus?: bool,
 *      show_in_admin_bar?: bool,
 *      menu_position?: int,
 *      menu_icon?: string,
 *      capability_type?: string|array<int, string>,
 *      capabilities?: array<string, string>,
 *      map_meta_cap?: bool,
 *      supports?: array<string>|false,
 *      register_meta_box_cb?: callable,
 *      taxonomies?: array<string>,
 *      has_archive?: bool|string,
 *      rewrite?: bool|array{
 *          slug?: string,
 *          with_front?: bool,
 *          feeds?: bool,
 *          pages?: bool,
 *          ep_mask?: int,
 *      },
 *      query_var?: bool|string,
 *      can_export?: bool,
 *      delete_with_user?: bool,
 *      show_in_rest?: bool,
 *      rest_base?: string,
 *      rest_namespace?: string,
 *      rest_controller_class?: string,
 *      _builtin?: bool,
 *      template?: array<array<string, mixed>>,
 *      template_lock?: string|false,
 *  }
 */
abstract class AbstractPostType extends BaseAcfModule {

	/**
	 * Get the post type name.
	 *
	 * @return lowercase-string&non-empty-string
	 */
	abstract public function get_name();

	/**
	 * Get the singular post type label.
	 *
	 * @return string
	 */
	abstract public function get_singular_label();

	/**
	 * Get the plural post type label.
	 *
	 * @return string
	 */
	abstract public function get_plural_label();

	/**
	 * Get the menu icon for the post type.
	 * This can be a base64 encoded SVG, a dashicons class or 'none' to leave it empty so it can be filled with CSS.
	 *
	 * @see https://developer.wordpress.org/resource/dashicons/
	 *
	 * @return string
	 */
	abstract public function get_menu_icon();

	/**
	 * Get the menu position for the post type.
	 *
	 * @return int|null
	 */
	public function get_menu_position() {
		return null;
	}

	/**
	 * Is the post type hierarchical?
	 *
	 * @return bool
	 */
	public function is_hierarchical() {
		return false;
	}

	/**
	 * Default post type supported feature names.
	 *
	 * @return array<string>
	 */
	public function get_editor_supports() {
		$supports = [
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'revisions',
		];

		return $supports;
	}

	/**
	 * Get the options for the post type.
	 *
	 * @return Options
	 */
	public function get_options() {
		$options = [
			'labels'            => $this->get_labels(),
			'public'            => true,
			'has_archive'       => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => true,
			'supports'          => $this->get_editor_supports(),
			'menu_icon'         => $this->get_menu_icon(),
			'hierarchical'      => $this->is_hierarchical(),
		];

		$menu_position = $this->get_menu_position();

		if ( null !== $menu_position ) {
			$options['menu_position'] = $menu_position;
		}

		return $options;
	}

	/**
	 * Get the labels for the post type.
	 *
	 * @return array<string>
	 */
	public function get_labels() {
		$plural_label   = $this->get_plural_label();
		$singular_label = $this->get_singular_label();

		// phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment -- ignoring template strings without translators placeholder since this is dynamic
		$labels = [
			'name'                     => $plural_label,
			// Already translated via get_plural_label().
			'singular_name'            => $singular_label,
			// Already translated via get_singular_label().
			'add_new'                  => sprintf( __( 'Add New %s', 'bluekelpie-plugin' ), $singular_label ),
			'add_new_item'             => sprintf( __( 'Add New %s', 'bluekelpie-plugin' ), $singular_label ),
			'edit_item'                => sprintf( __( 'Edit %s', 'bluekelpie-plugin' ), $singular_label ),
			'new_item'                 => sprintf( __( 'New %s', 'bluekelpie-plugin' ), $singular_label ),
			'view_item'                => sprintf( __( 'View %s', 'bluekelpie-plugin' ), $singular_label ),
			'view_items'               => sprintf( __( 'View %s', 'bluekelpie-plugin' ), $plural_label ),
			'search_items'             => sprintf( __( 'Search %s', 'bluekelpie-plugin' ), $plural_label ),
			'not_found'                => sprintf( __( 'No %s found.', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'not_found_in_trash'       => sprintf( __( 'No %s found in Trash.', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'parent_item_colon'        => sprintf( __( 'Parent %s:', 'bluekelpie-plugin' ), $plural_label ),
			'all_items'                => sprintf( __( 'All %s', 'bluekelpie-plugin' ), $plural_label ),
			'archives'                 => sprintf( __( '%s Archives', 'bluekelpie-plugin' ), $singular_label ),
			'attributes'               => sprintf( __( '%s Attributes', 'bluekelpie-plugin' ), $singular_label ),
			'insert_into_item'         => sprintf( __( 'Insert into %s', 'bluekelpie-plugin' ), strtolower( $singular_label ) ),
			'uploaded_to_this_item'    => sprintf( __( 'Uploaded to this %s', 'bluekelpie-plugin' ), strtolower( $singular_label ) ),
			'filter_items_list'        => sprintf( __( 'Filter %s list', 'bluekelpie-plugin' ), strtolower( $plural_label ) ),
			'items_list_navigation'    => sprintf( __( '%s list navigation', 'bluekelpie-plugin' ), $plural_label ),
			'items_list'               => sprintf( __( '%s list', 'bluekelpie-plugin' ), $plural_label ),
			'item_published'           => sprintf( __( '%s published.', 'bluekelpie-plugin' ), $singular_label ),
			'item_published_privately' => sprintf( __( '%s published privately.', 'bluekelpie-plugin' ), $singular_label ),
			'item_reverted_to_draft'   => sprintf( __( '%s reverted to draft.', 'bluekelpie-plugin' ), $singular_label ),
			'item_scheduled'           => sprintf( __( '%s scheduled.', 'bluekelpie-plugin' ), $singular_label ),
			'item_updated'             => sprintf( __( '%s updated.', 'bluekelpie-plugin' ), $singular_label ),
			'menu_name'                => $plural_label,
			'name_admin_bar'           => $singular_label,
		];
		// phpcs:enable WordPress.WP.I18n.MissingTranslatorsComment

		return $labels;
	}

	/**
	 * Registers a post type and associates its taxonomies.
	 *
	 * @uses $this->get_name() to get the post's type name.
	 * @return Bool Whether this theme has supports for this post type.
	 */
	public function register() {
		$this->register_post_type();
		$this->register_taxonomies();
		$this->register_field_groups();

		$this->after_register();

		return true;
	}


	/**
	 * Registers the current post type with WordPress.
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type(
			$this->get_name(),
			$this->get_options()
		);
	}

	/**
	 * Registers the taxonomies declared with the current post type.
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$taxonomies = $this->get_supported_taxonomies();
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				register_taxonomy_for_object_type(
					$taxonomy,
					$this->get_name()
				);
			}
		}
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
	 * Run any code after the post type has been registered.
	 *
	 * @return void
	 */
	public function after_register() {
		// Do nothing.
	}

	/**
	 * Returns the default supported taxonomies. The subclass should declare the
	 * Taxonomies that it supports here if required.
	 *
	 * @return array<string>
	 */
	public function get_supported_taxonomies() {
		return [];
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
