<?php
/**
 * Development Dependencies.
 *
 * @since 1.0
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin;

/**
 * Development Dependencies.
 */
class DevelopmentDependencies implements ModuleInterface {

	/**
	 * Missing dependencies.
	 *
	 * @var array
	 */
	protected $missing_dependencies = [];

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		$is_cli = defined( 'WP_CLI' ) && WP_CLI;

		$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
		$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' );
		$is_local     = $is_local_env && $is_local_url;

		if ( $is_cli || ! $is_local ) {
			return;
		}

		add_action( 'init', [ $this, 'setup' ] );
		add_action( 'wp_footer', [ $this, 'render' ] );
		add_action( 'admin_footer', [ $this, 'render' ] );
	}

	/**
	 * Find errors
	 *
	 * @return void
	 */
	public function setup() {
		if ( ! defined( 'BLUEKELPIE_THEME_PATH' ) || ! defined( 'BLUEKELPIE_THEME_BUILD_PATH' ) ) {
			return;
		}

		$missing_dependency_paths = [
			WP_CONTENT_DIR . '/node_modules'        => 'Missing wp-content node_modules.',
			WP_CONTENT_DIR . '/vendor'              => 'Missing wp-content vendor.',
			BLUEKELPIE_PLUGIN_PATH . 'node_modules' => 'Missing bluekelpie-plugin node_modules.',
			BLUEKELPIE_PLUGIN_PATH . 'vendor'       => 'Missing bluekelpie-plugin vendor.',
			BLUEKELPIE_PLUGIN_BUILD_PATH            => 'Missing bluekelpie-plugin build.',
			BLUEKELPIE_THEME_PATH . 'node_modules'  => 'Missing bluekelpie-theme node_modules.',
			BLUEKELPIE_THEME_PATH . 'vendor'        => 'Missing bluekelpie-theme vendor.',
			BLUEKELPIE_THEME_BUILD_PATH             => 'Missing bluekelpie-theme build.',
		];

		foreach ( $missing_dependency_paths as $path => $message ) {
			if ( ! file_exists( $path ) ) {
				array_push( $this->missing_dependencies, $message );
			}
		}

		$incorrect_dependency_paths = [
			WP_CONTENT_DIR . '/package-lock.json'        => 'wp-content node dependencies were installed with npm instead of pnpm.',
			BLUEKELPIE_PLUGIN_PATH . 'package-lock.json' => 'bluekelpie-plugin node dependencies were installed with npm instead of pnpm.',
			BLUEKELPIE_PLUGIN_PATH . 'pnpm-lock.yaml'    => 'bluekelpie-plugin node dependencies were installed in the plugin rather than wp-content.',
			BLUEKELPIE_THEME_PATH . 'package-lock.json'  => 'bluekelpie-theme node dependencies were installed with npm instead of pnpm.',
			BLUEKELPIE_THEME_PATH . 'pnpm-lock.yaml'     => 'bluekelpie-theme node dependencies were installed in the theme rather than wp-content.',
		];

		foreach ( $incorrect_dependency_paths as $path => $message ) {
			if ( file_exists( $path ) ) {
				array_push( $this->missing_dependencies, $message );
			}
		}
	}

	/**
	 * Render the toast.
	 *
	 * @return void
	 */
	public function render() {
		if ( empty( $this->missing_dependencies ) ) {
			return;
		}
		ob_start();
		?>
		<div style='position:fixed; bottom:0; z-index:9999;'>
			<div style='
					position:absolute !important;
					bottom:1rem;
					left:1rem;
					width:36rem;
					padding:1rem;
					z-index:100;
					border-radius:0.375rem;
					background:lab(97.3564% -4.5141 27.344);
					font-size:0.875rem;
					line-height:1.4285714;
					box-shadow:
						0 10px 15px -3px rgb(0 0 0 / 0.1),
						0 4px 6px -4px rgb(0 0 0 / 0.1);
					display:flex;
					gap:0.75rem;
				'>
				<svg width='20'
					height='20'
					viewBox='0 0 20 20'
					fill='currentColor'
					aria-hidden='true'
					style='
						color:lab(83.2664% 8.6513 106.895);
						width:1.25rem;
						height:1.25rem;
						flex-shrink:0;
					'>
					<path d='M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z'
							clip-rule='evenodd'
							fill-rule='evenodd'></path>
				</svg>
				<div>
					<h1 style='
						font-size:0.875rem;
						line-height:1.4285714;
						color:lab(38.7484% 23.5833 51.4916);
						font-weight:500;
						margin:0;
					'>
						Issues were found with your development dependencies
					</h1>
					<ul style='
						color:lab(47.8202% 25.2426 66.5015);
						list-style-type:disc;
						padding-left:1.25rem;
						margin-top:0.5rem;
						display:flex;
						flex-direction:column;
						gap:0.25rem;
					'>
						<?php foreach ( $this->missing_dependencies as $dependency_message ) : ?>
							<li>
								<?php echo is_string($dependency_message) ? $dependency_message : ''; // phpcs:ignore ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore
	}
}

