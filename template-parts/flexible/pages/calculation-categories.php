<?php
/**
 * Calculation categories layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'calculation-categories';
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $items ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__items">
					<?php foreach ( $items as $item_index => $item ) : ?>
						<?php
						if ( is_numeric( $args['post_id'] ?? null ) ) {
							$meta_prefix = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . absint( $args['section_index'] ?? 0 ) . '_items_' . absint( $item_index ) . '_';

							foreach ( array( 'price_label', 'includes_icon', 'when_icon' ) as $field_name ) {
								if ( empty( $item[ $field_name ] ) ) {
									$item[ $field_name ] = get_post_meta( absint( $args['post_id'] ), $meta_prefix . $field_name, true );
								}
							}
						}
						?>
						<article class="<?php echo esc_attr( $block ); ?>__item">
							<div class="<?php echo esc_attr( $block ); ?>__item-content">
								<?php if ( ! empty( $item['image'] ) ) : ?>
									<figure class="<?php echo esc_attr( $block ); ?>__media">
										<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], $block . '__image', 'large' ); ?>
									</figure>
								<?php endif; ?>

								<div class="<?php echo esc_attr( $block ); ?>__item-body">
									<?php if ( ! empty( $item['title'] ) ) : ?>
										<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
									<?php endif; ?>

									<?php if ( ! empty( $item['price'] ) ) : ?>
										<div class="<?php echo esc_attr( $block ); ?>__price">
											<?php if ( ! empty( $item['price_label'] ) ) : ?>
												<span class="<?php echo esc_attr( $block ); ?>__price-label"><?php echo esc_html( $item['price_label'] ); ?></span>
											<?php endif; ?>
											<strong class="<?php echo esc_attr( $block ); ?>__price-value"><?php echo esc_html( $item['price'] ); ?></strong>
										</div>
									<?php endif; ?>

									<?php foreach ( array( 'includes', 'when' ) as $feature ) : ?>
										<?php if ( ! empty( $item[ $feature . '_title' ] ) || ! empty( $item[ $feature . '_text' ] ) ) : ?>
											<div class="<?php echo esc_attr( $block ); ?>__feature <?php echo esc_attr( $block ); ?>__feature--<?php echo esc_attr( $feature ); ?>">
												<?php if ( ! empty( $item[ $feature . '_icon' ] ) ) : ?>
													<span class="<?php echo esc_attr( $block ); ?>__feature-icon">
														<?php ukladka_trotuarnoy_plitki_render_image( $item[ $feature . '_icon' ], $block . '__feature-icon-image', 'full' ); ?>
													</span>
												<?php endif; ?>
												<div class="<?php echo esc_attr( $block ); ?>__feature-content">
													<?php if ( ! empty( $item[ $feature . '_title' ] ) ) : ?>
														<strong class="<?php echo esc_attr( $block ); ?>__feature-title"><?php echo esc_html( $item[ $feature . '_title' ] ); ?></strong>
													<?php endif; ?>
													<?php if ( ! empty( $item[ $feature . '_text' ] ) ) : ?>
														<div class="<?php echo esc_attr( $block ); ?>__feature-text"><?php echo wp_kses_post( $item[ $feature . '_text' ] ); ?></div>
													<?php endif; ?>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>

							<?php ukladka_trotuarnoy_plitki_render_button( $item, $block . '__item-button' ); ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
