<?php
/**
 * Abstract Options Page.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF;

/**
 * Abstract Options Page.
 *
 * @phpstan-import-type FieldGroups from \BlueKelpiePlugin\ACF\AbstractPostType
 *
 * @phpstan-type OptionsPageArgs array{
 *   page_title: string,
 *   menu_title: string,
 *   menu_slug: string,
 *   capability: string,
 *   redirect: bool,
 *   autoload: bool,
 *   icon_url?: string,
 *   position?: int|string|null,
 *   parent_slug?: string,
 *   update_button?: string,
 *   updated_message?: string,
 * }
 */
abstract class AbstractOptionsPage extends BaseAcfModule {

	/**
	 * Get the page title shown in the browser tab and at the top of the page.
	 *
	 * @return string
	 */
	abstract public function get_page_title();

	/**
	 * Get the unique menu slug used to identify this options page.
	 * This value is also used as the ACF field group location value when
	 * setting `'param' => 'options_page'` in a field group's location rules.
	 *
	 * @return string
	 */
	abstract public function get_menu_slug();

	/**
	 * Get the label shown in the WP admin menu.
	 *
	 * Defaults to the page title.
	 *
	 * @return string
	 */
	public function get_menu_title() {
		return $this->get_page_title();
	}

	/**
	 * Get the dashicons class or full URL for the menu icon.
	 * Only applies to top-level pages (i.e. when get_parent_slug() returns '').
	 *
	 * @see https://developer.wordpress.org/resource/dashicons/
	 *
	 * @return string
	 */
	public function get_icon_url() {
		return 'dashicons-admin-generic';
	}

	/**
	 * Get the position in the WP admin menu.
	 * Only applies to top-level pages. Pass null to use the default position.
	 *
	 * @return int|null
	 */
	public function get_menu_position() {
		return null;
	}

	/**
	 * Get the capability required to view this options page.
	 *
	 * @return string
	 */
	public function get_capability() {
		return 'manage_options';
	}

	/**
	 * Whether this page should redirect to its first child page.
	 *
	 * @return bool
	 */
	public function is_redirect() {
		return false;
	}

	/**
	 * Whether ACF should autoload option values from this page.
	 *
	 * @return bool
	 */
	public function is_autoload() {
		return true;
	}

	/**
	 * Get the parent menu slug to nest this as a sub-page.
	 * Return an empty string to register as a top-level menu page.
	 *
	 * @return string
	 */
	public function get_parent_slug() {
		return '';
	}

	/**
	 * Get the options array passed to acf_add_options_page() / acf_add_options_sub_page().
	 *
	 * @return OptionsPageArgs
	 */
	public function get_options() {
		$options = [
			'page_title' => $this->get_page_title(),
			'menu_title' => $this->get_menu_title(),
			'menu_slug'  => $this->get_menu_slug(),
			'capability' => $this->get_capability(),
			'redirect'   => $this->is_redirect(),
			'autoload'   => $this->is_autoload(),
		];

		$icon_url = $this->get_icon_url();
		if ( '' !== $icon_url ) {
			$options['icon_url'] = $icon_url;
		}

		$position = $this->get_menu_position();
		if ( null !== $position ) {
			$options['position'] = $position;
		}

		$parent_slug = $this->get_parent_slug();
		if ( '' !== $parent_slug ) {
			$options['parent_slug'] = $parent_slug;
		}

		return $options;
	}

	/**
	 * Registers the options page and its field groups.
	 *
	 * @return void
	 */
	public function register() {
		$this->register_options_page();
		$this->register_field_groups();

		$this->after_register();
	}

	/**
	 * Registers the options page with ACF.
	 * Uses acf_add_options_sub_page() when a parent slug is set.
	 *
	 * @return void
	 */
	public function register_options_page() {
		$parent_slug = $this->get_parent_slug();

		if ( '' === $parent_slug ) {
			acf_add_options_page( $this->get_options() );
		} else {
			acf_add_options_sub_page( $this->get_options() );
		}
	}

	/**
	 * Registers the ACF field groups declared for this options page.
	 *
	 * @return void
	 */
	public function register_field_groups() {
		$field_groups = $this->get_field_groups();
		if ( ! empty( $field_groups ) ) {
			foreach ( $field_groups as $field_group ) {
				acf_add_local_field_group( $field_group );
			}
		}
	}

	/**
	 * Run any code after the options page has been registered.
	 *
	 * @return void
	 */
	public function after_register() {
		// Do nothing.
	}

	/**
	 * Returns ACF field groups for this options page.
	 *
	 * Subclasses should override this method to declare their field groups.
	 * Use get_menu_slug() as the location value, e.g.:
	 *
	 *   'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => $this->get_menu_slug()]]]
	 *
	 * @return FieldGroups
	 */
	public function get_field_groups() {
		return [];
	}
}
