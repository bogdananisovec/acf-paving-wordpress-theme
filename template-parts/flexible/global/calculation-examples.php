<?php
/**
 * Calculation examples layout — cards with task, included works, price and result.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'calculation-examples';
$items   = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
$task_icon   = ukladka_trotuarnoy_plitki_get_image_id( $section['task_icon'] ?? 0 );
$work_icon   = ukladka_trotuarnoy_plitki_get_image_id( $section['work_icon'] ?? 0 );
$result_icon = ukladka_trotuarnoy_plitki_get_image_id( $section['result_icon'] ?? 0 );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $items ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__items" data-mobile-slider>
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$includes = is_array( $item['includes'] ?? null ) ? $item['includes'] : array();
						if ( ! $includes && is_array( $item['work_items'] ?? null ) ) {
							$includes = $item['work_items'];
						}
						$parameters = is_array( $item['parameters'] ?? null ) ? $item['parameters'] : array();
						$estimate_rows = is_array( $item['estimate_rows'] ?? null ) ? $item['estimate_rows'] : array();
						$include_columns = $includes ? array_chunk( $includes, (int) ceil( count( $includes ) / 2 ) ) : array();
						if ( 9 === count( $includes ) ) {
							$include_columns = array(
								array_slice( $includes, 0, 4 ),
								array_slice( $includes, 4, 3 ),
								array_slice( $includes, 7, 2 ),
							);
						}
						$parameters_icon = ukladka_trotuarnoy_plitki_get_image_id( $item['parameters_icon'] ?? 0 );
						$has_total       = empty( $item['price'] ) && ! empty( $item['total'] );
						?>
						<article class="<?php echo esc_attr( $block ); ?>__item">
							<?php if ( ! empty( $item['image'] ) ) : ?>
								<figure class="<?php echo esc_attr( $block ); ?>__media">
									<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], $block . '__image', 'large' ); ?>
									<?php if ( ! empty( $item['number'] ) || ! empty( $item['title'] ) ) : ?>
										<figcaption class="<?php echo esc_attr( $block ); ?>__caption">
											<?php if ( ! empty( $item['number'] ) ) : ?>
												<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $item['number'] ); ?></span>
											<?php endif; ?>
											<?php if ( ! empty( $item['title'] ) ) : ?>
												<span class="<?php echo esc_attr( $block ); ?>__caption-title"><?php echo esc_html( $item['title'] ); ?></span>
											<?php endif; ?>
										</figcaption>
									<?php endif; ?>
								</figure>
							<?php endif; ?>
							<div class="<?php echo esc_attr( $block ); ?>__item-body">
								<?php if ( ! empty( $item['title'] ) && empty( $item['image'] ) ) : ?>
									<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $item['task'] ) ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__group <?php echo esc_attr( $block ); ?>__group--task">
										<span class="<?php echo esc_attr( $block ); ?>__group-title"><?php if ( $task_icon ) { ukladka_trotuarnoy_plitki_render_image( $task_icon, $block . '__group-icon', 'full' ); } ?><?php echo esc_html( $item['task_title'] ?? __( 'Задача', 'ukladka-trotuarnoy-plitki' ) ); ?></span>
										<div class="<?php echo esc_attr( $block ); ?>__task"><?php echo wp_kses_post( wpautop( $item['task'] ) ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( $parameters ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__group <?php echo esc_attr( $block ); ?>__group--parameters">
										<?php if ( ! empty( $item['parameters_title'] ) ) : ?>
											<span class="<?php echo esc_attr( $block ); ?>__group-title">
												<?php if ( $parameters_icon ) : ?>
													<?php ukladka_trotuarnoy_plitki_render_image( $parameters_icon, $block . '__group-icon', 'thumbnail' ); ?>
												<?php endif; ?>
												<span><?php echo esc_html( $item['parameters_title'] ); ?></span>
											</span>
										<?php endif; ?>
										<ul class="<?php echo esc_attr( $block ); ?>__params">
											<?php foreach ( $parameters as $parameter ) : ?>
												<?php $parameter_text = is_array( $parameter ) ? ( $parameter['text'] ?? '' ) : (string) $parameter; ?>
												<?php if ( '' !== $parameter_text ) : ?>
													<li class="<?php echo esc_attr( $block ); ?>__param"><?php echo esc_html( $parameter_text ); ?></li>
												<?php endif; ?>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
								<?php if ( $includes ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__group <?php echo esc_attr( $block ); ?>__group--includes">
										<?php $includes_title = $item['includes_title'] ?? ( $item['work_title'] ?? '' ); ?>
										<?php if ( $includes_title ) : ?>
											<span class="<?php echo esc_attr( $block ); ?>__group-title"><?php if ( $work_icon ) { ukladka_trotuarnoy_plitki_render_image( $work_icon, $block . '__group-icon', 'full' ); } ?><?php echo esc_html( $includes_title ); ?></span>
										<?php endif; ?>
										<div class="<?php echo esc_attr( $block ); ?>__lists">
											<?php foreach ( $include_columns as $include_column ) : ?>
												<ul class="<?php echo esc_attr( $block ); ?>__list">
													<?php foreach ( $include_column as $inc ) : ?>
														<?php $inc_text = is_array( $inc ) ? ( $inc['text'] ?? '' ) : (string) $inc; ?>
														<?php if ( '' !== $inc_text ) : ?>
															<li class="<?php echo esc_attr( $block ); ?>__list-item"><?php echo esc_html( $inc_text ); ?></li>
														<?php endif; ?>
													<?php endforeach; ?>
												</ul>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if ( $estimate_rows || $has_total ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__estimate-composite">
										<span class="<?php echo esc_attr( $block ); ?>__divider" aria-hidden="true"></span>
										<?php if ( $estimate_rows ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__estimate">
										<?php if ( ! empty( $item['estimate_title'] ) ) : ?>
											<strong class="<?php echo esc_attr( $block ); ?>__estimate-title"><?php echo esc_html( $item['estimate_title'] ); ?></strong>
										<?php endif; ?>
										<div class="<?php echo esc_attr( $block ); ?>__estimate-table">
											<?php foreach ( $estimate_rows as $estimate_row ) : ?>
												<div class="<?php echo esc_attr( $block ); ?>__estimate-row">
													<span><?php echo esc_html( $estimate_row['name'] ?? '' ); ?></span>
													<span><?php echo esc_html( $estimate_row['quantity'] ?? '' ); ?></span>
													<span><?php echo esc_html( $estimate_row['price'] ?? '' ); ?></span>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
										<?php endif; ?>
										<?php if ( $has_total ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__price-row">
										<span class="<?php echo esc_attr( $block ); ?>__price-title"><?php echo esc_html( $item['total_label'] ?? '' ); ?></span>
										<span class="<?php echo esc_attr( $block ); ?>__price"><?php echo esc_html( $item['total'] ); ?></span>
									</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $item['price'] ) ) : ?>
									<span class="<?php echo esc_attr( $block ); ?>__divider" aria-hidden="true"></span>
									<div class="<?php echo esc_attr( $block ); ?>__price-row">
										<?php if ( ! empty( $item['price_title'] ) ) : ?>
											<span class="<?php echo esc_attr( $block ); ?>__price-title"><?php echo esc_html( $item['price_title'] ); ?></span>
										<?php endif; ?>
										<span class="<?php echo esc_attr( $block ); ?>__price"><?php echo esc_html( $item['price'] ); ?></span>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $item['result'] ) ) : ?>
									<div class="<?php echo esc_attr( $block ); ?>__group <?php echo esc_attr( $block ); ?>__group--result">
										<?php if ( ! empty( $item['result_title'] ) ) : ?>
											<span class="<?php echo esc_attr( $block ); ?>__group-title"><?php if ( $result_icon ) { ukladka_trotuarnoy_plitki_render_image( $result_icon, $block . '__group-icon', 'full' ); } ?><?php echo esc_html( $item['result_title'] ); ?></span>
										<?php endif; ?>
										<div class="<?php echo esc_attr( $block ); ?>__result"><?php echo wp_kses_post( wpautop( $item['result'] ) ); ?></div>
									</div>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $cta && ( ! empty( $cta['icon'] ) || ! empty( $cta['button_text'] ) || ! empty( $cta['button_id'] ) || ! empty( $cta['image'] ) ) ) : ?>
				<aside class="<?php echo esc_attr( $block ); ?>__cta">
					<div class="<?php echo esc_attr( $block ); ?>__cta-content">
						<?php if ( ! empty( $cta['icon'] ) ) : ?>
							<span class="<?php echo esc_attr( $block ); ?>__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], $block . '__cta-icon-image', 'full' ); ?></span>
						<?php endif; ?>
						<span class="<?php echo esc_attr( $block ); ?>__cta-divider" aria-hidden="true"></span>
						<?php ukladka_trotuarnoy_plitki_render_button( $cta, $block . '__button' ); ?>
					</div>
					<?php if ( ! empty( $cta['image'] ) ) : ?>
						<figure class="<?php echo esc_attr( $block ); ?>__cta-media">
							<?php ukladka_trotuarnoy_plitki_render_image( $cta['image'], $block . '__cta-image', 'large' ); ?>
						</figure>
					<?php endif; ?>
				</aside>
			<?php else : ?>
				<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
