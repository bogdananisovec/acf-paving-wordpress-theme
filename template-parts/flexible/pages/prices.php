<?php
/**
 * Home pricing table with note and estimate CTA.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'prices' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'prices' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="prices__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, 'prices' ); ?>

			<?php if ( $rows ) : ?>
				<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, 'prices' ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $section['note_text'] ) ) : ?>
				<div class="prices__note">
					<?php if ( ! empty( $section['note_icon'] ) ) : ?>
						<span class="prices__note-icon">
							<?php ukladka_trotuarnoy_plitki_render_image( $section['note_icon'], 'prices__note-icon-image', 'thumbnail' ); ?>
						</span>
					<?php endif; ?>
					<div class="prices__note-text"><?php echo wp_kses_post( wpautop( $section['note_text'] ) ); ?></div>
				</div>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="prices__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<span class="prices__cta-icon">
							<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'prices__cta-icon-image', 'thumbnail' ); ?>
						</span>
					<?php endif; ?>
					<div class="prices__cta-copy">
						<?php if ( ! empty( $cta['title'] ) ) : ?>
							<h3 class="prices__cta-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?>
							<div class="prices__cta-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
						<?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'prices__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
