<?php
/**
 * Shared paving patterns layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = is_array( $section['items'] ?? null ) ? array_filter(
	$section['items'],
	static function ( $item ) {
		return is_array( $item ) && ( ! empty( $item['image'] ) || ! empty( $item['title'] ) || ! empty( $item['text'] ) || ! empty( $item['where_suitable'] ) || ! empty( $item['advantages'] ) );
	}
) : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="patterns__wrapper">
			<header class="patterns__header">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="patterns__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="patterns__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
				<?php endif; ?>
			</header>

			<?php if ( ! empty( $section['main_image'] ) ) : ?>
				<figure class="patterns__main-media">
					<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'], 'patterns__main-image', 'full' ); ?>
				</figure>
			<?php endif; ?>

			<?php if ( $items ) : ?>
				<div class="patterns__items">
					<?php foreach ( $items as $item_index => $item ) : ?>
						<?php
						$meta_prefix     = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . absint( $args['section_index'] ?? 0 ) . '_items_' . absint( $item_index ) . '_';
						$post_id         = absint( $args['post_id'] ?? get_the_ID() );
						$advantages_text = '';
						$result_label    = '';
						$result_text     = '';
						$where_icon      = ukladka_trotuarnoy_plitki_get_image_id( $item['where_icon'] ?? get_post_meta( $post_id, $meta_prefix . 'where_icon', true ) );
						$advantages_icon = ukladka_trotuarnoy_plitki_get_image_id( $item['advantages_icon'] ?? get_post_meta( $post_id, $meta_prefix . 'advantages_icon', true ) );
						$result_icon     = ukladka_trotuarnoy_plitki_get_image_id( $item['result_icon'] ?? get_post_meta( $post_id, $meta_prefix . 'result_icon', true ) );
						$note_icon       = ukladka_trotuarnoy_plitki_get_image_id( $item['note_icon'] ?? get_post_meta( $post_id, $meta_prefix . 'note_icon', true ) );

						if ( ! empty( $item['advantages'] ) ) {
							$advantages_raw = trim( (string) $item['advantages'] );

							if ( preg_match( '/^(.*?)\s*(Результат:)\s*(.+)$/su', $advantages_raw, $result_matches ) ) {
								$advantages_text = trim( (string) $result_matches[1] );
								$result_label    = trim( (string) $result_matches[2] );
								$result_text     = trim( (string) $result_matches[3] );
							} else {
								$advantages_parts = preg_split( '/\R{2,}/u', $advantages_raw );
								$advantages_text  = trim( (string) ( $advantages_parts[0] ?? '' ) );
								$result_text      = trim( implode( "\n\n", array_slice( $advantages_parts, 1 ) ) );
							}

							$advantages_text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $advantages_text ) ) );
							$result_text     = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $result_text ) ) );

							if ( $result_text && preg_match( '/^([^:]+:)\s*(.+)$/u', wp_strip_all_tags( $result_text ), $result_matches ) ) {
								$result_label = trim( $result_matches[1] );
								$result_text  = trim( $result_matches[2] );
							}
						}
						?>
						<article class="patterns__item">
							<?php if ( ! empty( $item['image'] ) ) : ?>
								<figure class="patterns__item-media">
									<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'patterns__item-image', 'large' ); ?>
								</figure>
							<?php endif; ?>
							<div class="patterns__item-body">
								<?php if ( ! empty( $item['title'] ) ) : ?>
									<h3 class="patterns__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $item['text'] ) ) : ?>
									<p class="patterns__item-text"><?php echo esc_html( $item['text'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $item['where_suitable'] ) ) : ?>
									<div class="patterns__item-block">
										<span class="patterns__item-label">
											<?php if ( $where_icon ) : ?>
												<?php ukladka_trotuarnoy_plitki_render_image( $where_icon, 'patterns__item-label-icon', 'thumbnail' ); ?>
											<?php endif; ?>
											<span>Где подходит:</span>
										</span>
										<div class="patterns__item-text"><?php echo wp_kses_post( $item['where_suitable'] ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( $advantages_text ) : ?>
									<div class="patterns__item-block">
										<span class="patterns__item-label">
											<?php if ( $advantages_icon ) : ?>
												<?php ukladka_trotuarnoy_plitki_render_image( $advantages_icon, 'patterns__item-label-icon', 'thumbnail' ); ?>
											<?php endif; ?>
											<span>Плюсы:</span>
										</span>
										<div class="patterns__item-text"><?php echo esc_html( $advantages_text ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( $result_text ) : ?>
									<div class="patterns__item-block patterns__item-block--result">
										<?php if ( $result_label ) : ?>
											<span class="patterns__item-label">
												<?php if ( $result_icon ) : ?>
													<?php ukladka_trotuarnoy_plitki_render_image( $result_icon, 'patterns__item-label-icon', 'thumbnail' ); ?>
												<?php endif; ?>
												<span><?php echo esc_html( $result_label ); ?></span>
											</span>
										<?php endif; ?>
										<div class="patterns__item-text"><?php echo esc_html( $result_text ); ?></div>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $item['show_note'] ) && ! empty( $item['note'] ) ) : ?>
									<div class="patterns__item-block patterns__item-block--note">
										<span class="patterns__item-label">
											<?php if ( $note_icon ) : ?>
												<?php ukladka_trotuarnoy_plitki_render_image( $note_icon, 'patterns__item-label-icon', 'thumbnail' ); ?>
											<?php endif; ?>
											<span>Важный нюанс:</span>
										</span>
										<div class="patterns__item-text"><?php echo wp_kses_post( $item['note'] ); ?></div>
									</div>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php ukladka_trotuarnoy_plitki_render_button( $section, 'patterns__button' ); ?>
		</div>
	</div>
</section>
