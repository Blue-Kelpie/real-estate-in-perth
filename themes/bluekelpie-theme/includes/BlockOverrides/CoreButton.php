<?php
/**
 * Core Button.
 *
 * @package BlueKelpieTheme
 */

namespace BlueKelpieTheme\BlockOverrides;

use BlueKelpiePlugin\ModuleInterface;
use function BlueKelpieTheme\get_icon_svg;

/**
 * Core Button.
 */
class CoreButton implements ModuleInterface {

	/**
	 * Connects the Module with WordPress using Hooks and/or Filters.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'render_block_core/button', [ $this, 'maybe_insert_aria_label' ], 10, 2 );
		add_filter( 'render_block_core/button', [ $this, 'maybe_insert_icon' ], 10, 2 );
	}

	/**
	 * Add aria-label attribute to button.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 * @return string
	 */
	public function maybe_insert_aria_label( $content, $block ) {
		if ( ! is_array( $block['attrs'] ) || ! isset( $block['attrs']['accessibleText'] ) || ! is_string( $block['attrs']['accessibleText'] ) || empty( $block['attrs']['accessibleText'] ) || false !== strpos( $content, 'aria-label' ) ) {
			return $content;
		}
		// Optional feature: if you want your button to have no text, set it's text to 'placeholder' which will be replaced with ''.
		$content = str_replace( 'placeholder', '', $content );

		$text   = $block['attrs']['accessibleText'];
		$button = new \WP_HTML_Tag_Processor( $content );

		while ( $button->next_tag() ) {
			if ( $button->next_tag( [ 'tag_name' => 'A' ] ) ) {
				$button->set_attribute( 'aria-label', $text );
			}
		}

		return $button->get_updated_html();
	}

	/**
	 * Add icon to buttons.
	 *
	 * @param string $content Block content.
	 * @param array  $block Block data.
	 * @return string
	 */
	public function maybe_insert_icon( $content, $block ) {
		if ( ! is_array( $block['attrs'] ) || ! isset( $block['attrs']['icon'] ) || ! is_array( $block['attrs']['icon'] ) || ! is_string( $block['attrs']['icon']['name'] ) ) {
			return $content;
		}
		$svg = get_icon_svg( $block['attrs']['icon']['name'] );

		if ( empty( $svg ) ) {
			return $content;
		}

		$content = str_replace(
			'</a>',
			$svg . '</a>',
			$content
		);

		return $content;
	}
}
