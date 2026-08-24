<?php
/** Price factors layout. */
$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = array_values( array_filter( (array) ( $section['items'] ?? array() ), 'is_array' ) );
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ?? 'price_factors' ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'price-factors' ) ) ); ?>">
	<div class="container">
		<div class="price-factors__wrapper">
			<div class="price-factors__intro">
				<header class="price-factors__header">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="price-factors__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="price-factors__text"><?php echo wp_kses_post( $section['text'] ); ?></div>
					<?php endif; ?>
				</header>
				<?php if ( ! empty( $section['main_image'] ) ) : ?>
					<figure class="price-factors__media"><?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'], 'price-factors__image', 'full' ); ?></figure>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="price-factors__items">
					<?php foreach ( array_chunk( $items, 2 ) as $pair ) : ?>
						<div class="price-factors__row">
							<?php foreach ( $pair as $item ) : ?>
								<article class="price-factors__item">
									<div class="price-factors__item-side">
										<?php if ( ! empty( $item['number'] ) ) : ?><span class="price-factors__number"><?php echo esc_html( $item['number'] ); ?></span><?php endif; ?>
										<?php if ( ! empty( $item['icon'] ) ) : ?><span class="price-factors__icon"><?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'price-factors__icon-image', 'full' ); ?></span><?php endif; ?>
									</div>
									<div class="price-factors__item-body">
										<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="price-factors__item-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
										<?php if ( ! empty( $item['text'] ) ) : ?><div class="price-factors__item-text"><?php echo wp_kses_post( $item['text'] ); ?></div><?php endif; ?>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<div class="price-factors__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?><span class="price-factors__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'price-factors__cta-icon-image', 'full' ); ?></span><?php endif; ?>
					<div class="price-factors__cta-copy">
						<?php if ( ! empty( $cta['title'] ) ) : ?><h3 class="price-factors__cta-title"><?php echo esc_html( $cta['title'] ); ?></h3><?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?><div class="price-factors__cta-text"><?php echo wp_kses_post( $cta['text'] ); ?></div><?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'price-factors__cta-button' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
