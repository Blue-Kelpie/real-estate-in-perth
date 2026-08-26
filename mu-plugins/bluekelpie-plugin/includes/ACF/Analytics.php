<?php
/**
 * Analytics setup.
 *
 * @package BlueKelpiePlugin
 */

namespace BlueKelpiePlugin\ACF;

use function BlueKelpiePlugin\get_asset_info;

/**
 * Analytics module.
 */
class Analytics extends BaseAcfModule {

	/**
	 * Registers the analytics hooks once ACF Pro is active.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_head', [ $this, 'add_analytics' ] );
		add_action( 'wp_head', [ $this, 'add_gtag_to_wp_head' ] );
		add_action( 'wp_head', [ $this, 'add_facebook_pixel_to_wp_head' ] );
		add_action( 'wp_body_open', [ $this, 'add_gtag_noscript_to_wp_body' ] );
		add_action( 'wp_body_open', [ $this, 'add_facebook_pixel_noscript_to_wp_body' ] );
	}

	/**
	 * Add Analytics scripts to <head>.
	 *
	 * @return void
	 */
	public function add_analytics() {
		$id = get_field( 'google_integrations_id', 'option' );
		if ( ! is_string( $id ) || ! is_numeric( $id ) ) {
			return;
		}

		wp_enqueue_script( 'google-analytics', 'https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $id ), [], BLUEKELPIE_PLUGIN_VERSION, [ 'in_footer' => true ] );

		ob_start(); ?>
		<!-- Google Analytics -->
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '{<?php echo esc_attr( $id ); ?>}');
		</script>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add Google Tag Manager scripts to <head>.
	 *
	 * @return void
	 */
	public function add_gtag_to_wp_head() {
		$id = get_field( 'google_tag_manager_container_id', 'option' );
		if ( ! is_string( $id ) || ! is_numeric( $id ) ) {
			return;
		}

		ob_start();
		?>
		<!-- Google Tag Manager -->
		<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','{<?php echo esc_attr( $id ); ?>}');
		</script>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add Facebook Pixel scripts to <head>.
	 *
	 * @return void
	 */
	public function add_facebook_pixel_to_wp_head() {
		$id = get_field( 'facebook_pixel_id', 'option' );
		if ( ! is_string( $id ) || ! is_numeric( $id ) ) {
			return;
		}

		$id = esc_attr( $id );

		ob_start();
		?>
		<!-- Facebook Pixel -->
		<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '{<?php echo esc_attr( $id ); ?>}');
			fbq('track', 'PageView');
		</script>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add Facebook Pixel (noscript) to <body>.
	 *
	 * @return void
	 */
	public function add_facebook_pixel_noscript_to_wp_body() {
		$id = get_field( 'facebook_pixel_id', 'option' );
		if ( ! is_string( $id ) || ! is_numeric( $id ) ) {
			return;
		}
		ob_start();
		?>
		<!-- Facebook Pixel Code (noscript) -->
		<noscript>
			<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id={<?php echo esc_attr( $id ); ?>}&ev=PageView&noscript=1'>
		</noscript>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add Google Tag Manager (noscript) scripts to <body>.
	 *
	 * @return void
	 */
	public function add_gtag_noscript_to_wp_body() {
		$id = get_field( 'google_tag_manager_container_id', 'option' );
		if ( ! is_string( $id ) || ! is_numeric( $id ) ) {
			return;
		}
		ob_start();
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src='https://www.googletagmanager.com/ns.html?id={<?php echo esc_attr( $id ); ?>}' height='0' width='0' style='display:none;visibility:hidden'></iframe>
		</noscript>
		<?php
		$out = ob_get_clean();
		echo is_string( $out ) ? $out : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
