<?php
/**
 * Block markup
 *
 * @package bluekelpie\Blocks
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 */

wp_interactivity_state(
	'bluekelpie/example-block',
	[]
);
?>

<div <?php echo get_block_wrapper_attributes( [ 'data-wp-interactive' => 'bluekelpie/example-block' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	Front-end
</div>
