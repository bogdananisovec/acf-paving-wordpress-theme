<?php
/**
 * Problem, solution and result cards layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section        = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block          = 'problem-solution-cards';
$items          = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$meta_prefix    = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . absint( $args['section_index'] ?? 0 ) . '_';
$source_post_id = absint( $args['post_id'] ?? get_the_ID() );
$problem_icon   = ukladka_trotuarnoy_plitki_get_image_id( $section['problem_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'problem_icon', true ) );
$check_icon     = ukladka_trotuarnoy_plitki_get_image_id( $section['check_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'check_icon', true ) );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'problem_solution_cards' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $items ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__items">
					<?php foreach ( $items as $item ) : ?>
						<article class="<?php echo esc_attr( $block ); ?>__item">
							<figure class="<?php echo esc_attr( $block ); ?>__media">
								<?php ukladka_trotuarnoy_plitki_render_image( $item['image'] ?? 0, $block . '__image', 'large' ); ?>
								<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
							</figure>

							<div class="<?php echo esc_attr( $block ); ?>__item-copy">
								<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>

								<?php
								$details = array(
									array( 'icon' => $problem_icon, 'title' => $item['problem_title'] ?? '', 'text' => $item['problem_text'] ?? '' ),
									array( 'icon' => $check_icon, 'title' => $item['solution_title'] ?? '', 'text' => $item['solution_text'] ?? '' ),
									array( 'icon' => $check_icon, 'title' => $item['result_title'] ?? '', 'text' => $item['result_text'] ?? '' ),
								);
								?>
								<div class="<?php echo esc_attr( $block ); ?>__details">
									<?php foreach ( $details as $detail ) : ?>
										<div class="<?php echo esc_attr( $block ); ?>__detail">
											<div class="<?php echo esc_attr( $block ); ?>__detail-heading">
												<?php if ( $detail['icon'] ) : ?>
													<span class="<?php echo esc_attr( $block ); ?>__detail-icon"><?php ukladka_trotuarnoy_plitki_render_image( $detail['icon'], $block . '__detail-icon-image', 'full' ); ?></span>
												<?php endif; ?>
												<h4><?php echo esc_html( $detail['title'] ); ?></h4>
											</div>
											<div class="<?php echo esc_attr( $block ); ?>__detail-text"><?php echo wp_kses_post( wpautop( $detail['text'] ) ); ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="<?php echo esc_attr( $block ); ?>__action">
				<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
			</div>
		</div>
	</div>
</section>
