<?php
/**
 * Foundation cost layout.
 *
 * Desktop keeps the shared table. The compact mobile view reuses the same
 * ACF rows and header icons in an accessible accordion.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'foundation-cost';
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
$is_comparison = in_array( 'foundation-cost--comparison', (array) ( $args['classes'] ?? array() ), true );

$header_icons = array_map(
	static function ( $header ) {
		return is_array( $header ) ? ( $header['icon'] ?? 0 ) : 0;
	},
	$headers
);

$toggle_open_url   = get_theme_file_uri( 'assets/icons/price-list-mobile-toggle-open.svg' );
$toggle_closed_url = get_theme_file_uri( 'assets/icons/price-list-mobile-toggle-closed.svg' );

$mobile_plain_text = static function ( $value ) {
	$text = html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = preg_replace( '/<br\s*\/?\s*>/iu', ' ', $text );
	$text = preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $text ) );

	return trim( (string) $text );
};
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="foundation-cost__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="foundation-cost__content">
				<?php if ( $rows ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ); ?>

					<div class="foundation-cost__mobile-list">
						<?php foreach ( $rows as $row_index => $row ) : ?>
							<details class="foundation-cost__mobile-row" <?php echo 0 === $row_index ? 'open' : ''; ?>>
								<summary class="foundation-cost__mobile-summary">
									<span class="foundation-cost__mobile-leading-icon">
										<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[0] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
									</span>
									<span class="foundation-cost__mobile-heading">
										<strong><?php echo esc_html( $mobile_plain_text( $row['variant'] ?? '' ) ); ?></strong>
										<span><?php echo esc_html( $row['price'] ?? '' ); ?></span>
									</span>
									<span class="foundation-cost__mobile-toggle" aria-hidden="true">
										<img class="foundation-cost__mobile-toggle-open" src="<?php echo esc_url( $toggle_open_url ); ?>" alt="" width="24" height="24">
										<img class="foundation-cost__mobile-toggle-closed" src="<?php echo esc_url( $toggle_closed_url ); ?>" alt="" width="24" height="24">
									</span>
								</summary>
								<div class="foundation-cost__mobile-details">
									<?php if ( $is_comparison ) : ?>
										<div class="foundation-cost__mobile-detail">
											<span class="foundation-cost__mobile-detail-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[1] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
											</span>
											<p><?php echo esc_html( $row['where'] ?? '' ); ?></p>
										</div>
										<div class="foundation-cost__mobile-detail">
											<span class="foundation-cost__mobile-detail-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[2] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
											</span>
											<p><?php echo esc_html( $row['includes'] ?? '' ); ?></p>
										</div>
									<?php else : ?>
										<div class="foundation-cost__mobile-detail">
											<span class="foundation-cost__mobile-detail-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[1] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
											</span>
											<p><?php echo esc_html( $row['includes'] ?? '' ); ?></p>
										</div>
										<div class="foundation-cost__mobile-detail">
											<span class="foundation-cost__mobile-detail-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[2] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
											</span>
											<p><?php echo esc_html( $row['where'] ?? '' ); ?></p>
										</div>
									<?php endif; ?>
									<?php if ( $is_comparison && ! empty( $row['risk'] ) ) : ?>
										<div class="foundation-cost__mobile-detail">
											<span class="foundation-cost__mobile-detail-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[4] ?? 0, 'foundation-cost__mobile-icon-image', 'full' ); ?>
											</span>
											<p><?php echo esc_html( $row['risk'] ); ?></p>
										</div>
									<?php endif; ?>
								</div>
							</details>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( array_filter( $cta ) ) : ?>
					<aside class="foundation-cost__aside foundation-cost__aside--cta">
						<?php ukladka_trotuarnoy_plitki_render_card( $cta, 'foundation-cost-aside' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
