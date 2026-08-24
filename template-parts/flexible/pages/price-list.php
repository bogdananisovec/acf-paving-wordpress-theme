<?php
/**
 * Price list layout.
 *
 * The desktop table remains the shared table renderer. Mobile uses the same
 * ACF rows in an accessible accordion matching the compact Figma topology.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'price-list';
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$note    = is_array( $section['note'] ?? null ) ? $section['note'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

$header_icons = array_map(
	static function ( $header ) {
		return is_array( $header ) ? ( $header['icon'] ?? 0 ) : 0;
	},
	$headers
);

$toggle_open_url   = get_theme_file_uri( 'assets/icons/price-list-mobile-toggle-open.svg' );
$toggle_closed_url = get_theme_file_uri( 'assets/icons/price-list-mobile-toggle-closed.svg' );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="price-list__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="price-list__content">
				<?php if ( $rows ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ); ?>

					<div class="price-list__mobile-list">
						<?php foreach ( $rows as $row_index => $row ) : ?>
							<details class="price-list__mobile-row" <?php echo 0 === $row_index ? 'open' : ''; ?>>
								<summary class="price-list__mobile-summary">
									<span class="price-list__mobile-work-icon">
										<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[0] ?? 0, 'price-list__mobile-icon-image', 'full' ); ?>
									</span>
									<span class="price-list__mobile-heading">
										<strong><?php echo esc_html( $row['work'] ?? '' ); ?></strong>
										<span><?php echo esc_html( $row['price'] ?? '' ); ?></span>
									</span>
									<span class="price-list__mobile-toggle" aria-hidden="true">
										<img class="price-list__mobile-toggle-open" src="<?php echo esc_url( $toggle_open_url ); ?>" alt="" width="24" height="24">
										<img class="price-list__mobile-toggle-closed" src="<?php echo esc_url( $toggle_closed_url ); ?>" alt="" width="24" height="24">
									</span>
								</summary>
								<div class="price-list__mobile-details">
									<div class="price-list__mobile-detail">
										<span class="price-list__mobile-detail-icon">
											<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[1] ?? 0, 'price-list__mobile-icon-image', 'full' ); ?>
										</span>
										<p><?php echo esc_html( $row['includes'] ?? '' ); ?></p>
									</div>
									<div class="price-list__mobile-detail">
										<span class="price-list__mobile-detail-icon">
											<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[2] ?? 0, 'price-list__mobile-icon-image', 'full' ); ?>
										</span>
										<p><?php echo esc_html( $row['when'] ?? '' ); ?></p>
									</div>
								</div>
							</details>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( array_filter( $note ) ) : ?>
					<aside class="price-list__aside price-list__aside--note">
						<?php ukladka_trotuarnoy_plitki_render_card( $note, 'price-list-aside' ); ?>
					</aside>
				<?php endif; ?>

				<?php if ( array_filter( $cta ) ) : ?>
					<aside class="price-list__aside price-list__aside--cta">
						<?php ukladka_trotuarnoy_plitki_render_card( $cta, 'price-list-aside' ); ?>
					</aside>
				<?php endif; ?>
			</div>

			<?php ukladka_trotuarnoy_plitki_render_button( $section, 'price-list__button' ); ?>
		</div>
	</div>
</section>
