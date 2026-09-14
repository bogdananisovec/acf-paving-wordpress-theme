<?php
/**
 * Home process steps layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section   = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items     = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$side_card = is_array( $section['side_card'] ?? null ) ? $section['side_card'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="steps__wrapper">
			<header class="steps__header">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="steps__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="steps__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</header>

			<div class="steps__items">
				<?php foreach ( $items as $index => $item ) : ?>
					<article class="steps__item">
						<div class="steps__progress">
							<span><?php echo esc_html( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<i aria-hidden="true"></i>
						</div>
						<?php if ( ! empty( $item['image'] ) ) : ?>
							<figure class="steps__media">
								<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'steps__image', 'medium_large' ); ?>
								<?php if ( ! empty( $item['icon'] ) ) : ?>
									<?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'steps__icon', 'thumbnail' ); ?>
								<?php endif; ?>
							</figure>
						<?php endif; ?>
						<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="steps__item-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
						<?php if ( ! empty( $item['text'] ) ) : ?><p class="steps__item-text"><?php echo esc_html( $item['text'] ); ?></p><?php endif; ?>
					</article>
				<?php endforeach; ?>

				<?php if ( array_filter( $side_card ) ) : ?>
					<aside class="steps__side-card work-steps__side-card">
						<div class="steps__side-content work-steps__side-content">
							<?php if ( ! empty( $side_card['icon'] ) ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $side_card['icon'], 'steps__side-icon work-steps__side-icon', 'thumbnail' ); ?>
							<?php endif; ?>
							<div class="steps__side-copy work-steps__side-copy">
								<?php if ( ! empty( $side_card['title'] ) ) : ?><h3 class="steps__side-title work-steps__side-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $side_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
								<?php if ( ! empty( $side_card['text'] ) ) : ?><p class="steps__side-text work-steps__side-text"><?php echo esc_html( $side_card['text'] ); ?></p><?php endif; ?>
							</div>
						</div>
						<div class="steps__side-action work-steps__side-action">
							<?php if ( ! empty( $side_card['button_heading'] ) ) : ?><strong class="steps__side-heading work-steps__side-heading"><?php echo esc_html( $side_card['button_heading'] ); ?></strong><?php endif; ?>
							<?php ukladka_trotuarnoy_plitki_render_button( $side_card, 'steps__side-button work-steps__side-button' ); ?>
						</div>
					</aside>
				<?php endif; ?>

			</div>
		</div>
	</div>
</section>
