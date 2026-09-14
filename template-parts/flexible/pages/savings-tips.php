<?php
/**
 * Savings tips layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'savings-tips';
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$classes = (array) ( $args['classes'] ?? array() );

$button_inside_items = in_array( 'page-stoimost-ukladki-trotuarnoj-plitki', $classes, true ) && in_array( 'section-index-14', $classes, true );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>
			<div class="<?php echo esc_attr( $block ); ?>__items">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_card( $item, $block, $index ); ?>
				<?php endforeach; ?>
				<?php if ( $button_inside_items ) : ?>
					<div class="<?php echo esc_attr( $block ); ?>__button-card">
						<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! $button_inside_items ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
