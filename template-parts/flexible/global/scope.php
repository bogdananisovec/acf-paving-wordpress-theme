<?php
/**
 * Scope of paving work.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section   = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items     = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$side_card = is_array( $section['side_card'] ?? null ) ? $section['side_card'] : array();
$cta       = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

$side_image = $side_card['image'] ?? ( $section['side_image'] ?? 0 );
$side_title = $side_card['title'] ?? ( $section['side_title'] ?? '' );
$side_text  = $side_card['text'] ?? ( $section['side_text'] ?? '' );

if ( $cta ) {
	$cta['button_id']   = $cta['button_id'] ?? ( $cta['cta_button_id'] ?? '' );
	$cta['button_text'] = $cta['button_text'] ?? ( $cta['cta_button_text'] ?? '' );
	$cta['button_link'] = $cta['button_link'] ?? ( $cta['cta_button_link'] ?? '' );
	$cta['modal_title'] = $cta['modal_title'] ?? ( $cta['cta_modal_title'] ?? '' );
}

$cta_button = $cta ? ukladka_trotuarnoy_plitki_resolve_button( $cta ) : array();
$cta_title  = ! empty( $cta['title'] ) ? $cta['title'] : ( ! empty( $cta['button_text'] ) ? $cta['button_text'] : ( $cta_button['text'] ?? '' ) );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="scope__wrapper">
			<div class="scope__top">
				<header class="scope__header">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="scope__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="scope__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
					<?php endif; ?>
				</header>

				<figure class="scope__media">
					<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'] ?? 0, 'scope__image', 'full' ); ?>
				</figure>

				<aside class="scope__side">
					<?php if ( ! empty( $side_image ) ) : ?>
						<figure class="scope__side-media">
							<?php ukladka_trotuarnoy_plitki_render_image( $side_image, 'scope__side-image', 'large' ); ?>
						</figure>
					<?php endif; ?>
					<?php if ( ! empty( $side_title ) ) : ?><h3 class="scope__side-title"><?php echo esc_html( $side_title ); ?></h3><?php endif; ?>
					<?php if ( ! empty( $side_text ) ) : ?><div class="scope__side-text"><?php echo wp_kses_post( wpautop( $side_text ) ); ?></div><?php endif; ?>
				</aside>
			</div>

			<?php if ( $items ) : ?>
				<div class="scope__items" data-mobile-slider>
					<?php foreach ( $items as $index => $item ) : ?>
						<article class="scope__item">
							<span class="scope__number"><?php echo esc_html( $item['number'] ?? sprintf( '%02d', $index + 1 ) ); ?></span>
							<span class="scope__item-line" aria-hidden="true"></span>
							<div class="scope__item-body">
								<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="scope__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
								<?php if ( ! empty( $item['text'] ) ) : ?><div class="scope__item-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div><?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $cta && ( ! empty( $cta['icon'] ) || ! empty( $cta['title'] ) || ! empty( $cta['text'] ) || ! empty( $cta['button_text'] ) || ! empty( $cta['button_id'] ) ) ) : ?>
				<aside class="scope__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<span class="scope__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'scope__cta-icon-image', 'thumbnail' ); ?></span>
					<?php endif; ?>
					<div class="scope__cta-copy">
						<?php if ( ! empty( $cta_title ) ) : ?><h3 class="scope__cta-title"><?php echo esc_html( $cta_title ); ?></h3><?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?><div class="scope__cta-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div><?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'scope__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
