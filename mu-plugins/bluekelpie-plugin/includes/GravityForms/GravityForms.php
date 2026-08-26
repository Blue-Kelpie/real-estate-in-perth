<?php
/**
 * Gravity Forms.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\GravityForms;

use GFAPI;

/**
 *  Gravity Forms.
 */
class GravityForms extends BaseGravityFormsModule {

	/**
	 * Register any hooks or filters.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'gform_phone_formats', [ $this, 'build_phone_formats' ] );
		add_filter( 'gform_submit_button', [ $this, 'append_button_classes' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'set_confirmation_state' ] );
		add_filter( 'gform_get_form_filter', [ $this, 'wrap_form' ] );
	}

	/**
	 * Provides specific formatting for Australian mobile numbers
	 *
	 * @link https://gist.github.com/DeveloperWil/03b58fb68684b2b2a283f3c0ea6afde2
	 *
	 * @param array $phone_formats Phone formats.
	 * @return array
	 */
	public function build_phone_formats( $phone_formats ) {
		$phone_formats['aumobile'] = array(
			'label'       => 'Australia Mobile',
			'mask'        => '9999 999 999',
			'regex'       => '/^\({0,1}((0|\+61)(2|4|3|7|8)){0,1}\){0,1}(\ |-){0,1}[0-9]{2}(\ |-){0,1}[0-9]{2}(\ |-){0,1}[0-9]{1}(\ |-){0,1}[0-9]{3}$/',
			'instruction' => 'Australian mobile phone numbers.',
		);

		return $phone_formats;
	}

	/**
	 * Add markup to support button styles.
	 *
	 * @param string $button Button markup.
	 * @param array  $form Form markup.
	 * @return string
	 */
	public function append_button_classes(
		$button,
		$form
	) {
		$tag_processor = new \WP_HTML_Tag_Processor( $button );
		$tag_processor->next_tag( [ 'tag_name' => 'button' ] );
		$tag_processor->add_class( 'wp-element-button' );
		return $tag_processor->get_updated_html();
	}

	/**
	 * Wraps the form markup in a form slot div, so the bluekelpie/gform
	 * Interactivity API store can always find it via .closest(), regardless
	 * of the surrounding page markup.
	 *
	 * @param string $form_string Rendered form markup.
	 * @return string
	 */
	public function wrap_form( $form_string ) {
		return '<div data-slot="form">' . $form_string . '</div>';
	}

	/**
	 * Sets the bluekelpie/gform Interactivity API state with every form's
	 * confirmation message, indexed by form ID.
	 *
	 * @return void
	 */
	public function set_confirmation_state() {
		if ( ! class_exists( 'GFAPI' ) ) {
			return;
		}

		$forms = GFAPI::get_forms();

		if ( ! is_array( $forms ) ) {
			return;
		}

		$confirmations = [];

		foreach ( $forms as $form ) {
			if ( ! is_array( $form ) ) {
				continue;
			}

			$form_id = $form['id'] ?? null;

			if ( ! is_int( $form_id ) ) {
				continue;
			}

			$message = $this->get_default_confirmation_message( $form );

			if ( null !== $message ) {
				$confirmations[ strval( $form_id ) ] = $message;
			}
		}

		wp_interactivity_state(
			'bluekelpie/gform',
			[
				'confirmations' => $confirmations,
			]
		);
	}

	/**
	 * Gets a form's default confirmation message, if it has one.
	 *
	 * @param array $form Form meta, as returned by GFAPI::get_forms().
	 * @return string|null
	 */
	private function get_default_confirmation_message( $form ) {
		$confirmations = $form['confirmations'] ?? [];

		if ( ! is_array( $confirmations ) ) {
			return null;
		}

		foreach ( $confirmations as $confirmation ) {
			if ( ! is_array( $confirmation ) || empty( $confirmation['isDefault'] ) ) {
				continue;
			}

			if ( 'message' !== $confirmation['type'] ) {
				return null;
			}

			$message = $confirmation['message'];

			return is_string( $message ) ? wp_kses_post( $message ) : null;
		}

		return null;
	}
}
