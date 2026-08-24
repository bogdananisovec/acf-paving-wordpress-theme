<?php
/**
 * Shared review listing layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$source  = $section['source'] ?? $section['reviews_source'] ?? 'latest';
$count   = max( 1, absint( $section['count'] ?? $section['reviews_count'] ?? 6 ) );
$items   = $section['selected_reviews'] ?? $section['items'] ?? array();
$show_more = ! empty( $section['show_load_more'] );
$load_count = max( 1, absint( $section['load_more_count'] ?? 6 ) );
$query   = array(
	'post_type'      => 'client_review',
	'post_status'    => 'publish',
	'posts_per_page' => $count,
	'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
);

if ( $show_more && 'manual' !== $source ) {
	$query['posts_per_page'] = -1;
}

if ( 'manual' === $source && $items ) {
	$query['post__in']       = array_map(
		static function ( $item ) {
			return is_object( $item ) ? absint( $item->ID ?? 0 ) : absint( $item );
		},
		(array) $items
	);
	$query['orderby']        = 'post__in';
	$query['posts_per_page'] = -1;
}

$reviews = new WP_Query( $query );
$cta_markup = '';
$cta_after  = absint( $section['cta']['insert_after'] ?? 0 );

if ( ! empty( $section['cta'] ) ) {
	$cta = is_array( $section['cta'] ) ? $section['cta'] : array();
	$cta_note_text = $cta['note_text'] ?? $cta['note'] ?? '';
	ob_start();
	?>
	<article class="reviews-cta__item">
		<div class="reviews-cta__item-body">
			<?php if ( ! empty( $cta['icon'] ) ) : ?>
				<span class="reviews-cta__icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'reviews-cta__icon-image', 'thumbnail' ); ?></span>
			<?php endif; ?>
			<span class="reviews-cta__divider" aria-hidden="true"></span>
			<div class="reviews-cta__content">
				<?php if ( ! empty( $cta['title'] ) ) : ?>
					<h3 class="reviews-cta__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $cta['text'] ) ) : ?>
					<div class="reviews-cta__item-text"><?php echo wp_kses_post( wpautop( $cta['text'] ) ); ?></div>
				<?php endif; ?>
			</div>
			<div class="reviews-cta__actions">
				<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'reviews-cta__item-button' ); ?>
				<?php if ( ! empty( $cta['note_icon'] ) || ! empty( $cta_note_text ) ) : ?>
					<div class="reviews-cta__note">
						<?php if ( ! empty( $cta['note_icon'] ) ) : ?>
							<span class="reviews-cta__note-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['note_icon'], 'reviews-cta__note-icon-image', 'thumbnail' ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $cta_note_text ) ) : ?>
							<div class="reviews-cta__note-text"><?php echo wp_kses_post( wpautop( $cta_note_text ) ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
	$cta_markup = (string) ob_get_clean();
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="reviews__wrapper">
			<div class="reviews__header">
				<div class="reviews__intro">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="reviews__title"><?php echo wp_kses_post( nl2br( esc_html( $section['title'] ) ) ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="reviews__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $section['badges'] ) ) : ?>
					<div class="reviews__badges">
						<?php foreach ( $section['badges'] as $badge ) : ?>
							<div class="reviews__badge">
								<?php ukladka_trotuarnoy_plitki_render_image( $badge['icon'] ?? 0, 'reviews__badge-icon', 'thumbnail' ); ?>
								<span><?php echo wp_kses_post( $badge['text'] ?? '' ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<button class="reviews__arrow reviews__arrow--prev" type="button" data-reviews-scroll="prev" aria-label="<?php esc_attr_e( 'Предыдущие отзывы', 'ukladka-trotuarnoy-plitki' ); ?>">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<rect y="48" width="48" height="48" rx="12" transform="rotate(-90 0 48)" fill="#F0F0F0" fill-opacity="0.3"/>
					<path d="M34.5605 24.0001L13.4405 24.0001M24.0005 34.5601L13.4405 24.0001L24.0005 13.4401" stroke="#F9F9FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<button class="reviews__arrow reviews__arrow--next" type="button" data-reviews-scroll="next" aria-label="<?php esc_attr_e( 'Следующие отзывы', 'ukladka-trotuarnoy-plitki' ); ?>">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<rect y="48" width="48" height="48" rx="12" transform="rotate(-90 0 48)" fill="#F0F0F0" fill-opacity="0.3"/>
					<path d="M34.5605 24.0001L13.4405 24.0001M24.0005 34.5601L13.4405 24.0001L24.0005 13.4401" stroke="#F9F9FB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<div class="reviews__grid" data-reviews-grid data-mobile-slider>
				<?php foreach ( $reviews->posts as $review_index => $review ) : ?>
					<div class="reviews__card"<?php echo $show_more && $review_index >= $count ? ' hidden' : ''; ?>>
						<?php echo ukladka_trotuarnoy_plitki_get_review_card_html( $review->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<?php if ( $cta_markup && $cta_after === $review_index + 1 ) : ?>
						<aside class="reviews__cta"><?php echo $cta_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></aside>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php if ( $show_more && count( $reviews->posts ) > $count ) : ?>
				<button class="reviews__load-more button" type="button" data-load-more="reviews" data-load-count="<?php echo esc_attr( $load_count ); ?>">
					<?php echo esc_html( $section['load_more_text'] ?? 'Загрузить ещё' ); ?>
				</button>
			<?php endif; ?>
			<?php if ( $cta_markup && ( 0 === $cta_after || $cta_after > count( $reviews->posts ) ) ) : ?>
				<aside class="reviews__cta"><?php echo $cta_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></aside>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
