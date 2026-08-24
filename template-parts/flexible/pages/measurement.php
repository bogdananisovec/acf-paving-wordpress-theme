<?php
/**
 * Measurement layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section      = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block        = 'measurement';
$check_items  = is_array( $section['check_items'] ?? null ) ? $section['check_items'] : array();
$result_items = is_array( $section['result_items'] ?? null ) ? $section['result_items'] : array();
$cta          = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
$text         = preg_replace( '/<p>(?:\s|&nbsp;|&#160;)*<\/p>/i', '', (string) ( $section['text'] ?? '' ) );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="measurement__wrapper">
			<div class="measurement__intro">
				<header class="measurement__header">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="measurement__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php endif; ?>
					<span class="measurement__divider" aria-hidden="true"></span>
					<?php if ( '' !== trim( $text ) ) : ?>
						<div class="measurement__text"><?php echo wp_kses_post( $text ); ?></div>
					<?php endif; ?>
				</header>

				<?php if ( ! empty( $section['main_image'] ) ) : ?>
					<figure class="measurement__media">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'], 'measurement__image', 'full' ); ?>
					</figure>
				<?php endif; ?>
			</div>

			<div class="measurement__panels">
				<section class="measurement__panel measurement__panel--check">
					<?php if ( ! empty( $section['check_title'] ) ) : ?>
						<h3 class="measurement__panel-title"><?php echo esc_html( $section['check_title'] ); ?></h3>
					<?php endif; ?>
					<span class="measurement__divider" aria-hidden="true"></span>
					<div class="measurement__check-items">
						<?php foreach ( $check_items as $item ) : ?>
							<div class="measurement__check-item">
								<span class="measurement__check-number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
								<span class="measurement__check-text"><?php echo esc_html( $item['text'] ?? '' ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</section>

				<section class="measurement__panel measurement__panel--result">
					<?php if ( ! empty( $section['result_title'] ) ) : ?>
						<h3 class="measurement__panel-title"><?php echo esc_html( $section['result_title'] ); ?></h3>
					<?php endif; ?>
					<span class="measurement__divider" aria-hidden="true"></span>
					<div class="measurement__result-items">
						<?php foreach ( $result_items as $item ) : ?>
							<article class="measurement__result-item">
								<?php if ( ! empty( $item['icon'] ) ) : ?>
									<span class="measurement__result-icon"><?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'measurement__result-icon-image', 'full' ); ?></span>
								<?php endif; ?>
								<span class="measurement__result-divider" aria-hidden="true"></span>
								<div class="measurement__result-content">
									<?php if ( ! empty( $item['title'] ) ) : ?>
										<h4 class="measurement__result-item-title"><?php echo esc_html( $item['title'] ); ?></h4>
									<?php endif; ?>
									<?php if ( ! empty( $item['text'] ) ) : ?>
										<div class="measurement__result-item-text"><?php echo wp_kses_post( $item['text'] ); ?></div>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</section>
			</div>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="measurement__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<span class="measurement__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'measurement__cta-icon-image', 'full' ); ?></span>
					<?php endif; ?>
					<span class="measurement__cta-divider" aria-hidden="true"></span>
					<div class="measurement__cta-content">
						<?php if ( ! empty( $cta['title'] ) ) : ?>
							<h3 class="measurement__cta-title"><?php echo esc_html( $cta['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?>
							<div class="measurement__cta-text"><?php echo wp_kses_post( $cta['text'] ); ?></div>
						<?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'measurement__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
