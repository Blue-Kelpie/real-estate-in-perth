<?php
/**
 * Abstract Route.
 * Derived from WooCommerce's AbstractRoute and AbstractCartRoute.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 * @see \Automattic\WooCommerce\StoreApi\Routes\V1\AbstractRoute
 * @see \Automattic\WooCommerce\StoreApi\Routes\V1\AbstractCartRoute
 */

namespace BlueKelpiePlugin\RestApi;

use BlueKelpiePlugin\ModuleInterface;

/**
 * Abstract Route.
 */
abstract class AbstractRoute implements ModuleInterface {

	/**
	 * Route namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'bk';

	/**
	 * Route path.
	 *
	 * @var string
	 */
	protected $path = '';

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		$namespace = $this->namespace;
		$path      = $this->path;

		if ( ! $namespace || ! $path ) {
			return;
		}

		register_rest_route(
			$namespace,
			$path,
			$this->get_args()
		);
	}

	/**
	 * Get args schema.
	 *
	 * @return array
	 */
	abstract protected function get_args();

	/**
	 * Get the route response based on the type of request.
	 *
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_response( $request ) {
		$response    = null;
		$nonce_check = $this->requires_nonce( $request ) ? $this->check_nonce( $request ) : null;

		if ( is_wp_error( $nonce_check ) ) {
			$response = $nonce_check;
		}

		if ( ! $response ) {
			try {
				$response = $this->get_route_response( $request );
			} catch ( \Exception $error ) {
				$response = $this->get_route_error_response( 'bk_rest_unknown_server_error', $error->getMessage(), 500 );
			}
		}

		// Format error responses.
		if ( is_wp_error( $response ) ) {
			$response = $this->error_to_response( $response );
		}

		return $this->add_response_headers( rest_ensure_response( $response ) );
	}

	/**
	 * Are we updating data or getting data?
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return boolean
	 */
	protected function is_update_request( $request ) {
		return in_array( $request->get_method(), [ 'POST', 'PUT', 'PATCH', 'DELETE' ], true );
	}

	/**
	 * Checks if a nonce is required for the route.
	 *
	 * @param \WP_REST_Request $request Request.
	 *
	 * @return bool
	 */
	protected function requires_nonce( $request ) {
		return $this->is_update_request( $request );
	}

	/**
	 * For non-GET endpoints, require and validate a nonce to prevent CSRF attacks.
	 *
	 * Nonces will mismatch if the logged in session cookie is different! If using a client to test, set this cookie
	 * to match the logged in cookie in your browser.
	 *
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_Error|boolean
	 */
	protected function check_nonce( $request ) {
		$nonce = null;

		if ( $request->get_header( 'Nonce' ) ) {
			$nonce = $request->get_header( 'Nonce' );
		}

		if ( null === $nonce ) {
			return $this->get_route_error_response( 'bk_rest_missing_nonce', 'Missing the Nonce header. This endpoint requires a valid nonce.', 401 );
		}

		if ( ! wp_verify_nonce( $nonce, 'bk_api' ) ) {
			return $this->get_route_error_response( 'bk_rest_invalid_nonce', 'Nonce is invalid.', 403 );
		}

		return true;
	}

	/**
	 * Handle the request and return a valid response for this endpoint.
	 * .
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	abstract protected function get_route_response( $request );

	/**
	 * Get route response when something went wrong.
	 *
	 * @param string $error_code String based error code.
	 * @param string $error_message User facing error message.
	 * @param int    $http_status_code HTTP status. Defaults to 500.
	 * @param array  $additional_data Extra data (key value pairs) to expose in the error response.
	 *
	 * @return \WP_Error WP Error object.
	 */
	protected function get_route_error_response( $error_code, $error_message, $http_status_code = 500, $additional_data = [] ) {
		$additional_data['status'] = $http_status_code;

		return new \WP_Error( $error_code, $error_message, $additional_data );
	}

	/**
	 * Converts an error to a response object. Based on \WP_REST_Server.
	 *
	 * @param \WP_Error $error WP_Error instance.
	 * @return \WP_REST_Response List of associative arrays with code and message keys.
	 */
	protected function error_to_response( $error ) {
		$error_data = $error->get_error_data();
		$status     = is_array( $error_data ) && isset( $error_data['status'] ) && is_int( $error_data['status'] ) ? $error_data['status'] : 500;
		$errors     = [];

		foreach ( (array) $error->errors as $code => $messages ) {
			foreach ( (array) $messages as $message ) {
				$errors[] = [
					'code'    => $code,
					'message' => $message,
					'data'    => $error->get_error_data( $code ),
				];
			}
		}

		$data = array_shift( $errors );

		if ( count( $errors ) ) {
			$data['additional_errors'] = $errors;
		}

		return new \WP_REST_Response( $data, $status );
	}

	/**
	 * Add nonce headers to a response object.
	 *
	 * @param \WP_REST_Response $response The response object.
	 *
	 * @return \WP_REST_Response
	 */
	protected function add_response_headers( $response ) {
		$nonce = wp_create_nonce( 'bk_api' );

		$response->header( 'Nonce', $nonce );
		$response->header( 'Nonce-Timestamp', strval( time() ) );
		$response->header( 'User-ID', strval( get_current_user_id() ) );

		return $response;
	}
}
