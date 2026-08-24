<?php
/**
 * Reusable portfolio grid layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block   = 'completed_works' === ( $args['layout'] ?? '' ) ? 'completed-works' : 'portfolio-grid';
$source  = $section['portfolio_source'] ?? 'latest';
$count   = max( 1, absint( $section['portfolio_count'] ?? 9 ) );
$items   = $section['portfolio_items'] ?? array();
$show_more = ! empty( $section['show_load_more'] );
$load_count = max( 1, absint( $section['load_more_count'] ?? 8 ) );
$query   = array(
	'post_type'      => 'portfolio',
	'post_status'    => 'publish',
	'posts_per_page' => $count,
);

if ( 'manual' !== $source ) {
	$query['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array(
			'key'     => 'card_title',
			'value'   => '',
			'compare' => '!=',
		),
	);
}

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

$portfolio = new WP_Query( $query );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php if ( ! empty( $section['title'] ) ) : ?><h2 class="<?php echo esc_attr( $block ); ?>__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2><?php endif; ?>
			<div class="<?php echo esc_attr( $block ); ?>__grid">
				<?php foreach ( $portfolio->posts as $item_index => $item ) : ?>
					<?php
					$item_id   = absint( $item->ID );
					$image     = function_exists( 'get_field' ) ? get_field( 'card_image', $item_id ) : 0;
					$item_title = function_exists( 'get_field' ) ? get_field( 'card_title', $item_id ) : '';
					$duration  = function_exists( 'get_field' ) ? get_field( 'duration', $item_id ) : '';
					$price     = function_exists( 'get_field' ) ? get_field( 'price', $item_id ) : '';
					?>
					<article class="<?php echo esc_attr( $block ); ?>__item"<?php echo $show_more && $item_index >= $count ? ' hidden' : ''; ?>>
						<a class="<?php echo esc_attr( $block ); ?>__link" href="<?php echo esc_url( get_permalink( $item ) ); ?>">
							<?php if ( $image ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $image, $block . '__image', 'large' ); ?>
							<?php else : ?>
								<?php echo get_the_post_thumbnail( $item, 'large', array( 'class' => $block . '__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>
							<div class="<?php echo esc_attr( $block ); ?>__card-content">
								<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item_title ?: get_the_title( $item ) ); ?></h3>
								<?php if ( $duration ) : ?><span class="<?php echo esc_attr( $block ); ?>__duration"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
								<?php if ( $price ) : ?><span class="<?php echo esc_attr( $block ); ?>__price"><?php echo esc_html( $price ); ?></span><?php endif; ?>
							</div>
							<span class="<?php echo esc_attr( $block ); ?>__arrow" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M8 2L8 14M1.5 8L8 14L14.5 8" stroke="#F9F9FB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( $show_more && count( $portfolio->posts ) > $count ) : ?>
				<button class="<?php echo esc_attr( $block ); ?>__load-more button" type="button" data-load-more="<?php echo esc_attr( $block ); ?>" data-load-count="<?php echo esc_attr( $load_count ); ?>">
					<?php echo esc_html( $section['load_more_text'] ?? 'Загрузить ещё' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
