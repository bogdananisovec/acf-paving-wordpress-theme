<?php
/**
 * Shared guarantee and quality-control layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section        = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block          = 'guarantee';
$left_card      = is_array( $section['left_card'] ?? null ) ? $section['left_card'] : ( is_array( $section['guarantee_card'] ?? null ) ? $section['guarantee_card'] : array() );
$checklist      = is_array( $section['checklist'] ?? null ) ? $section['checklist'] : ( is_array( $section['quality_checklist'] ?? null ) ? $section['quality_checklist'] : array() );
$check_note     = is_array( $section['check_note'] ?? null ) ? $section['check_note'] : ( is_array( $section['info'] ?? null ) ? $section['info'] : array() );
$problems       = is_array( $section['problems'] ?? null ) ? $section['problems'] : array();
$bottom_cta     = is_array( $section['bottom_cta'] ?? null ) ? $section['bottom_cta'] : array();
$bottom_info    = is_array( $section['bottom_info'] ?? null ) ? $section['bottom_info'] : array();
$main_image     = $section['main_image'] ?? $section['image'] ?? 0;
$checklist_name = $section['checklist_title'] ?? $section['checklist_section_title'] ?? '';
$problems_name  = $section['problems_title'] ?? $section['problems_section_title'] ?? '';
$fallback_point_icon_url = get_template_directory_uri() . '/assets/icons/guarantee-check-circle.svg';
$fallback_note_icon_url  = get_template_directory_uri() . '/assets/icons/guarantee-note-check-circle.svg';
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="guarantee__wrapper">
			<div class="guarantee__intro">
				<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>
				<?php if ( $main_image ) : ?>
					<figure class="guarantee__media">
						<?php ukladka_trotuarnoy_plitki_render_image( $main_image, 'guarantee__image', 'full' ); ?>
					</figure>
				<?php endif; ?>
			</div>

			<div class="guarantee__top">
				<?php if ( array_filter( $left_card ) ) : ?>
					<aside class="guarantee__card">
						<?php
						$left_icon   = ukladka_trotuarnoy_plitki_first_value( $left_card, array( 'icon', 'decor_icon' ) );
						$left_title  = ukladka_trotuarnoy_plitki_first_value( $left_card, array( 'title', 'name', 'label' ) );
						$left_text   = ukladka_trotuarnoy_plitki_first_value( $left_card, array( 'text', 'description', 'note', 'bottom_text' ) );
						$left_period = ukladka_trotuarnoy_plitki_first_value( $left_card, array( 'period', 'subtitle' ) );
						$left_points = is_array( $left_card['points'] ?? null ) ? $left_card['points'] : ( is_array( $left_card['list'] ?? null ) ? $left_card['list'] : ( is_array( $left_card['fixed_items'] ?? null ) ? $left_card['fixed_items'] : array() ) );
						$list_title  = $left_card['list_title'] ?? '';
						?>
						<article class="guarantee-card__item">
							<div class="guarantee-card__item-body">
								<div class="guarantee-card__main">
									<div class="guarantee-card__head">
										<?php if ( $left_icon ) : ?>
											<span class="guarantee-card__icon"><?php ukladka_trotuarnoy_plitki_render_image( $left_icon, 'guarantee-card__head-icon', 'thumbnail' ); ?></span>
										<?php endif; ?>
										<span class="guarantee-card__head-divider" aria-hidden="true"></span>
										<div class="guarantee-card__head-content">
											<?php if ( $left_title ) : ?>
												<h3 class="guarantee-card__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $left_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
											<?php endif; ?>
									<?php if ( $left_period ) : ?>
										<div class="guarantee-card__period"><?php echo wp_kses_post( wpautop( $left_period ) ); ?></div>
											<?php endif; ?>
										</div>
									</div>
									<?php if ( $left_text ) : ?>
										<div class="guarantee-card__item-text"><?php echo wp_kses_post( wpautop( $left_text ) ); ?></div>
									<?php endif; ?>
								</div>
								<?php if ( $left_points && $left_text ) : ?>
									<span class="guarantee-card__divider" aria-hidden="true"></span>
								<?php endif; ?>
								<div class="guarantee-card__actions">
							<?php if ( $left_points ) : ?>
								<div class="guarantee-card__points">
									<?php if ( $list_title ) : ?>
										<h4 class="guarantee-card__points-title"><?php echo esc_html( $list_title ); ?></h4>
											<?php endif; ?>
											<?php foreach ( $left_points as $point ) : ?>
												<?php
												$point      = is_array( $point ) ? $point : array();
												$point_icon = ukladka_trotuarnoy_plitki_first_value( $point, array( 'icon', 'decor_icon' ) );
												$point_text = ukladka_trotuarnoy_plitki_first_value( $point, array( 'text', 'description', 'title', 'label' ) );
												?>
												<?php if ( $point_text || $point_icon ) : ?>
													<div class="guarantee-card__points-item">
														<?php if ( $point_icon ) : ?>
															<span class="guarantee-card__point-icon"><?php ukladka_trotuarnoy_plitki_render_image( $point_icon, 'guarantee-card__point-icon-image', 'thumbnail' ); ?></span>
														<?php else : ?>
															<span class="guarantee-card__point-icon"><img class="guarantee-card__point-icon-image" src="<?php echo esc_url( $fallback_point_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span>
														<?php endif; ?>
														<?php if ( $point_text ) : ?>
															<div class="guarantee-card__text"><?php echo wp_kses_post( wpautop( $point_text ) ); ?></div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
									<?php ukladka_trotuarnoy_plitki_render_button( $left_card, 'guarantee-card__item-button' ); ?>
						</div>
					</div>
				</article>
					</aside>
				<?php endif; ?>

				<div class="guarantee__quality">
					<?php if ( $checklist ) : ?>
						<div class="guarantee__checklist">
							<?php if ( $checklist_name ) : ?><h3 class="guarantee__subtitle"><?php echo ukladka_trotuarnoy_plitki_format_heading( $checklist_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
							<div class="guarantee__checklist-items">
								<?php foreach ( $checklist as $index => $item ) : ?>
									<?php
									$item       = is_array( $item ) ? $item : array();
									$number     = $item['number'] ?? sprintf( '%02d', $index + 1 );
									$check_icon = $item['icon'] ?? 0;
									$title      = $item['title'] ?? '';
									$text       = $item['text'] ?? '';
									?>
									<article class="guarantee-check__item">
										<div class="guarantee-check__item-body">
											<?php if ( $number ) : ?>
												<span class="guarantee-check__number"><?php echo esc_html( $number ); ?></span>
											<?php endif; ?>
											<?php if ( $check_icon ) : ?>
												<span class="guarantee-check__icon"><?php ukladka_trotuarnoy_plitki_render_image( $check_icon, 'guarantee-check__icon-image', 'thumbnail' ); ?></span>
											<?php endif; ?>
											<?php if ( $title ) : ?>
												<h3 class="guarantee-check__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
											<?php endif; ?>
											<?php if ( $text ) : ?>
												<div class="guarantee-check__item-text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
											<?php endif; ?>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! array_filter( $check_note ) && array_filter( $bottom_info ) ) : ?>
						<?php $check_note = $bottom_info; ?>
					<?php endif; ?>

					<?php if ( array_filter( $check_note ) ) : ?>
						<aside class="guarantee__info">
							<?php
							$check_note_icon = ukladka_trotuarnoy_plitki_first_value( $check_note, array( 'icon', 'decor_icon' ) );
							$check_note_text = ukladka_trotuarnoy_plitki_first_value( $check_note, array( 'text', 'description', 'note' ) );
							?>
							<article class="guarantee-info__item">
								<div class="guarantee-info__item-body">
									<?php if ( $check_note_icon ) : ?>
										<span class="guarantee-info__icon"><?php ukladka_trotuarnoy_plitki_render_image( $check_note_icon, 'guarantee-info__icon-image', 'thumbnail' ); ?></span>
									<?php endif; ?>
									<span class="guarantee-info__divider" aria-hidden="true"></span>
									<?php if ( $check_note_text ) : ?>
										<div class="guarantee-info__item-text"><?php echo wp_kses_post( wpautop( $check_note_text ) ); ?></div>
									<?php endif; ?>
								</div>
							</article>
						</aside>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $problems ) : ?>
				<div class="guarantee__problems">
					<?php if ( $problems_name ) : ?><h3 class="guarantee__subtitle guarantee__subtitle--problems"><?php echo ukladka_trotuarnoy_plitki_format_heading( $problems_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
					<div class="guarantee__problem-items" data-mobile-slider>
						<?php foreach ( $problems as $index => $item ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_card( $item, 'guarantee-problem', $index ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( array_filter( $bottom_cta ) ) : ?>
				<aside class="guarantee__bottom">
					<?php if ( ! empty( $bottom_cta['image'] ) ) : ?>
						<figure class="guarantee__bottom-media">
							<?php ukladka_trotuarnoy_plitki_render_image( $bottom_cta['image'], 'guarantee__bottom-image', 'full' ); ?>
						</figure>
					<?php endif; ?>
					<div class="guarantee__bottom-card">
						<?php
						$bottom_title     = ukladka_trotuarnoy_plitki_first_value( $bottom_cta, array( 'title', 'name', 'label' ) );
						$bottom_text      = ukladka_trotuarnoy_plitki_first_value( $bottom_cta, array( 'text', 'description', 'note' ) );
						$bottom_note_icon = $bottom_cta['note_icon'] ?? $bottom_cta['icon'] ?? 0;
						$bottom_note_text = ukladka_trotuarnoy_plitki_first_value( $bottom_cta, array( 'note_text', 'note', 'caption', 'subtitle' ) );
						?>
						<article class="guarantee-bottom__item">
							<div class="guarantee-bottom__item-body">
								<div class="guarantee-bottom__content">
									<?php if ( $bottom_title ) : ?>
										<h3 class="guarantee-bottom__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $bottom_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
									<?php endif; ?>
									<?php if ( $bottom_text ) : ?>
										<div class="guarantee-bottom__item-text"><?php echo wp_kses_post( wpautop( $bottom_text ) ); ?></div>
									<?php endif; ?>
								</div>
								<div class="guarantee-bottom__actions">
									<?php ukladka_trotuarnoy_plitki_render_button( $bottom_cta, 'guarantee-bottom__item-button' ); ?>
									<?php if ( $bottom_note_icon || $bottom_note_text ) : ?>
										<div class="guarantee-bottom__note">
											<?php if ( $bottom_note_icon ) : ?>
												<span class="guarantee-bottom__note-icon"><?php ukladka_trotuarnoy_plitki_render_image( $bottom_note_icon, 'guarantee-bottom__note-icon-image', 'thumbnail' ); ?></span>
											<?php elseif ( $bottom_note_text ) : ?>
												<span class="guarantee-bottom__note-icon"><img class="guarantee-bottom__note-icon-image" src="<?php echo esc_url( $fallback_note_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span>
											<?php endif; ?>
											<?php if ( $bottom_note_text ) : ?>
												<div class="guarantee-bottom__note-text"><?php echo wp_kses_post( wpautop( $bottom_note_text ) ); ?></div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</article>
					</div>
				</aside>
			<?php elseif ( array_filter( $bottom_info ) && empty( $check_note ) ) : ?>
				<aside class="guarantee__bottom-info">
					<?php ukladka_trotuarnoy_plitki_render_card( $bottom_info, 'guarantee-bottom-info' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
