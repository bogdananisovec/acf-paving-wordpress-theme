<?php
/**
 * Promotions layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section   = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items     = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$questions = is_array( $section['questions'] ?? null ) ? $section['questions'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'promotions' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'promotions' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="promotions__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, 'promotions' ); ?>

			<?php if ( $items ) : ?>
				<div class="promotions__items" data-mobile-slider>
					<?php foreach ( $items as $item ) : ?>
						<?php
						$for_label = $item['for_whom_title'] ?? $item['for_label'] ?? '';
						$for_text  = $item['for_whom_text'] ?? $item['for_text'] ?? '';
						?>
						<article class="promotions__item">
							<div class="promotions__item-top">
								<?php if ( ! empty( $item['image'] ) ) : ?>
									<figure class="promotions__media">
										<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'promotions__image', 'large' ); ?>
										<?php if ( ! empty( $item['badge'] ) ) : ?>
											<figcaption class="promotions__badge"><?php echo esc_html( $item['badge'] ); ?></figcaption>
										<?php endif; ?>
									</figure>
								<?php endif; ?>

								<div class="promotions__item-copy">
									<?php if ( ! empty( $item['title'] ) ) : ?>
										<h3 class="promotions__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
									<?php endif; ?>

									<?php if ( ! empty( $item['text'] ) ) : ?>
										<div class="promotions__item-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div>
									<?php endif; ?>

									<?php if ( $for_label || $for_text ) : ?>
										<div class="promotions__for">
											<span class="promotions__for-icon" aria-hidden="true">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M20 11.3867V12C19.9991 13.4376 19.5336 14.8365 18.6728 15.9879C17.8121 17.1393 16.6021 17.9817 15.2235 18.3893C13.8449 18.797 12.3714 18.748 11.0229 18.2498C9.67439 17.7516 8.52304 16.8308 7.74057 15.6248C6.95811 14.4188 6.58646 12.9921 6.68105 11.5576C6.77564 10.1231 7.33141 8.75762 8.26545 7.66479C9.1995 6.57195 10.4618 5.81033 11.8641 5.4935C13.2663 5.17668 14.7335 5.32163 16.0466 5.90674M20 6.66674L13.3333 13.3401L11.3333 11.3401" stroke="#C5A880" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
											</span>
											<div class="promotions__for-copy">
												<?php if ( $for_label ) : ?>
													<strong class="promotions__for-label"><?php echo esc_html( $for_label ); ?></strong>
												<?php endif; ?>
												<?php if ( $for_text ) : ?>
													<div class="promotions__for-text"><?php echo wp_kses_post( wpautop( $for_text ) ); ?></div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<?php ukladka_trotuarnoy_plitki_render_button( $item, 'promotions__item-button' ); ?>
						</article>
					<?php endforeach; ?>
				</div>
				<button class="promotions__slider-button promotions__slider-button--prev" type="button" aria-label="Предыдущие акции" data-promotions-scroll="previous">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true" focusable="false">
						<rect y="48" width="48" height="48" rx="12" transform="rotate(-90 0 48)" fill="#F0F0F0" fill-opacity="0.3"/>
						<path d="M34.5605 24.0001L13.4405 24.0001M24.0005 34.5601L13.4405 24.0001L24.0005 13.4401" stroke="#F9F9FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				<button class="promotions__slider-button promotions__slider-button--next" type="button" aria-label="Следующие акции" data-promotions-scroll="next">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true" focusable="false">
						<rect y="48" width="48" height="48" rx="12" transform="rotate(-90 0 48)" fill="#F0F0F0" fill-opacity="0.3"/>
						<path d="M34.5605 24.0001L13.4405 24.0001M24.0005 34.5601L13.4405 24.0001L24.0005 13.4401" stroke="#F9F9FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			<?php endif; ?>

			<?php if ( array_filter( $questions ) ) : ?>
				<aside class="promotions__questions">
					<?php if ( ! empty( $questions['icon'] ) ) : ?>
						<span class="promotions__questions-icon">
							<?php ukladka_trotuarnoy_plitki_render_image( $questions['icon'], 'promotions__questions-icon-image', 'thumbnail' ); ?>
						</span>
					<?php endif; ?>

					<span class="promotions__questions-divider" aria-hidden="true"></span>

					<div class="promotions__questions-content">
						<?php if ( ! empty( $questions['title'] ) ) : ?>
							<h3 class="promotions__questions-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $questions['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $questions['text'] ) ) : ?>
							<div class="promotions__questions-text"><?php echo wp_kses_post( wpautop( $questions['text'] ) ); ?></div>
						<?php endif; ?>
					</div>

					<?php
					$phone_button = ukladka_trotuarnoy_plitki_resolve_button( $questions );
					if ( ( empty( $phone_button['text'] ) || empty( $phone_button['url'] ) ) && ! empty( $questions['phone'] ) ) {
						$phone_button = ukladka_trotuarnoy_plitki_resolve_button(
							array(
								'button_id'   => 'telephone',
								'button_text' => $questions['phone'],
							)
						);
					}
					$phone_icon   = ukladka_trotuarnoy_plitki_get_image_id( $phone_button['icon'] ?? 0 );
					if ( ! empty( $phone_button['text'] ) && ! empty( $phone_button['url'] ) ) :
						?>
						<a class="promotions__phone" href="<?php echo esc_url( $phone_button['url'] ); ?>">
							<span class="promotions__phone-icon" aria-hidden="true">
								<?php ukladka_trotuarnoy_plitki_render_image( $phone_icon, 'promotions__phone-icon-image', 'thumbnail' ); ?>
							</span>
							<span><?php echo esc_html( $phone_button['text'] ); ?></span>
						</a>
					<?php endif; ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
