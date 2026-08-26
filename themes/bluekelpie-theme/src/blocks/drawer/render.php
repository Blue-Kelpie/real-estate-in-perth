<?php
/**
 * Drawer markup.
 *
 * @package bluekelpie\Blocks
 *
 * @var array    $attributes         Block attributes.
 * @var string   $content            Block content.
 * @var WP_Block $block              Block instance.
 */

wp_interactivity_state(
	'bluekelpie/drawer',
	[
		'isOpen' => false,
	]
);

$unique_id = wp_unique_id();
$drawer_id = 'drawer-' . $unique_id;
$menu_id   = 'menu-' . $unique_id;

$block_wrapper_attrs = get_block_wrapper_attributes(
	[
		'data-wp-interactive' => 'bluekelpie/drawer',
		'data-wp-on--open'    => 'callbacks.onOpen',
		'data-wp-on--close'   => 'callbacks.onClose',
	]
);
?>

<button command='show-modal' commandfor=<?php echo esc_attr( $drawer_id ); ?>>Show</button>
<button data-wp-interactive='bluekelpie/drawer' data-wp-on--click='actions.hide'>Close</button>

<?php
add_action(
	'wp_footer',
	function () use ( $content, $block_wrapper_attrs, $drawer_id, $menu_id ) {
		?>
<el-dialog <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<dialog id=<?php echo esc_attr( $drawer_id ); ?> aria-labelledby=<?php echo esc_attr( $menu_id ); ?>>
		<el-dialog-backdrop></el-dialog-backdrop>
		<div tabindex='0' class='panel-container'>
			<el-dialog-panel>
				<div class='panel'>
					<div class='panel-head-container'>
						<div class='panel-head'>
							<h2 id=<?php echo esc_attr( $menu_id ); ?> class='sr-only'>Menu</h2>
							<div class='button-container'>
								<button type='button' command='close' commandfor='drawer'>
									<span class='sr-only'>Close menu</span>
									<svg viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.5' data-slot='icon' aria-hidden='true'>
										<path d='M6 18 18 6M6 6l12 12' stroke-linecap='round' stroke-linejoin='round' />
									</svg>
								</button>
							</div>
						</div>
					</div>
					<div class='panel-body-container'>
						<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</el-dialog-panel>
		</div>
	</dialog>
</el-dialog>
		<?php
	}
);
