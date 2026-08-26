<?php
/**
 * Base API Client Module.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\Api;

/**
 * Base API Client Module.
 *
 * @phpstan-type HTTPRequestArguments array{
 *   url:string,
 *   args:array{
 *     method?:string,
 *     timeout?:float,
 *     redirection?:int,
 *     httpversion?:string,
 *     user-agent?:string,
 *     reject_unsafe_urls?:bool,
 *     blocking?:bool,
 *     headers?:string|array,
 *     cookies?:array,
 *     body?:string|array,
 *     compress?:bool,
 *     decompress?:bool,
 *     sslverify?:bool,
 *     sslcertificates?:string,
 *     stream?:bool,
 *     filename?:string,
 *     limit_response_size?:int,
 *   },
 * }
 */
abstract class BaseClientModule {

	/**
	 * API URL.
	 *
	 * @var string
	 */
	protected $api_url = '';

	/**
	 * Initialize the class.
	 */
	public function __construct() {
	}

	/**
	 * Request wrapper.
	 *
	 * @see \WP_Http::request()
	 * @param HTTPRequestArguments $http_request_args Request args, 'args' must satisfy $args in \WP_Http::request().
	 * @return array|\WP_Error
	 */
	protected function request( $http_request_args ) {
		return wp_safe_remote_request( $http_request_args['url'], $http_request_args['args'] );
	}

	/**
	 * Parse a response by validating it and logging any errors.
	 *
	 * @param array|\WP_Error $res Response.
	 * @return array|null
	 */
	protected function parse_response( $res ) {
		$result = $this->validate_response( $res );

		if ( is_wp_error( $result ) ) {
			return null;
		}

		return $result;
	}

	/**
	 * Validates a response.
	 *
	 * @param array|\WP_Error $res Response.
	 * @return array|\WP_Error
	 */
	protected function validate_response( $res ) {
		if ( is_wp_error( $res ) ) {
			return new \WP_Error(
				'bluekelpie_request_wp_error_' . esc_html( strval( $res->get_error_code() ) ),
				esc_html( $res->get_error_message() ),
				$res
			);
		}

		$res_code  = wp_remote_retrieve_response_code( $res );
		$is_ok_res = ( $res_code >= 200 && $res_code <= 299 );
		if ( ! $is_ok_res ) {
			return new \WP_Error(
				'bluekelpie_request_' . esc_html( strval( $res_code ) ),
				esc_html( wp_remote_retrieve_response_message( $res ) ),
				$res
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( ! is_array( $body ) ) {
			return new \WP_Error(
				'bluekelpie_request_invalid_body',
				esc_html( wp_remote_retrieve_response_message( $res ) ),
				$res,
			);
		}

		return $body;
	}

	/**
	 * Build request.
	 *
	 * @param array  $body Body.
	 * @param string $route Optional route.
	 * @return HTTPRequestArguments
	 */
	protected function build_request_args( $body, $route = '' ) {
		$args = [
			'url'  => $this->api_url . $route,
			'args' => [
				'method'  => 'POST',
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body'    => '',
			],
		];
		$json = wp_json_encode( $body );
		if ( is_string( $json ) ) {
			$args['args']['body'] = $json;
		}
		return $args;
	}
}
