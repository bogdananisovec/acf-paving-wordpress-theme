<?php
/**
 * Homepage "When to order" layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'when-order';
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="when-order__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $items ) : ?>
				<div class="when-order__items" data-mobile-slider>
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						if ( ! is_array( $item ) ) {
							continue;
						}

						$image        = $item['image'] ?? 0;
						$number       = (string) ( $item['number'] ?? sprintf( '%02d', $index + 1 ) );
						$title        = (string) ( $item['title'] ?? '' );
						$action_label = (string) ( $item['action_label'] ?? '' );
						$action_text  = (string) ( $item['action_text'] ?? '' );
						$result_label = (string) ( $item['result_label'] ?? '' );
						$result_text  = (string) ( $item['result_text'] ?? '' );
						?>
						<article class="when-order__item">
							<?php if ( $image ) : ?>
								<figure class="when-order__media">
									<?php ukladka_trotuarnoy_plitki_render_image( $image, 'when-order__image', 'large' ); ?>
								</figure>
							<?php endif; ?>

							<?php if ( $number ) : ?>
								<span class="when-order__number"><?php echo esc_html( $number ); ?></span>
							<?php endif; ?>

							<div class="when-order__item-body">
								<?php if ( $title ) : ?>
									<h3 class="when-order__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
								<?php endif; ?>

								<?php if ( $action_label || $action_text ) : ?>
									<div class="when-order__meta when-order__meta--action">
										<span class="when-order__meta-icon" aria-hidden="true">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
												<path d="M18.0631 9.5619H14.4381V5.93694L18.6672 1.70782C17.3143 1.0617 15.7944 0.850891 14.3168 1.10442C12.8391 1.35794 11.4764 2.06333 10.4163 3.12346C9.35618 4.18358 8.65078 5.54629 8.39726 7.02395C8.14373 8.5016 8.35455 10.0215 9.00067 11.3744L1.75075 18.6243C1.27005 19.105 1 19.757 1 20.4368C1 21.1166 1.27005 21.7685 1.75075 22.2492C2.23145 22.7299 2.88342 23 3.56323 23C4.24304 23 4.89501 22.7299 5.37571 22.2492L12.6256 14.9993C13.9785 15.6455 15.4984 15.8563 16.9761 15.6027C18.4537 15.3492 19.8164 14.6438 20.8765 13.5837C21.9367 12.5236 22.6421 11.1609 22.8956 9.68321C23.1491 8.20556 22.9383 6.68565 22.2922 5.33278L18.0631 9.5619Z" stroke="#C5A880" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
										<div class="when-order__meta-content">
											<?php if ( $action_label ) : ?>
												<div class="when-order__meta-label"><?php echo esc_html( $action_label ); ?></div>
											<?php endif; ?>
											<?php if ( $action_text ) : ?>
												<div class="when-order__meta-text"><?php echo wp_kses_post( wpautop( $action_text ) ); ?></div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $result_label || $result_text ) : ?>
									<div class="when-order__meta when-order__meta--result">
										<span class="when-order__meta-icon" aria-hidden="true">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
												<path d="M21 11.0801V12.0001C20.9988 14.1565 20.3005 16.2548 19.0093 17.9819C17.7182 19.7091 15.9033 20.9726 13.8354 21.584C11.7674 22.1954 9.55726 22.1219 7.53447 21.3747C5.51168 20.6275 3.78465 19.2466 2.61096 17.4371C1.43727 15.6276 0.879791 13.4865 1.02168 11.3344C1.16356 9.1823 1.99721 7.13443 3.39828 5.49599C4.79935 3.85755 6.69279 2.71637 8.79619 2.24187C10.8996 1.76737 13.1003 1.98499 15.07 2.86209M21 4.00009L11 14.0101L8 11.0101" stroke="#C5A880" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</span>
										<div class="when-order__meta-content">
											<?php if ( $result_label ) : ?>
												<div class="when-order__meta-label"><?php echo esc_html( $result_label ); ?></div>
											<?php endif; ?>
											<?php if ( $result_text ) : ?>
												<div class="when-order__meta-text"><?php echo wp_kses_post( wpautop( $result_text ) ); ?></div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="when-order__aside when-order__aside--cta">
					<div class="when-order-aside__item">
						<div class="when-order-aside__body">
							<?php if ( ! empty( $cta['icon'] ) ) : ?>
								<span class="when-order-aside__icon">
									<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'when-order-aside__icon-image', 'thumbnail' ); ?>
								</span>
							<?php endif; ?>

							<span class="when-order-aside__divider" aria-hidden="true"></span>

							<div class="when-order-aside__content">
								<?php if ( ! empty( $cta['title'] ) ) : ?>
									<h3 class="when-order-aside__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $cta['text'] ) ) : ?>
									<div class="when-order-aside__item-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
								<?php endif; ?>
							</div>

							<div class="when-order-aside__actions">
								<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'when-order-aside__item-button' ); ?>

								<?php if ( ! empty( $cta['note_text'] ) ) : ?>
									<div class="when-order-aside__note">
										<?php if ( ! empty( $cta['note_icon'] ) ) : ?>
											<span class="when-order-aside__note-icon">
												<?php ukladka_trotuarnoy_plitki_render_image( $cta['note_icon'], 'when-order-aside__note-icon-image', 'thumbnail' ); ?>
											</span>
										<?php endif; ?>
										<div class="when-order-aside__note-text"><?php echo wp_kses_post( wpautop( $cta['note_text'] ) ); ?></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
