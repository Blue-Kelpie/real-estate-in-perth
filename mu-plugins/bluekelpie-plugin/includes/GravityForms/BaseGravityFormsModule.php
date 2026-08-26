<?php
/**
 * Base class for Gravity Forms modules. Defers registration until Gravity Forms is active.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\GravityForms;

use GFAPI;
use BlueKelpiePlugin\ModuleInterface;

/**
 * Base Gravity Forms Module.
 */
abstract class BaseGravityFormsModule implements ModuleInterface {

	/**
	 * Form identifier.
	 *
	 * @var string
	 */
	protected $form_name = '';


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
		if ( is_plugin_active( 'gravityforms/gravityforms.php' ) && class_exists( 'GFAPI' ) ) {
			$this->register();
		}
	}

	/**
	 * Register any hooks or filters.
	 *
	 * @return void
	 */
	abstract public function register();

	/**
	 * Create the footer signup form once, if it does not already exist.
	 * Should fire on gform_loaded with a priority of 5.
	 *
	 * @return void
	 */
	public function maybe_add_form() {
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$form_id = $this->get_form_id();
		if ( $form_id && GFAPI::form_id_exists( $form_id ) ) {
			return;
		}

		// Prevent race conditions resulting from multiple action calls.
		if ( ! add_option( $this->get_form_option_name(), 'pending' ) ) {
			return;
		}

		$form = $this->get_form_definition();

		if ( null === $form ) {
			delete_option( $this->get_form_option_name() );
			return;
		}

		$result = GFAPI::add_form( $form );

		if ( is_wp_error( $result ) ) {
			delete_option( $this->get_form_option_name() );
			GFAPI::log_error( 'BlueKelpie form could not be created: ' . $result->get_error_message() );
			return;
		}

		update_option( $this->get_form_option_name(), $result );
	}

	/**
	 * Get the option name used for storing the form ID.
	 *
	 * @return string
	 */
	protected function get_form_option_name() {
		return 'bluekelpie-plugin-gform-id-' . $this->form_name;
	}

	/**
	 * Get the stored form ID option if it exists.
	 *
	 * @return int|null
	 */
	protected function get_form_id() {
		$option  = get_option( $this->get_form_option_name() );
		$form_id = intval( is_string( $option ) ? $option : '' );
		return $form_id > 0 ? $form_id : null;
	}

	/**
	 * Load the form definition from the bundled JSON export.
	 *
	 * @return array|null The form object, or null if it could not be loaded.
	 */
	protected function get_form_definition() {
		$json = file_get_contents( __DIR__ . '/forms/' . $this->form_name . '.json' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( false === $json ) {
			return null;
		}

		$data = json_decode( $json, true );

		if ( ! is_array( $data ) ) {
			return null;
		}

		if ( isset( $data['title'] ) ) {
			return $data;
		}

		return isset( $data[0] ) && is_array( $data[0] ) ? $data[0] : null;
	}

	/**
	 * Get a field value from an entry array.
	 *
	 * @param array      $entry Entry object.
	 * @param string|int $key Key to get.
	 * @return string
	 */
	protected function get_field( $entry, $key ) {
		return isset( $entry[ $key ] ) && is_string( $entry[ $key ] ) ? $entry[ $key ] : '';
	}
}
