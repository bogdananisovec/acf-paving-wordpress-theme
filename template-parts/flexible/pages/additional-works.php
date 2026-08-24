<?php
/**
 * Additional works layout.
 *
 * Desktop keeps the shared table. Mobile presents the same ACF rows and
 * header icons as compact cards.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'additional-works';
$headers = is_array( $section['headers'] ?? null ) ? $section['headers'] : array();
$rows    = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

if ( ! empty( $section['text'] ) ) {
	$section['text'] = preg_replace( '~</p>\s*<p>~i', ' ', (string) $section['text'] );
	$section['text'] = preg_replace( '~(?:\r?\n){2,}~', ' ', (string) $section['text'] );
}

$header_icons = array_map(
	static function ( $header ) {
		return is_array( $header ) ? ( $header['icon'] ?? 0 ) : 0;
	},
	$headers
);
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="additional-works__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="additional-works__content">
				<?php if ( $rows ) : ?>
					<?php ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ); ?>

					<div class="additional-works__mobile-list">
						<?php foreach ( $rows as $row ) : ?>
							<article class="additional-works__mobile-row">
								<div class="additional-works__mobile-heading">
									<span class="additional-works__mobile-leading-icon">
										<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[0] ?? 0, 'additional-works__mobile-icon-image', 'full' ); ?>
									</span>
									<strong><?php echo esc_html( $row['work'] ?? '' ); ?></strong>
									<span class="additional-works__mobile-price"><?php echo esc_html( $row['price'] ?? '' ); ?></span>
								</div>
								<div class="additional-works__mobile-detail">
									<span class="additional-works__mobile-detail-icon">
										<?php ukladka_trotuarnoy_plitki_render_image( $header_icons[1] ?? 0, 'additional-works__mobile-icon-image', 'full' ); ?>
									</span>
									<p><?php echo esc_html( $row['when'] ?? '' ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( array_filter( $cta ) ) : ?>
					<aside class="additional-works__aside additional-works__aside--cta">
						<?php ukladka_trotuarnoy_plitki_render_card( $cta, 'additional-works-aside' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
