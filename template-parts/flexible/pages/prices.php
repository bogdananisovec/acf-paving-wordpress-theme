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

$header_icons = array_map(
	static function ( $header ) {
		return is_array( $header ) ? ( $header['icon'] ?? 0 ) : 0;
	},
	$headers
);

$mobile_plain_text = static function ( $value ) {
	$text = html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = preg_replace( '/<br\s*\/?\s*>/iu', ' ', $text );
	$text = preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $text ) );

	return trim( (string) $text );
};
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

				<div class="prices__mobile-list">
					<?php foreach ( $rows as $row_index => $row ) : ?>
						<?php
						$work_type = $mobile_plain_text( $row['work_type'] ?? ( $row['work'] ?? '' ) );
						$includes  = $mobile_plain_text( $row['includes'] ?? '' );
						$when      = $mobile_plain_text( $row['when'] ?? ( $row['suitable_for'] ?? '' ) );
						$price     = $mobile_plain_text( $row['price'] ?? '' );
						?>
						<details class="prices__mobile-row" <?php echo 0 === $row_index ? 'open' : ''; ?>>
							<summary class="prices__mobile-summary">
								<span class="prices__mobile-work-icon">
									<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[0] ?? 0, 'prices__mobile-icon-image', 'full' ); ?>
								</span>
								<span class="prices__mobile-heading">
									<strong><?php echo esc_html( $work_type ); ?></strong>
									<span><?php echo esc_html( $price ); ?></span>
								</span>
								<span class="prices__mobile-toggle" aria-hidden="true"></span>
							</summary>
							<div class="prices__mobile-details">
								<?php if ( $includes ) : ?>
									<div class="prices__mobile-detail">
										<span class="prices__mobile-detail-icon">
											<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[1] ?? 0, 'prices__mobile-icon-image', 'full' ); ?>
										</span>
										<p><?php echo esc_html( $includes ); ?></p>
									</div>
								<?php endif; ?>
								<?php if ( $when ) : ?>
									<div class="prices__mobile-detail">
										<span class="prices__mobile-detail-icon">
											<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[2] ?? 0, 'prices__mobile-icon-image', 'full' ); ?>
										</span>
										<p><?php echo esc_html( $when ); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</details>
					<?php endforeach; ?>
				</div>
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
