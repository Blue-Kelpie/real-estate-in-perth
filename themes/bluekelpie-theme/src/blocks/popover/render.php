<?php
/**
 * Popover markup.
 *
 * @package bluekelpie\Blocks
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 */

$unique_id  = wp_unique_id();
$popover_id = 'popover-' . $unique_id;
$label      = is_string( $attributes['label'] ) ? $attributes['label'] : '';

$block_wrapper_attrs = get_block_wrapper_attributes(
	[
		'id' => $popover_id,
	]
);
?>

<button popovertarget=<?php echo esc_attr( $popover_id ); ?>>
	<?php echo esc_html( $label ); ?>
</button>

<?php
add_action(
	'wp_footer',
	function () use ( $content, $block_wrapper_attrs ) {
		?>
<el-popover popover <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<!-- Presentational element used to render the bottom shadow, if we put the shadow on the actual panel it pokes out the top, so we use this shorter element to hide the top of the shadow -->
	<div aria-hidden='true' class='bottom-shadow-container'></div>
	<div tabindex='0' class='container'>
		<div class='content'>
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</el-popover>
		<?php
	}
);
