<?php
/**
 * Shared information cards layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'info_cards' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'info-cards' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="info-cards__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, 'info-cards' ); ?>

			<?php if ( $items ) : ?>
				<div class="info-cards__content">
					<div class="info-cards__items" data-mobile-slider>
						<?php foreach ( $items as $index => $item ) : ?>
							<?php
							if ( ! is_array( $item ) ) {
								continue;
							}

							$sections = is_array( $item['sections'] ?? null ) ? $item['sections'] : array();
							?>
							<article class="info-cards__item">
								<?php if ( ! empty( $item['image'] ) ) : ?>
									<figure class="info-cards__media">
										<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'info-cards__image', 'large' ); ?>
									</figure>
								<?php endif; ?>

								<div class="info-cards__item-body">
									<?php if ( ! empty( $item['number'] ) ) : ?>
										<span class="info-cards__number"><?php echo esc_html( $item['number'] ); ?></span>
									<?php endif; ?>

									<?php if ( ! empty( $item['title'] ) ) : ?>
										<h3 class="info-cards__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
									<?php endif; ?>

									<?php if ( $sections ) : ?>
										<div class="info-cards__sections">
											<?php foreach ( $sections as $section_item ) : ?>
												<?php if ( ! is_array( $section_item ) || ! array_filter( $section_item ) ) : ?>
													<?php continue; ?>
												<?php endif; ?>

												<div class="info-cards__sections-item info-cards__item-item">
													<?php if ( ! empty( $section_item['icon'] ) ) : ?>
														<span class="info-cards__icon">
															<?php ukladka_trotuarnoy_plitki_render_image( $section_item['icon'], 'info-cards__icon-image', 'thumbnail' ); ?>
														</span>
													<?php endif; ?>

													<?php if ( ! empty( $section_item['title'] ) ) : ?>
														<h4 class="info-cards__title"><?php echo esc_html( $section_item['title'] ); ?></h4>
													<?php endif; ?>

													<?php if ( ! empty( $section_item['text'] ) ) : ?>
														<div class="info-cards__text"><?php echo wp_kses_post( wpautop( $section_item['text'] ) ); ?></div>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php ukladka_trotuarnoy_plitki_render_button( $section, 'info-cards__button' ); ?>
		</div>
	</div>
</section>
