<?php
/**
 * Demo Form.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\GravityForms;

use BlueKelpiePlugin\Api\DemoClient;

/**
 * Demo Form.
 *
 * Illustrates how a Gravity Forms module can auto-create its own form from a
 * bundled JSON export and forward submissions to an API client on completion.
 */
class Demo extends BaseGravityFormsModule {

	/**
	 * Option key storing the ID of the form once it has been created.
	 *
	 * @var string
	 */
	protected $form_name = 'demo-form';

	/**
	 * Demo API client.
	 *
	 * @var DemoClient
	 */
	protected $demo_client;

	/**
	 * Constructor.
	 *
	 * @param DemoClient $demo_client Demo API client.
	 */
	public function __construct( DemoClient $demo_client ) {
		$this->demo_client = $demo_client;
	}

	/**
	 * Register any hooks or filters.
	 *
	 * @return void
	 */
	public function register() {
		$this->maybe_add_form();

		$form_id = $this->get_form_id();
		if ( $form_id ) {
			add_action( "gform_after_submission_{$form_id}", [ $this, 'after_submission' ], 10, 2 );
		}
	}

	/**
	 * Forward the submission to the demo API client after successful submission.
	 *
	 * @param array $entry The Entry object.
	 * @param array $form The Form object.
	 * @return void
	 */
	public function after_submission( $entry, $form ) {
		$fields = [
			'id'         => $this->get_field( $entry, 'id' ),
			'first_name' => $this->get_field( $entry, 1 ),
			'last_name'  => $this->get_field( $entry, 2 ),
			'email'      => $this->get_field( $entry, 3 ),
		];

		/**
		 * Filters the fields extracted from a demo form submission before they
		 * are forwarded to the demo API client.
		 *
		 * @param array $fields Extracted submission fields.
		 * @param array $entry  The Entry object.
		 * @param array $form   The Form object.
		 */
		$fields = apply_filters( 'bluekelpie_demo_form_submission_fields', $fields, $entry, $form );

		if ( ! is_string( $fields['id'] ) || empty( $fields['id'] ) ) {
			return;
		}

		$this->demo_client->demo_graphql_request( intval( $fields['id'] ) );
	}
}
