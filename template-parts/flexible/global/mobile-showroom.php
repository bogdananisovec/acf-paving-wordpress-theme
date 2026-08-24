<?php
/**
 * Shared mobile showroom layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="mobile-showroom__wrapper">
			<div class="mobile-showroom__content">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="mobile-showroom__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="mobile-showroom__text"><?php echo wp_kses_post( $section['text'] ); ?></div>
				<?php endif; ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, 'mobile-showroom__button' ); ?>
			</div>

			<?php if ( ! empty( $section['background_image'] ) ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_image( $section['background_image'], 'mobile-showroom__background-image', 'full' ); ?>
			<?php endif; ?>
			<?php if ( ! empty( $section['car_image'] ) ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_image( $section['car_image'], 'mobile-showroom__car-image', 'full' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
