<?php
/**
 * Project timeline layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'timeline';
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $rows ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ); ?>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="<?php echo esc_attr( $block ); ?>__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<span class="<?php echo esc_attr( $block ); ?>__cta-icon">
							<?php
							$cta_icon_id = ukladka_trotuarnoy_plitki_get_image_id( $cta['icon'] );
							echo wp_get_attachment_image( $cta_icon_id, 'full', false, array( 'class' => $block . '__cta-icon-image', 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</span>
					<?php endif; ?>
					<span class="<?php echo esc_attr( $block ); ?>__cta-divider" aria-hidden="true"></span>
					<div class="<?php echo esc_attr( $block ); ?>__cta-copy">
						<?php if ( ! empty( $cta['title'] ) ) : ?>
							<h3 class="<?php echo esc_attr( $block ); ?>__cta-title"><?php echo esc_html( $cta['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?>
							<div class="<?php echo esc_attr( $block ); ?>__cta-text"><?php echo wp_kses_post( $cta['text'] ); ?></div>
						<?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, $block . '__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
