<?php
/**
 * Demo Client.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\Api;

/**
 * Demo Client.
 */
class DemoClient extends BaseClientModule {

	/**
	 * API URL.
	 *
	 * @var string
	 */
	protected $api_url = 'https://api.example.com';

	/**
	 * Demo GraphQL request
	 *
	 * @param int $post_id Event post id.
	 * @return mixed|null
	 */
	public function demo_graphql_request( $post_id ) {
		$query     = <<<'GRAPHQL'
					query GetEvent($token: String!, $id: Int!) {
					viewer(token: $token) {
						getEvent(id: $id) {
						category {
							name
						}
						genre {
							name
						}
						}
					}
					}
					GRAPHQL;
		$query     = apply_filters( 'bluekelpie_get_event_genre_query', $query );
		$variables = apply_filters(
			'bluekelpie_get_event_genre_variables',
			[
				'token' => '',
				'id'    => $post_id,
			]
		);

		$res = $this->parse_response(
			$this->request(
				$this->build_request_args(
					[
						'query'     => $query,
						'variables' => $variables,
					]
				)
			)
		);
		return $res;
	}
}
